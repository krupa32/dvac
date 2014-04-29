<?php
	include "../common/config.php";

	session_start();
	$id = $_SESSION["user_id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select name,grade,reporting_officer_id,location from users where id='$id'";
	$res = $db->query($q);
	$row = $res->fetch_assoc();
	$ret = $row;
	$res->close();

	$q = "select name from users where id='${ret['reporting_officer_id']}'";
	$res = $db->query($q);
	if ($res && $res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$ret["reporting_officer"] = $row["name"];
	}
	$res->close();

	$db->close();

	print json_encode($ret);
?>
