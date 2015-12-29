<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	$user_id = $_SESSION["user_id"];
	$case_id = $_POST["case_id"];
	$on = strtotime($_POST["on"]);
	$comment = $_POST["comment"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$comment = $db->real_escape_string($_POST["comment"]);

	$q = "insert into reminders values(null, $user_id, $case_id, $on, 0, '$comment')";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$ret = "ok";

out:
	if ($db)
		$db->close();
	print json_encode($ret);
?>
