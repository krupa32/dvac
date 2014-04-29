<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /admin/login.php");

	/* default password is "password".
	 * default reporting officer id is "0".
	 * default location is 1 (chennai)
	 */
	$password = crypt("password");
	$rep_id = "0";
	$location = 1;

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	if ($_POST["action"] == "new")
		$q = "insert into users values('${_POST['id']}', '${_POST['name']}', ${_POST['grade']}, '$password', '$rep_id', $location)";
	else if ($_POST["action"] == "update")
		$q = "update users set name='${_POST['name']}', grade=${_POST['grade']} where id='${_POST['id']}'";
	else
		$q = "update users set password='$password' where id='${_POST['id']}'";

	if (!$db->query($q))
		$ret = $db->error;
	else
		$ret = "ok";
	
	print json_encode($ret);
?>
