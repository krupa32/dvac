<?php
	include "../common/config.php";
	include "../common/utils.php";

	function get_last_activity($db, $case_id)
	{
		$q = "select ts from activities where case_id=$case_id order by ts desc limit 0,1";
		$res = $db->query($q);
		if (!$res || $res->num_rows == 0) {
			$last_activity = "None";
			goto out;
		}

		$row = $res->fetch_row();
		$last_activity = relative_time($row[0]);

out:
		$res->close();
		return $last_activity;
	}


	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$start = $_GET["start_item"];
	$rows = $num_items_per_fetch;

	$ret = array();
	$cases = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/*
	 * select list of cases based on the type 
	 */
	switch ($_GET["type"]) {
	case "my":
		$q = "select cases.id,case_num,status,investigator,petitioner,next_hearing,location from cases,users " . 
			"where assigned_to=${_SESSION['user_id']} and investigator=users.id " . 
			"limit $start,$rows";
		break;
	case "upcoming_hearings":
		$from = mktime() - 24*60*60;
		$q = "select cases.id,case_num,status,investigator,petitioner,next_hearing,location from cases,users " . 
			"where next_hearing >= $from and investigator=users.id " .
			"order by next_hearing " .
			"limit $start,$rows";
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

	$res = $db->query($q);

	while ($row = $res->fetch_assoc()) {
		$row["status"] = array_search($row["status"], $statuses);	
		$row["investigator"] = get_name_grade($row["investigator"]);
		$row["location"] = array_search($row["location"], $locations);
		$row["last_activity"] = get_last_activity($db, $row["id"]);
		if ($row["next_hearing"] == 0)
			$row["next_hearing"] = "None";
		else
			$row["next_hearing"] = absolute_date($row["next_hearing"]);

		$cases[] = $row;
	}

	$res->close();
	$db->close();

	$after = gettimeofday(true);

	$ret["cases"] = $cases;
	$ret["latency"] = ($after - $before) * 1000; // in millisec

out:
	print json_encode($ret);
?>
