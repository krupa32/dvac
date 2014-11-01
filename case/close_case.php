<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$case_id = $_POST["case_id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "update cases set status=${statuses['CLOSED']} where id=$case_id";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$type = $activities["CLOSE"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, $case_id, $case_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	/* send sms if required */
	$sms = "closed case";
	check_and_send_sms("CLOSE", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	$db->close();
	print json_encode($ret);
?>
