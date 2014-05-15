<?php
	include "../common/config.php";
	include "../common/utils.php";

	function get_proceeding_details($db, $proc_id)
	{
		global $courts, $counsels, $disposals;

		$q = "select court,hall,judge,counsel,disposal,comment " . 
			"from proceedings where proceedings.id=$proc_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		$row["court"] = array_search($row["court"], $courts);
		$row["counsel"] = array_search($row["counsel"], $counsels);
		$row["disposal"] = array_search($row["disposal"], $disposals);
		return $row;
	}

	function get_comment_details($db, $comment_id)
	{
		$q = "select comment from comments where comments.id=$comment_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		return $row;
	}

	function get_assignment_details($db, $assn_id)
	{
		$q = "select target,comment from assignments where assignments.id=$assn_id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$res->close();
		$row["target"] = get_name_grade($row["target"]);
		return $row;
	}



	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select * from activities where case_id=${_GET['id']} order by ts";
	$res = $db->query($q);
	while ($row = $res->fetch_assoc()) {
		$entry["type"] = array_search($row["type"], $activities);
		$entry["doer"] = get_name_grade($row["doer"]);
		$entry["case_id"] = $row["case_id"];
		$dt = new DateTime($row["ts"]);
		$entry["ts"] = $dt->format("M d, Y"); // Mar 04, 2014

		switch ($row["type"]) {
		case $activities["ADDPROCEEDING"]:
			$entry["details"] = get_proceeding_details($db, $row["object"]);
			break;
		case $activities["ADDCOMMENT"]:
			$entry["details"] = get_comment_details($db, $row["object"]);
			break;
		case $activities["ASSIGN"]:
			$entry["details"] = get_assignment_details($db, $row["object"]);
			break;
		}

		$ret[] = $entry;
	}

	$res->close();
	$db->close();

out:
	print json_encode($ret);
?>
