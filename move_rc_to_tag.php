<?php
	/* This script moves regularcases data into the cases
	 * table under the 'tag' field. Once this is done, this
	 * script is no longer needed.
	 */
	include "common/config.php";
	
	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$q = "select id,regularcase from cases where regularcase!=0";
	$res = $db->query($q);

	while ($row = $res->fetch_row()) {
		$q = "select case_num from regularcases where id=${row[1]}";
		$res2 = $db->query($q);
		$row2 = $res2->fetch_row();
		//print "updating case $row[0] to '$row2[0]'";
		$q = "update cases set tag='${row2[0]}' where id=${row[0]}";
		$db->query($q);
	}

	$db->close();
	
?>
