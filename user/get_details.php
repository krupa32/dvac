<?php
	include "../common/config.php";

	session_start();
	$id = $_SESSION["user_id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select name,grade,reporting_to,location from users where id=$id";
	$res = $db->query($q);
	$row = $res->fetch_assoc();
	$ret = $row;
	$res->close();

	$q = "select name from users where id='${ret['reporting_to']}'";
	$res = $db->query($q);
	if ($res && $res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$grade = array_search($ret["grade"], $grades);
		$ret["reporting_to_name"] = $row["name"] . ", " . $grade;
	}
	$res->close();

	$db->close();

	print json_encode($ret);
?>
