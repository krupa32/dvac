<?php
	include "./config.php";

	//error_log("autocomplete term:" . $_GET['term']);

	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select id,name,grade,location from users where name like '%${_GET['term']}%' limit 5";
	$res = $db->query($q);
	while ($row = $res->fetch_assoc()) {
		$grade = array_search($row["grade"], $grades);
		$loc = array_search($row["location"], $locations);
		$entry["label"] = "${row['name']}, $grade, $loc";
		$entry["value"] = $row['id'];
		$ret[] = $entry;
	}

	$res->close();
	$db->close();

	//error_log("returning:" . json_encode($ret));
	print json_encode($ret);
?>
