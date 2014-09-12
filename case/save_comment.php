<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

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

	$ret = "ok";

out:
	print json_encode($ret);
?>
