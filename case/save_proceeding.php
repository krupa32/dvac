<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "insert into proceedings values(null, ${_SESSION['user_id']}, ${_POST['case_id']}, null, " . 
		"${_POST['court']}, ${_POST['hall']}, ${_POST['item']}, '${_POST['judge']}', " . 
		"${_POST['counsel']}, ${_POST['disposal']}, '${_POST['comment']}')";

	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$proc_id = $db->insert_id;

	$type = $activities["ADDPROCEEDING"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, ${_POST['case_id']}, $proc_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$ret = "ok";

out:
	print json_encode($ret);
?>
