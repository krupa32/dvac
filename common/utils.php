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
			$ret = $diff->format("%h hours ago");
		else if ($diff->i)
			$ret = $diff->format("%i minutes ago");
		else
			$ret = "Just now";
	
		return $ret;
	}
	
?>
