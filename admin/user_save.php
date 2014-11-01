<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	/* default password is "password".
	 * default reporting officer id is "0".
	 * default location is 1 (chennai)
	 */
	$password = crypt("password");
	$rep_id = 0;
	$location = 1;

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	if ($_POST["action"] == "new")
		$q = "insert into users values(null, '${_POST['login']}', '${_POST['name']}', ${_POST['grade']}, '$password', $rep_id, $location, 0, null)";
	else if ($_POST["action"] == "update")
		$q = "update users set login='${_POST["login"]}', name='${_POST['name']}', grade=${_POST['grade']} where id=${_POST['id']}";
	else
		$q = "update users set password='$password' where id=${_POST['id']}";

	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
out:
	print json_encode($ret);
?>
