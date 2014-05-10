<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	if ($_POST["id"])
		$q = "update cases set case_num='${_POST['case_num']}', investigator=${_POST['investigator']}, " .
			"petitioner='${_POST['petitioner']}', respondent='${_POST['respondent']}', prayer='${_POST['prayer']}' " .
			"where id=${_POST['id']}";
	else
		$q = "insert into cases values(null, '${_POST['case_num']}', ${_SESSION['user_id']}, null, " .
			"${_POST['investigator']}, '${_POST['petitioner']}', '${_POST['respondent']}', '${_POST['prayer']}')";

	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$case_id = $db->insert_id;

	$type = $activities["ADDCASE"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, $case_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$ret = $case_id;

out:
	print json_encode($ret);
?>
