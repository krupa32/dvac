<?php

	include "/var/www/dvac/common/config.php";
	include "/var/www/dvac/common/utils.php";

	print "Checking for proceedings\n";

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	$ts1 = time() + ($num_days_proceeding_lookahead - 1) * 86400;
	$ts2 = time() + $num_days_proceeding_lookahead * 86400;
	$q = "select id,next_hearing from cases where next_hearing > $ts1 and next_hearing < $ts2";
	$res = $db->query($q);
	if (!$res || $res->num_rows == 0) {
		print "No proceedings on $num_days_proceeding_lookahead'th day\n";
		goto out;
	}

	while ($row = $res->fetch_assoc()) {
		$dt = date("M d, Y", $row["next_hearing"]);
		$sms = "Next hearing on $dt";
		print "Sending sms:${row['id']}: $sms\n";
		check_and_send_sms("PROCEEDINGLOOKAHEAD", null, $row["id"], $sms);
	}

	$res->close();
out:
	if ($db)
		$db->close();
?>
