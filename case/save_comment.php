<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$case_id = $_POST['case_id'];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$comment = $db->real_escape_string($_POST["comment"]);
	$q = "insert into comments values(null, ${_POST['case_id']}, '$comment')";

	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$comment_id = $db->insert_id;

	$type = $activities["ADDCOMMENT"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, ${_POST['case_id']}, $comment_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$activity_id = $db->insert_id;

	/* send sms if required */
	$sms = "added a comment: $comment";
	check_and_send_sms("ADDCOMMENT", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	print json_encode($ret);
?>
