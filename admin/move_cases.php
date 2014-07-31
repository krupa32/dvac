<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	$from = $_POST["from"];
	$to = $_POST["to"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "update cases set investigator=$to where investigator=$from";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$q = "update cases set assigned_to=$to where assigned_to=$from";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$ret = "ok";

out:
	$db->close();
	print json_encode($ret);
?>
