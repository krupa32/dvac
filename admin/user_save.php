<?php
	include "../config/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /admin/login.php");

	$password = crypt($_POST["password"]);
	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	if ($_POST["action"] == "new")
		$q = "insert into users values('${_POST['id']}', '${_POST['name']}', ${_POST['level']}, '$password', '${_POST['rep_id']}')";
	else
		$q = "update users set name='${_POST['name']}', level=${_POST['level']}, password='$password', " .
			"reporting_officer_id='${_POST['rep_id']}' where id='${_POST['id']}'";
	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
	
	print json_encode($ret);
?>
