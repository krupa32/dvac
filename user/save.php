<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "update users set name='${_POST['name']}', grade=${_POST['grade']}, " .
		"reporting_to=${_POST['reporting_to']}, location=${_POST['location']} where id=${_SESSION['user_id']}";

	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
	
	print json_encode($ret);
?>
