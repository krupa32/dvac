<?php
	include "./config.php";

	//error_log("autocomplete term:" . $_GET['term']);

	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select id,case_num from regularcases where case_num like '%${_GET['term']}%' limit 5";
	$res = $db->query($q);
	while ($row = $res->fetch_assoc()) {
		$entry["label"] = $row['case_num'];
		$entry["value"] = $row['id'];
		$ret[] = $entry;
	}

	$res->close();
	$db->close();

	//error_log("returning:" . json_encode($ret));
	print json_encode($ret);
?>
