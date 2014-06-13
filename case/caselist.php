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

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/*
	 * select list of cases based on the type 
	 */
	switch ($_GET["type"]) {
	case "recent":
		$q = "select case_id from activities where datediff(now(), ts) < $num_days_recent_activity order by ts desc";
		break;
	case "search":
		$field = $_GET["field"];
		$data = $_GET["data"];
		$q = "select id from cases where $field like '%$data%' limit $start,$rows";
		if ($field == "assigned_to" || $field == "investigator") /* special cases where data contains id */
			$q = "select id from cases where $field=$data limit $start,$rows";
		if ($field == "location")
			$q = "select cases.id from cases,users where cases.investigator=users.id and users.location=$data limit $start,$rows";
		break;
	case "my":
		$q = "select id from cases where assigned_to=${_SESSION['user_id']} limit $start,$rows";
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
	case "upcoming_hearings":
		$from = mktime() - 24*60*60;
		//$to = $from + ($num_days_upcoming_hearings * 24 * 60 * 60);
		//$q = "select id from cases where next_hearing >= $from and next_hearing <= $to order by next_hearing limit $start,$rows";
		$q = "select id from cases where next_hearing >= $from order by next_hearing limit $start,$rows";
		break;
	case "no_hearings":
		$q = "select id from cases where next_hearing = 0 and status = ${statuses['PENDING_IN_COURT']} limit $start,$rows";
		break;
	}

	$res = $db->query($q);

	/* form a list of caseids from the query result */
	while ($row = $res->fetch_row())
		$caseids[] = $row[0];

	/* The query for 'recent' type will return duplicate caseids.
	 * Also, the caseids are not limited to $start and $rows.
	 * So, make the caseids unique and restrict the result to
	 * $start, $rows.
	 */
	if ($_GET["type"] == "recent") {
		$caseids = array_unique($caseids);
		$caseids = array_slice($caseids, $start, $rows);
	}

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

		$ret[] = $case;
	}

	$res->close();
	$db->close();

out:
	print json_encode($ret);
?>
