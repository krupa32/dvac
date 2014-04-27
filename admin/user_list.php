<?php
	include "../config/config.php";

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select id,name from users";
	$res = $db->query($q);
	while ($row = $res->fetch_assoc())
		$ret[] = $row;

	$res->close();

	print json_encode($ret);
?>
