<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$hearing = strtotime($_POST["hearing"]);
	if (!$hearing)
		$hearing = 0;
	
	if ($_POST["court"] == "")
		$_POST["court"] = 0;
	if ($_POST["hall"] == "")
		$_POST["hall"] = 0;

	$case_id = $_POST["case_id"];
	$judge = $db->real_escape_string($_POST["judge"]);
	$comment = $db->real_escape_string($_POST["comment"]);
	
	$q = "insert into proceedings values(null, ${_SESSION['user_id']}, ${_POST['case_id']}, null, " . 
		"${_POST['court']}, ${_POST['hall']}, ${_POST['item']}, '$judge', " . 
		"${_POST['counsel']}, ${_POST['disposal']}, $hearing, '$comment')";

	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$proc_id = $db->insert_id;

	/* update next hearing date, if applicable */
	$q = "update cases set next_hearing=$hearing where id=${_POST['case_id']}";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$type = $activities["ADDPROCEEDING"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, ${_POST['case_id']}, $proc_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	/* send sms if required */
	$sms = "added a proceeding: $comment";
	check_and_send_sms("ADDPROCEEDING", $_SESSION["user_id"], $case_id, $sms);

	$ret = "ok";

out:
	print json_encode($ret);
?>
