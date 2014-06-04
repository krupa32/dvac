<?php
	function get_count($db, $q)
	{
		$res = $db->query($q);
		$row = $res->fetch_row();
		$res->close();
		return $row[0];
	}

	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/* my */
	$q = "select count(*) from cases where assigned_to=${_SESSION['user_id']}";
	$ret["my"] = get_count($db, $q);

	/* pending_court */
	$q = "select count(*) from cases where status=${statuses['PENDING_IN_COURT']}";
	$ret["pending_court"] = get_count($db, $q);

	/* pending_dvac */
	$q = "select count(*) from cases where status=${statuses['PENDING_WITH_DVAC']}";
	$ret["pending_dvac"] = get_count($db, $q);

	/* hearings */
	$from = mktime();
	$to = $from + ($num_days_upcoming_hearings * 24 * 60 * 60);
	$q = "select count(id) from cases where next_hearing >= $from and next_hearing <= $to";
	$ret["hearings"] = get_count($db, $q);

	/* Crl.OP */
	$q = "select count(*) from cases where category=${categories['Crl.OP']} and " .
		"status != ${statuses['CLOSED']}";
	$ret["crlop"] = get_count($db, $q);

	/* WP */
	$q = "select count(*) from cases where category=${categories['WP']} and " .
		"status != ${statuses['CLOSED']}";
	$ret["wp"] = get_count($db, $q);

	/* WA */
	$q = "select count(*) from cases where category=${categories['WA']} and " .
		"status != ${statuses['CLOSED']}";
	$ret["wa"] = get_count($db, $q);

	/* RC */
	$q = "select count(*) from cases where category=${categories['RC']} and " .
		"status != ${statuses['CLOSED']}";
	$ret["rc"] = get_count($db, $q);

	/* CA */
	$q = "select count(*) from cases where category=${categories['CA']} and " .
		"status != ${statuses['CLOSED']}";
	$ret["ca"] = get_count($db, $q);

out:
	$db->close();
	print json_encode($ret);
?>
