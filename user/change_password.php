<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /admin/login.php");

	$password = crypt($_POST["password"]);

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "update users set password='$password' where id='${_SESSION['user_id']}'";

	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
	
	print json_encode($ret);
?>
