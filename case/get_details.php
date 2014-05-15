<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select * from cases where id=${_GET['id']}";
	$res = $db->query($q);
	$row = $res->fetch_assoc();
	$ret = $row;
	$res->close();

	$ret["status"] = array_search($ret["status"], $statuses);

	$ret["investigator_id"] = $ret["investigator"];
	$ret["investigator"] = get_name_grade($ret["investigator"]);

	if ($row["assigned_to"] != 0)
		$ret["assigned_to"] = get_name_grade($ret["assigned_to"]);

out:
	print json_encode($ret);
?>
