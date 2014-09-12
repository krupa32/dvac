<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);
	$q = "select * from cases where id=${_GET['id']}";
	$res = $db->query($q);
	$ret = $res->fetch_assoc();
	$res->close();

	$ret["status"] = array_search($ret["status"], $statuses);

	$ret["investigator_id"] = $ret["investigator"];
	$ret["investigator"] = get_name_grade($ret["investigator"]);
	$ret["rc_case_num"] = get_rc_case_num($ret["regularcase"]);

	if ($ret["assigned_to"] != 0)
		$ret["assigned_to"] = get_name_grade($ret["assigned_to"]);

	if ($ret["next_hearing"] == 0) {
		$ret["next_hearing"] = "None";
	} else {
		$dt = date("M d, Y", $ret["next_hearing"]);
		$ret["next_hearing"] = $dt; // Mar 04, 2014
	}
out:
	print json_encode($ret);
?>
