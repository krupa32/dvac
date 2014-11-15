<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	$id = $_POST["id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/* ensure no other user is reporting to this user */
	$q = "select id from users where reporting_to=$id";
	$res = $db->query($q);
	if ($res && $res->num_rows > 0) {
		$ret = "Unable to remove. Some users are still reporting to this user.";
		goto out;
	}

	/* set reporting_to of this user to 0 */
	$q = "update users set reporting_to=0 where id=${_POST['id']}";
	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
out:
	if ($db)
		$db->close();
	print json_encode($ret);
?>
