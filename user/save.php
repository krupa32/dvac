<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /admin/login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "update users set name='${_POST['name']}', grade=${_POST['grade']}, " .
		"reporting_officer_id='${_POST['reporting_officer_id']}', location=${_POST['location']} where id='${_SESSION['user_id']}'";

	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
	
	print json_encode($ret);
?>
