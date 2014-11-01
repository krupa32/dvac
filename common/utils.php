<?php
	function get_name_grade($id)
	{
		global $db_host, $db_user, $db_password, $db_name, $grades;

		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
		$q = "select name,grade from users where id=$id";
		$res = $db->query($q);
		$row = $res->fetch_assoc();
		$ret = $row["name"] . ", " . array_search($row["grade"], $grades);

		$res->close();
		$db->close();
		return $ret;
	}

	function get_rc_case_num($id)
	{
		global $db_host, $db_user, $db_password, $db_name;

		$ret = "";

		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
		$q = "select case_num from regularcases where id=$id";
		$res = $db->query($q);
		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_assoc();
			$ret = $row["case_num"];
			$res->close();
		}

		$db->close();
		return $ret;
	}

	function get_case_details($id)
	{
		global $db_host, $db_user, $db_password, $db_name;

		$ret = null;

		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
		$q = "select case_num,status,investigator,assigned_to from cases where id=$id";
		$res = $db->query($q);
		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_assoc();
			$ret = $row;
			$res->close();
		}

		$db->close();
		return $ret;
	}


	function relative_time($timestamp)
	{
		$now = new DateTime(date(DATE_W3C));
		//error_log("ts=$timestamp");
		$dt = new DateTime($timestamp);
		$diff = $dt->diff($now);
	
		if ($diff->m)
			$ret = $diff->format("%m months ago");
		else if ($diff->d)
			$ret = $diff->format("%d days ago");
		else if ($diff->h)
			$ret = $diff->format("%h hrs ago");
		else if ($diff->i)
			$ret = $diff->format("%i min ago");
		else
			$ret = "Just now";
	
		return $ret;
	}
	
	function absolute_date($timestamp)
	{	
		return date("M d, Y", $timestamp);
	}

	/* Get a list of user ids belonging to the team of given user.
	 * id	: id of user whose team is requested
	 * grade: grade of user whose team is requested
	 * Return: array of user ids belonging to the team.
	 */
	function get_team_ids($db, $id, $grade)
	{
		$team = array();
		$team[] = $id;

		if ($grade == 10) // inspector, no team, single
			goto out1;

		$q = "select id,grade from users where reporting_to=$id";
		$res = $db->query($q);
		if (!$res)
			goto out1;
		if ($res->num_rows == 0)
			goto out2;

		while ($row = $res->fetch_assoc()) {
			$subteam = get_team_ids($db, $row["id"], $row["grade"]);
			$team = array_merge($team, $subteam);
		}

out2:
		$res->close();
out1:
		return $team;
	}

	function get_ancestor_ids($db, $id)
	{
		$ancestors = array();

		while ($id != 0) {
			$q = "select reporting_to from users where id=$id";
			$res = $db->query($q);
			$row = $res->fetch_row();
			$parent = $row[0];
			if ($parent != 0)
				$ancestors[] = $parent;

			$id = $parent;
		}

		return $ancestors;
	}

	function queue_sms($id, $sms)
	{
		global $db_host, $db_user, $db_password, $db_name, $grades;

		$db = new mysqli($db_host, $db_user, $db_password, $db_name);

		$q = "select phone from users where id=$id";
		$res = $db->query($q);
		$row = $res->fetch_row();
		$phone = $row[0];
		if ($phone == null || $phone == "")
			return;

		$q = "insert into smsqueue values(null, '$phone', '$sms')";
		if (!$db->query($q))
			error_log("Error inserting into smsqueue: $sms");

		$db->close();
	}

	function get_sms_to_list($activity, $case_id)
	{
		global $sms_list, $SMS_NONE, $SMS_SELF_PARENT, $SMS_ANCESTORS;
		global $db_host, $db_user, $db_password, $db_name, $statuses;

		$db = new mysqli($db_host, $db_user, $db_password, $db_name);

		$to = array();
		$list = $sms_list[$activity];

		if ($list == $SMS_NONE)
			goto out;

		/* get case details */
		$details = get_case_details($case_id);
		$investigator = $details["investigator"];
		$assigned_to = $details["assigned_to"];
		$status = $details["status"];

		/* get ancestors */
		$investigator_ancestors = get_ancestor_ids($db, $investigator);
		if ($investigator != $assigned_to)
			$assigned_to_ancestors =  get_ancestor_ids($db, $assigned_to);
		else
			$assigned_to_ancestors = $investigator_ancestors;

		/* for cases pending with dvac, sms will be sent to all
		 * ancestors. so override whatever was configured.
		 */
		if ($status == $statuses["PENDING_WITH_DVAC"])
			$list = $SMS_ANCESTORS;

		/* sms WILL be sent to investigator, assigned_to and their parents */
		$to[] = $investigator;
		$to[] = $assigned_to;

		if ($list == $SMS_SELF_PARENT) {
			$to[] = $investigator_ancestors[0];
			$to[] = $assigned_to_ancestors[0];
		} else if ($list == $SMS_ANCESTORS) {
			$to = array_merge($to, $investigator_ancestors);
			$to = array_merge($to, $assigned_to_ancestors);
		}

out:
		$db->close();
		$to = array_unique($to);
		return $to;
	}

	function check_and_send_sms($activity, $doer_id, $case_id, $sms)
	{
		global $sms_list, $SMS_NONE, $SMS_SELF_PARENT, $SMS_ANCESTORS;

		if ($sms_list[$activity] == $SMS_NONE)
	       		return;

		$details = get_case_details($case_id);
		$case_num = $details["case_num"];
		$status = $details["status"];

		$doer = get_name_grade($_SESSION['user_id']);
		$sms = "$case_num: $doer $sms";
		$to_list = get_sms_to_list($activity, $case_id);
		foreach($to_list as $to_id) {
			//error_log("queueing to $to_id: $sms");
			queue_sms($to_id, $sms);
		}

		/* execute the smssender.
		 * note that if it was already running, it will detect
		 * and peacefully exit.
		 */
		system("../sms/smssender > /tmp/sender.log 2>&1 &");
	}


?>
