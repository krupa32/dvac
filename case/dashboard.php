<?php
	include "../common/config.php";
	include "../common/utils.php";


	function init_global(&$global)
	{
		global $statuses;
		$global["TOTAL"] = 0;
		foreach ($statuses as $key => $val)
			$global[$key] = 0;
	}
	function update_global_with_status(&$global, $status)
	{
		global $statuses;
		$key = array_search($status, $statuses);
		$global[$key]++;
		$global["TOTAL"]++;
	}
	function update_global_with_dvac_status(&$global, $dvac_status)
	{
		global $dvac_statuses;
		$key = array_search($dvac_status, $dvac_statuses);
		$global[$key]++;
		$global["TOTAL"]++;
	}
	function init_range(&$range)
	{
		global $statuses;
		$range["TOTAL"] = 0;
		foreach ($statuses as $key => $val)
			$range[$key] = 0;
	}
	function update_range_with_status(&$range, $status)
	{
		global $statuses;
		$key = array_search($status, $statuses);
		$range[$key]++;
		$range["TOTAL"]++;
	}
	function update_range_with_dvac_status(&$range, $dvac_status)
	{
		global $dvac_statuses;
		$key = array_search($dvac_status, $dvac_statuses);
		$range[$key]++;
		$range["TOTAL"]++;
	}
	function init_category(&$category)
	{
		global $categories;
		foreach ($categories as $key => $val)
			$category[$key] = 0;
	}
	function update_category(&$category, $cat)
	{
		global $categories;
		$key = array_search($cat, $categories);
		$category[$key]++;
	}
	function init_location(&$location)
	{
		global $locations;
		foreach ($locations as $key => $val)
			$location[$key] = 0;
	}
	function update_location(&$location, $loc)
	{
		global $locations;
		$key = array_search($loc, $locations);
		$location[$key]++;
	}
	function init_hearing(&$hearing)
	{
		$hearing["upcoming"] = 0;
		$hearing["notspecified"] = 0;
		$hearing["notupdated"] = 0;
	}
	function update_hearing(&$hearing, $status, $next_hearing)
	{
		global $statuses;

		$today = strtotime(date("M j, Y"));

		if ($next_hearing == 0)
			$hearing["notspecified"]++;
		else if ($next_hearing >= $today)
			$hearing["upcoming"]++;
		else
			$hearing["notupdated"]++;
	}
	function add_to_team($db, &$team, $id, $name, $grade)
	{
		global $grades;

		$team["id"] = $id;
		$team["name"] = $name . ", " . array_search($grade, $grades);
		$team["count"] = 0;
		$team["team"] = null;

		if ($grade == $grades["Inspector"])
			return;

		$team["team"] = array();

		/* get all children/reporting officers of 'id' */
		$q = "select id,name,grade from users where reporting_to=$id";
		$res = $db->query($q);
		if (!$res || $res->num_rows == 0) {
			$res->close();
			return;
		}

		while ($row = $res->fetch_assoc()) {
			$subteam = null;
			add_to_team($db, $subteam, $row["id"], $row["name"], $row["grade"]);
			$team["team"][] = $subteam;
		}

		$res->close();

	}
	function init_team($db, &$team)
	{
		global $_SESSION;

		$id = $_SESSION["user_id"];
		$name = $_SESSION["user_name"];
		$grade = $_SESSION["user_grade"];

		$team = array();
		$subteam = null;
		add_to_team($db, $subteam, $id, $name, $grade);
		$team[] = $subteam;
	}

	/* Does a simple dfs. Very unoptomized.
	 * Best would be a bfs, but since I dont know how to
	 * queue/dequeue pointers in php, I am stuck with this.
	 * Note that php supports pass by reference, and hence
	 * can only do dfs for now.
	 */
	function update_team(&$team, $id)
	{
		/* check all nodes in current team */
		for ($i = 0; $i < count($team); $i++) {

			if ($team[$i]["id"] == $id) {
				$team[$i]["count"]++;
				return true;
			}

			if ($team[$i]["team"] == null)
				continue;

			if (update_team($team[$i]["team"], $id))
				return true;
		}

	}


	session_start();
	if (!$_SESSION["user_id"]) {
		$ret = "Not logged in";
		goto out;
	}

	$caps = get_capabilities($_SESSION["user_id"]);

	$ret = array(
		"global" 	=> array(),
		"range" 	=> array(),
		"category" 	=> array(),
		"location" 	=> array(),
		"hearing" 	=> array(),
		"team" 		=> array()
		);

	$before = gettimeofday(true);

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	init_global($ret["global"]);
	init_range($ret["range"]);
	init_category($ret["category"]);
	init_location($ret["location"]);
	init_hearing($ret["hearing"]);
	init_team($db, $ret["team"]);

	/* get ids of all officers in current user's team */
	$team_ids = get_team_ids($db, $_SESSION["user_id"], $_SESSION["user_grade"]);

	/* select all cases */
	$q = "select cases.id,category,status,dvac_status,assigned_to,investigator,next_hearing,name,location " .
		"from cases,users where investigator=users.id";
	$res = $db->query($q);

	while ($row = $res->fetch_assoc()) {
		update_global_with_status($ret["global"], $row["status"]);
		update_global_with_dvac_status($ret["global"], $row["dvac_status"]);

		if ($caps & $CAP_NOTEAMFILTER) {
			/* All cases are visible to this user.
			 * no team filter required in these cases.
			 */
			goto post_filter;
		}

		/* if case doesnt belong to team, continue */
		if (!in_array($row["investigator"], $team_ids))
			continue;
		
post_filter:
		update_range_with_status($ret["range"], $row["status"]);
		update_range_with_dvac_status($ret["range"], $row["dvac_status"]);

		/* the following statistics are only for open cases. so... */
		if ($row["status"] == $statuses["CLOSED"])
			continue;

		update_category($ret["category"], $row["category"]);
		update_location($ret["location"], $row["location"]);
		update_hearing($ret["hearing"], $row["status"], $row["next_hearing"]);
		update_team($ret["team"], $row["investigator"]);
	}

	$res->close();
	$db->close();

	$after = gettimeofday(true);
	$ret["latency"] = ($after - $before) * 1000; // in millisec

out:
	print(json_encode($ret));
