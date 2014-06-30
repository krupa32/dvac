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
	
	function relative_time($timestamp)
	{
		$now = new DateTime(date(DATE_W3C));
		//error_log("ts=$timestamp");
		$dt = new DateTime($timestamp);
		$diff = $dt->diff($now);
	
		if ($diff->d)
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


?>
