<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	$id = $_SESSION["user_id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select name,grade,reporting_to,location,phone from users where id=$id";
	$res = $db->query($q);
	$row = $res->fetch_assoc();
	$ret = $row;
	$res->close();

	$ret["reporting_to_name"] = get_name_grade($ret["reporting_to"]);

	$db->close();

	print json_encode($ret);
?>
