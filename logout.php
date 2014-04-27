<?php
	session_start();
	$_SESSION["user_id"] = null;
	header("location: /login.php");
?>
