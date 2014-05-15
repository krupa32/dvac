<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select count(*) from cases where status=${statuses['OPEN']}";
	$res = $db->query($q);
	$row = $res->fetch_row();
	$ret["open"] = $row[0];
	$res->close();

	$q = "select count(*) from cases where status=${statuses['OPEN']} and assigned_to=${_SESSION['user_id']}";
	$res = $db->query($q);
	$row = $res->fetch_row();
	$ret["my"] = $row[0];
	$res->close();

out:
	$db->close();
	print json_encode($ret);
?>
