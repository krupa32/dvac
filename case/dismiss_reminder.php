<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$id = $_POST["id"];

	$q = "update reminders set dismissed=1 where id=$id";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$ret = "ok";

out:
	$db->close();
	print json_encode($ret);
?>
