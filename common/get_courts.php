<?php
	include "./config.php";

	$ret = array();

	foreach ($courts as $name => $value) {
		$row["name"] = $name;
		$row["value"] = $value;
		$ret[] = $row;
	}

	print json_encode($ret);
?>