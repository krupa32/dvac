<?php

	//include "/var/www/dvac/common/config.php";
	//include "/var/www/dvac/common/utils.php";
	include "../common/config.php";
	include "../common/utils.php";

	print "Checking for reminders\n";

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$ts1 = time() - 86400;
	$ts2 = time();
	$q = "select * from reminders where remind_on > $ts1 and remind_on < $ts2";
	$res = $db->query($q);
	if (!$res || $res->num_rows == 0) {
		print "No reminders for today\n";
		goto out;
	}

	while ($row = $res->fetch_assoc()) {
		$sms = "REMINDER:${row['comment']}";
		print "Sending sms:${row['creator']}: $sms\n";
		if ($sms_enabled) {
			queue_sms($row["creator"], $sms);
		}
	}

	$res->close();
out:
	if ($db)
		$db->close();
?>
