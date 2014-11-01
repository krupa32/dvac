<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$case_id = $_POST["case_id"];

	$q = "update cases set assigned_to=${_POST['target']} where id=${_POST['case_id']}";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$comment = $db->real_escape_string($_POST["comment"]);
	$q = "insert into assignments values(null, ${_POST['case_id']}, ${_POST['target']}, '$comment')";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$assignment_id = $db->insert_id;

	$type = $activities["ASSIGN"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, ${_POST['case_id']}, $assignment_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	/* send sms if required */
	$assigned = get_name_grade($_POST["target"]);
	$sms = "assigned case to $assigned";
	check_and_send_sms("ASSIGN", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	$db->close();
	print json_encode($ret);
?>
