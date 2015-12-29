<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	$user_id = $_SESSION["user_id"];
	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$today = strtotime(date("M j, Y"));
	$q = "select reminders.id,cases.id as case_id,case_num,status,comment,remind_on from reminders,cases " .
		"where reminders.creator=$user_id and dismissed=0 and case_id=cases.id order by remind_on";

	$res = $db->query($q);
	if (!$res || $res->num_rows == 0)
		goto out;

	while ($row = $res->fetch_assoc()) {
		$row["status"] = array_search($row["status"], $statuses);	
		if ($row["remind_on"] < $today) {
			$row["remind_on"] = absolute_date($row["remind_on"]);
			$row["expired"] = 1;
		} else if ($row["remind_on"] == $today) {
			$row["remind_on"] = "Today";
			$row["expired"] = 1;
		} else {
			$row["remind_on"] = absolute_date($row["remind_on"]);
			$row["expired"] = 0;
		}

		$ret[] = $row;
	}
	$res->close();

out:
	if ($db)
		$db->close();
	print json_encode($ret);
?>
