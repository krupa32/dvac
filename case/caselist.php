<?php
	include "../common/config.php";
	include "../common/utils.php";

	function get_last_activity($db, $case_id)
	{
		$last_activity = "None";

		$q = "select ts from activities where case_id=$case_id order by ts desc limit 0,1";
		$res = $db->query($q);
		if (!$res)
			goto out1;
		if ($res->num_rows == 0)
			goto out2;

		$row = $res->fetch_row();
		$last_activity = relative_time($row[0]);

out2:
		$res->close();
out1:
		return $last_activity;
	}


	function get_team_ids($db, $id, $grade)
	{
		$team = array();
		$team[] = $id;

		if ($grade == 10) // inspector, no team, single
			goto out1;

		$q = "select id,grade from users where reporting_to=$id";
		$res = $db->query($q);
		if (!$res)
			goto out1;
		if ($res->num_rows == 0)
			goto out2;

		while ($row = $res->fetch_assoc()) {
			$subteam = get_team_ids($db, $row["id"], $row["grade"]);
			$team = array_merge($team, $subteam);
		}

out2:
		$res->close();
out1:
		return $team;
	}


	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$start = $_GET["start_item"];
	$rows = $num_items_per_fetch;

	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/*
	 * select list of cases based on the type.
	 * this select is global, irrespective of relevance of the case
	 * to the current user. the list is filtered later.
	 */
	switch ($_GET["type"]) {
	case "assigned":
		$q = "select cases.id,case_num,status,investigator,petitioner,next_hearing,location,grade from cases,users " . 
			"where assigned_to=${_SESSION["user_id"]} and investigator=users.id ";
		break;
	case "upcoming_hearings":
		$from = mktime() - 24*60*60;
		$q = "select cases.id,case_num,status,investigator,petitioner,next_hearing,location from cases,users " . 
			"where next_hearing >= $from and investigator=users.id " .
			"order by next_hearing ";
		break;
	case "my":
		$q = "select cases.id,case_num,status,investigator,petitioner,next_hearing,location,grade from cases,users " . 
			"where investigator=users.id ";
		break;
	/* below cases are obsolete */
	case "search":
		$field = $_GET["field"];
		$data = $_GET["data"];
		$q = "select id from cases where $field like '%$data%' limit $start,$rows";
		if ($field == "assigned_to" || $field == "investigator") /* special cases where data contains id */
			$q = "select id from cases where $field=$data limit $start,$rows";
		if ($field == "location")
			$q = "select cases.id from cases,users where cases.investigator=users.id and users.location=$data limit $start,$rows";
		break;
	case "pending_court":
		$q = "select id from cases where status=${statuses['PENDING_IN_COURT']} limit $start,$rows";
		break;
	case "pending_dvac":
		$q = "select id from cases where status=${statuses['PENDING_WITH_DVAC']} limit $start,$rows";
		break;
	case "category":
		$name = $_GET["name"];
		$q = "select id from cases where category=${categories[$name]} limit $start,$rows";
		break;
	case "nohearings":
		$q = "select id from cases where next_hearing = 0 and status = ${statuses['PENDING_IN_COURT']} limit $start,$rows";
		break;
	}

	$before = gettimeofday(true);

	/* get all cases */
	$allcases = array();
	$res = $db->query($q);
	while ($row = $res->fetch_assoc())
		$allcases[] = $row;
	$res->close();
	error_log("num allcases=" . count($allcases));

	/* get ids of all officers in current user's team.
	 * team is defined as all users reporting to current user.
	 */
	$team_ids = get_team_ids($db, $_SESSION["user_id"], $_SESSION["user_grade"]);
	error_log("teamids = " . implode(",", $team_ids));

	/* remove cases not investigated by team */
	$cases = array();
	foreach ($allcases as $case) {
		error_log("checking id ${case["investigator"]} in teamid");
		if (in_array($case["investigator"], $team_ids))
			$cases[] = $case;
	}
	error_log("num cases=" . count($cases));

	/* translate numbers in the result to readable texts */
	for ($i = 0; $i < count($cases); $i++) {
		$cases[$i]["status"] = array_search($cases[$i]["status"], $statuses);	
		$cases[$i]["investigator"] = get_name_grade($cases[$i]["investigator"]);
		$cases[$i]["location"] = array_search($cases[$i]["location"], $locations);
		$cases[$i]["last_activity"] = get_last_activity($db, $cases[$i]["id"]);
		if ($cases[$i]["next_hearing"] == 0)
			$cases[$i]["next_hearing"] = "None";
		else
			$cases[$i]["next_hearing"] = absolute_date($cases[$i]["next_hearing"]);

	}

	/* obtain columns which would be used for multisort */
	foreach ($cases as $key => $val) {
		$locs[$key] = $val["location"];
		$g[$key] = $val["grade"];
	}

	/* multisort, first by location, then by grade.
	 * the last arg $cases is just reordered based on the sorting
	 * done on first two args.
	 */
	array_multisort($locs, $g, SORT_DESC, $cases);

	/* apply start and rows now */
	$cases = array_slice($cases, $start, $rows);


	$after = gettimeofday(true);

	$ret["cases"] = $cases;
	$ret["latency"] = ($after - $before) * 1000; // in millisec

	$db->close();
out:
	print json_encode($ret);
?>
