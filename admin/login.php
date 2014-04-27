<?php
	include "../config/config.php";

	session_start();
	if ($_POST["password"]) {
		if ($_POST["password"] != $admin_password) {
			$error = "Invalid password";
			goto out;
		}
		$_SESSION["user_id"] = "admin";
		header("location: /admin/");
	}
out:
?>
<html>
<head>
</head>
<body>
<h1>Welcome to DVAC</h1>
<p><?php if ($error) print $error; ?></p>
<form method="post" action="/admin/login.php">
<p>Enter administrator password</p>
<p><input type="password" name="password"></input></p>
<p><input type="submit" value="Login"></input></p>
</form>
</body>
</html>
