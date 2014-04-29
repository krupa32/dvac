<?php
	include "../common/config.php";

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select * from users where id='${_GET['id']}'";
	$res = $db->query($q);
	$row = $res->fetch_assoc();
	$ret = $row;

	$res->close();

	print json_encode($ret);
?>
