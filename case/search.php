<?php
	include "../common/config.php";
	include "../common/utils.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$query = $_GET["query"];
	$data = $_GET["data"];
	$ret = array();

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	switch ($query) {
	case "case_num":
	case "petitioner":
	case "respondent":
		$q = "select id,case_num,status,investigator,assigned_to,petitioner,respondent,prayer from cases where $query like '%$data%'";
		break;
	case "investigator":
	case "assigned_to":
		$q = "select id,case_num,status,investigator,assigned_to,petitioner,respondent,prayer from cases where $query=$data";
		break;
	case "open":
		$q = "select id,case_num,status,investigator,assigned_to,petitioner,respondent,prayer from cases where status=${statuses['OPEN']}";
		break;
	case"my":
		$q = "select id,case_num,status,investigator,assigned_to,petitioner,respondent,prayer from cases " . 
			"where status=${statuses['OPEN']} and assigned_to=${_SESSION['user_id']}";
		break;
	}

	$res = $db->query($q);
	while ($row = $res->fetch_assoc()) {
		$row["status"] = array_search($row["status"], $statuses);
		$row["investigator"] = get_name_grade($row["investigator"]);
		$row["assigned_to"] = get_name_grade($row["assigned_to"]);
		$ret[] = $row;
	}

	$res->close();
	$db->close();

out:
	print json_encode($ret);
?>
