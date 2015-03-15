<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$case_id = $_POST["case_id"];
	$dvac_status = $_POST["dvac_status"];
	$direction = $_POST["direction"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "update cases set dvac_status=$dvac_status,direction=$direction where id=$case_id";

	if (!$db->query($q)) {
		$ret = $db->error . "query=" . $q;
		goto out;
	}

	$type = $activities["CHANGEDVACSTATUS"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, $case_id, $dvac_status, null)";
	if (!$db->query($q)) {
		$ret = $db->error . "query=" . $q;
		goto out;
	}

	/* send sms if required */
	$sms = "changed dvac status";
	check_and_send_sms("CHANGEDVACSTATUS", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	print json_encode($ret);
?>
