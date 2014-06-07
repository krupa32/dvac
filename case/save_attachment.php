<?php
	include "../common/config.php";

	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");

	$db = new mysqli($db_host, $db_user, $db_password, $db_name);

	/* check if uploads path exists */
	$upload_path = $_SERVER["DOCUMENT_ROOT"] . $upload_dir;
	if (!is_dir($upload_path)) {
		$ret = "$upload_path directory not found";
		goto out;
	}

	if (!$_FILES["attachment"] || $_FILES["attachment"]["error"] != UPLOAD_ERR_OK) {
		$ret = "Error occured while uploading attachment:" . $_FILES["attachment"]["error"] ;
		goto out;
	}

	/* add an attachment record in db */
	$name = $_FILES["attachment"]["name"];
	$q = "insert into attachments values(null, '$name')";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}

	$attachment_id = $db->insert_id;

	/* add an attachment activity in the db */
	$type = $activities["ATTACH"];
	$q = "insert into activities values($type, ${_SESSION['user_id']}, ${_POST['case_id']}, $attachment_id, null)";
	if (!$db->query($q)) {
		$ret = $db->error;
		goto out;
	}


	/* move attachment tmp file to /uploads */
	$src = $_FILES["attachment"]["tmp_name"];
	$ext = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
	$dst = "$upload_path/$attachment_id.$ext"; 
	if (!move_uploaded_file($src, $dst)) {
		$ret = "Error moving uploaded file";
		goto out;
	}


	$ret = "ok";

out:
	$db->close();
	print json_encode($ret);
?>
