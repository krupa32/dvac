<?php
	include "../common/config.php";
	include "../common/utils.php";

	function get_proceeding_details($db, $proc_id)
	{
		global $courts, $counsels, $disposals;

		$q = "select court,hall,judge,counsel,disposal,next_hearing,comment from proceedings where id=$proc_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		$row["court"] = array_search($row["court"], $courts);
		$row["counsel"] = array_search($row["counsel"], $counsels);
		$row["disposal"] = array_search($row["disposal"], $disposals);
		if ($row["next_hearing"]) {
			$dt = date("M d, Y", $row["next_hearing"]);
			$row["next_hearing"] = $dt; // Mar 04, 2014
		} else {
			$row["next_hearing"] = "None";
		}
		return $row;
	}

	function get_comment_details($db, $comment_id)
	{
		$q = "select comment from comments where id=$comment_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		return $row;
	}

	function get_assignment_details($db, $assn_id)
	{
		$q = "select target,comment from assignments where id=$assn_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		$row["target"] = get_name_grade($row["target"]);
		return $row;
	}

	function get_status_details($db, $status)
	{
		global $statuses;

		$ret["status"] = array_search($status, $statuses);

		return $ret;
	}

	function get_attachment_details($db, $attachment_id)
	{
		global $upload_dir;

		$q = "select name from attachments where id=$attachment_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$ext = pathinfo($row["name"], PATHINFO_EXTENSION);
		$row["link"] = "$upload_dir/$attachment_id.$ext";
		$res->close();
		return $row;
	}



	function get_activity_details($db, $type, $object)
	{
		$details = null;

		switch ($type) {
		case "ADDPROCEEDING":
			$details = get_proceeding_details($db, $object);
			break;
		case "ADDCOMMENT":
			$details = get_comment_details($db, $object);
			break;
		case "ASSIGN":
			$details = get_assignment_details($db, $object);
			break;
		case "CHANGESTATUS":
			$details = get_status_details($db, $object);
			break;
		case "ATTACH":
			$details = get_attachment_details($db, $object);
			break;
		}

		return $details;
	}

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$start = $_GET["start_item"];
	$rows = $num_items_per_fetch;

	$ret = array();
	$cases = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$before = gettimeofday(true);

	/* get ids of all officers in current user's team.
	 * team is defined as all users reporting to current user.
	 */
	$team_ids = get_team_ids($db, $_SESSION["user_id"], $_SESSION["user_grade"]);

	/* get list of cases with recent activity */
	$q = "select case_id,investigator from activities,cases " .
		"where datediff(now(), activities.ts) < $num_days_recent_activity and case_id=cases.id " .
		"order by activities.ts desc";

	$res = $db->query($q);

	/* form a list of caseids from the query result.
	 * filter only cases investigated by team.
	 */
	while ($row = $res->fetch_assoc()) {
		if (in_array($row["investigator"], $team_ids))
			$caseids[] = $row["case_id"];
	}

	/* The query for 'recent' may return duplicate caseids.
	 * Also, the caseids are not limited to $start and $rows.
	 * So, make the caseids unique and restrict the result to
	 * $start, $rows.
	 */
	$caseids = array_unique($caseids);
	$caseids = array_slice($caseids, $start, $rows);

	/* for each case selected, get the details and activities */
	foreach ($caseids as $caseid) {

		/* get case details */
		$q = "select id,case_num,petitioner,respondent,prayer,next_hearing from cases where id=$caseid";
		$res2 = $db->query($q);
		$case = $res2->fetch_assoc();
		if ($case["next_hearing"] == 0) {
			$case["next_hearing"] = "None";
		} else {
			$dt = date("M d, Y", $case["next_hearing"]);
			$case["next_hearing"] = $dt; // Mar 04, 2014
		}
		$res2->close();

		$case["activities"] = array();

		/* get recent activities for the case */
		$q = "select type,doer,object,ts from activities where case_id=${case['id']} order by ts desc limit $num_recent_activities_per_case";
		$res2 = $db->query($q);
		while ($act = $res2->fetch_assoc()) {
			$act["type"] = array_search($act["type"], $activities);
			$act["doer"] = get_name_grade($act["doer"]);
			$act["ts"] = relative_time($act["ts"]);
			$act["details"] = get_activity_details($db, $act["type"], $act["object"]);

			$case["activities"][] = $act;
		}
		$res2->close();

		$cases[] = $case;
	}

	$res->close();
	$db->close();

	$after = gettimeofday(true);

	$ret["cases"] = $cases;
	$ret["latency"] = ($after - $before) * 1000; // in millisec

out:
	print json_encode($ret);
?>
