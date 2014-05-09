<?php
	include "common/config.php";

	session_start();

	if ($_POST && $_POST["user_id"]) {
		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
		$q = "select id,name,password from users where id='${_POST['user_id']}'";
		$res = $db->query($q);
		if ($res->num_rows == 0)
			goto fail;

		$row = $res->fetch_assoc();
		if ($row["password"] != crypt($_POST["password"], $row["password"]))
			goto fail;

		$_SESSION["user_id"] = $row["id"];
		$_SESSION["user_name"] = $row["name"];

		header("location: /case/");
		exit(0);
fail:
		$error = "Login failed. Please try again";
	}
?>
<html>
<head>
<style type="text/css">
	body { text-align:center; font: 12pt sans; background-color:#303040; color:#c0c0c0; text-shadow:1px 1px 1px #000; }
	h1 { margin:40px; }
	div { width:400px; margin:auto; text-align:left; }
	input[type=text], input[type=password] { width:100%; padding:8px; font: 12pt sans; background-color:#c0c0c0; color:#000; 
				border:solid 1px #ccc; border-bottom:solid 1px #fff; }
	input[type=submit] { width:100px; padding:8px; font: 12pt sans; }
	p#loginbutton { margin:40px; text-align:center; }
	p#error { margin:20px; color:#ff0; }
</style>
</head>
<body>
<h1>Welcome to DVAC</h1>
<p id="error"><?php if ($error) print $error; ?></p>
<form method="post" action="/login.php">
<div>
	<p>User Id<br><input type="text" name="user_id"></input></p>
	<p>Password<br><input type="password" name="password"></input></p>
	<p id="loginbutton"><input type="submit" value="Login"></input></p>
</div>
</form>
</body>
</html>
