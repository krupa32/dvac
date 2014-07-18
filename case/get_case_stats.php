<?php
	include "../common/config.php";
	include "../common/utils.php";

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

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");
	
	$user_id = $_SESSION["user_id"];

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/* get ids of all officers in current user's team */
	$team_ids = get_team_ids($db, $_SESSION["user_id"], $_SESSION["user_grade"]);

	/* assigned */
	$q = "select count(*) from cases where assigned_to=${_SESSION['user_id']}";
	$ret["assigned"] = get_count($db, $q);

	/* upcoming_hearings */
	$ret["upcoming_hearings"] = 0;
	$today = strtotime(date("M j, Y"));
	$q = "select investigator from cases where next_hearing >= $today";
	$res = $db->query($q);
	while ($res && ($row = $res->fetch_row())) {
		/* filter cases investigated by team */
		if (in_array($row[0], $team_ids))
			$ret["upcoming_hearings"]++;
	}
	if ($res)
		$res->close();

	/* reminders for today */
	$today = strtotime(date("M j, Y"));
	$q = "select count(*) from reminders where creator=$user_id and remind_on=$today";
	$ret["reminders"] = get_count($db, $q);

	/* total reminders */
	$today = strtotime(date("M j, Y"));
	$q = "select count(*) from reminders where creator=$user_id and remind_on >= $today";
	$ret["reminders_total"] = get_count($db, $q);

out:
	$db->close();
	print json_encode($ret);
?>
