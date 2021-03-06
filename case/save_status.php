<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$case_id = $_POST["case_id"];
	$status = $_POST["status"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "update cases set status=$status where id=$case_id";

	if (!$db->query($q)) {
		$ret = $db->error . "query=" . $q;
		goto out;
	}

	$type = $activities["CHANGESTATUS"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, $case_id, $status, null)";
	if (!$db->query($q)) {
		$ret = $db->error . "query=" . $q;
		goto out;
	}

	/* send sms if required */
	$sms = "changed case status";
	check_and_send_sms("CHANGESTATUS", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	print json_encode($ret);
?>
