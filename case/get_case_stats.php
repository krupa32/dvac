<?php
	function get_count($db, $q)
	{
		$count = 0;

		$res = $db->query($q);
		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_row();
			$count = $row[0];
			$res->close();
		}
		
		return $count;
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
	$from = mktime() - 24*60*60;;
	$to = $from + ($num_days_upcoming_hearings * 24 * 60 * 60);
	$q = "select count(id) from cases where next_hearing >= $from and next_hearing <= $to";
	$ret["hearings"] = get_count($db, $q);

	/* nohearings */
	$q = "select count(id) from cases where next_hearing = 0 and status = ${statuses['PENDING_IN_COURT']}";
	$ret["nohearings"] = get_count($db, $q);

	/* get cases stats by category */
	$ret["categories"] = array();
	foreach ($categories as $name => $value) {
		$q = "select count(*) from cases where category=$value and " .
			"status != ${statuses['CLOSED']}";
		/* key is derived by removing space,/,. and converting to lower */
		$key = strtolower(strtr($name, array(" " => "", "/" => "", "." => "")));
		$entry["name"] = $name;
		$entry["key"] = $key;
		$entry["count"] = get_count($db, $q);
		$ret["categories"][] = $entry;
	}

out:
	$db->close();
	print json_encode($ret);
?>
