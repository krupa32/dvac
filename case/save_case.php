<?php
	include "../common/config.php";
	include "../common/utils.php";

	/* if a regular case num is given, try to get its id.
	 * if it doesnt exist in db, add it and get its id.
	 */
	function check_and_get_rc_id($db, $rc_case_num)
	{
		$rc_id = 0;

		$q = "select id from regularcases where case_num='$rc_case_num'";
		$res = $db->query($q);
		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_assoc();
			$rc_id = $row["id"];
		} else {
			$q = "insert into regularcases values(null, '$rc_case_num')";
			if ($db->query($q))
				$rc_id = $db->insert_id;
		}

		return $rc_id;
	}

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$hearing = strtotime($_POST["next_hearing"]);
	if (!$hearing)
		$hearing = 0;

	/* default values for new case */
	$status = $statuses["PENDING_IN_COURT"];
	$assigned_to = $_POST["investigator"];

	$rc_id = check_and_get_rc_id($db, $_POST["rc_case_num"]);

	$petitioner = $db->real_escape_string($_POST["petitioner"]);
	$respondent = $db->real_escape_string($_POST["respondent"]);
	$prayer = $db->real_escape_string($_POST["prayer"]);

	if ($_POST["id"])
		$q = "update cases set case_num='${_POST['case_num']}', category=${_POST['category']}, investigator=${_POST['investigator']}, " .
			"petitioner='$petitioner', respondent='$respondent', prayer='$prayer', " .
			"next_hearing=$hearing, regularcase=$rc_id " .
			"where id=${_POST['id']}";
	else
		$q = "insert into cases values(null, '${_POST['case_num']}', ${_POST['category']}, ${_SESSION['user_id']}, null, " .
			"$status, $assigned_to, ${_POST['investigator']}, " . 
			"'$petitioner', '$respondent', '$prayer', $hearing, $rc_id)";

	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	if ($_POST["id"]) {
		$case_id = intval($_POST["id"]);
		$type = $activities["UPDATECASE"];
		$activity = "UPDATECASE";
		$sms = "updated case details";
	} else {
		$case_id = $db->insert_id;
		$type = $activities["ADDCASE"];
		$activity = "ADDCASE";
		$sms = "added new case";
	}

	$q = "insert into activities values($type, ${_SESSION['user_id']}, $case_id, $case_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	/* send sms if required */
	check_and_send_sms($activity, $_SESSION["user_id"], $case_id, $sms);

	$ret = $case_id;

out:
	$db->close();
	print json_encode($ret);
?>
