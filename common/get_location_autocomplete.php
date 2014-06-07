<?php
	include "./config.php";

	//error_log("autocomplete term:" . $_GET['term']);

	$ret = array();

	$term = $_GET["term"];

	foreach ($locations as $name => $value) {
		if (stristr($name, $term)) {
			$entry["label"] = $name;
			$entry["value"] = $value;
			$ret[] = $entry;
		}
	}

	//error_log("returning:" . json_encode($ret));
	print json_encode($ret);
?>
