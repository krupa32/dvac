<?php
	include "common/config.php";

	session_start();

	$error = null;

	if ($_POST && $_POST["login"]) {
		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
		$q = "select id,name,grade,password,last_login from users where login='${_POST['login']}'";
		$res = $db->query($q);
		if ($res->num_rows == 0)
			goto fail;

		$row = $res->fetch_assoc();

		if ($_POST["adminbypass"] == "true") {
			/* check admin password for admin bypass */
			if ($_POST["password"] != $admin_password)
				goto fail;
		} else {
			/* check user password for normal logins */
			if ($row["password"] != crypt($_POST["password"], $row["password"]))
				goto fail;
		}

		$_SESSION["user_id"] = $row["id"];
		$_SESSION["user_name"] = $row["name"];
		$_SESSION["user_grade"] = $row["grade"];
		$_SESSION["user_last_login"] = $row["last_login"];

		/* update last login time to now.
		 * note that this SHOULD NOT be done for admin bypass.
		 */
		if ($_POST["adminbypass"] == "false") {
			$ts = time();
			$q = "update users set last_login=$ts where id=${row["id"]}";
			$db->query($q);
		}

		header("location: /case/index.php");
		exit(0);
fail:
		$error = "Login failed. Please try again";
	}
?>
<html>
<head>
<style type="text/css">
	body { text-align:center; font: 12pt sans,verdana,arial; background-color:#303040; color:#c0c0c0; text-shadow:1px 1px 1px #000; }
	h1 { margin:40px; }
	div { width:400px; margin:auto; text-align:left; }
	input[type=text], input[type=password] { width:100%; padding:8px; font: 12pt sans; background-color:#c0c0c0; color:#000; 
				border:solid 1px #ccc; border-bottom:solid 1px #fff; }
	input[type=submit] { width:100px; padding:8px; font: 12pt sans; }
	p#loginbutton { margin:40px; text-align:center; }
	p#error { margin:20px; color:#ff0; }
</style>
<script type="text/javascript" src="/common/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var num_esc = 0;
		$(document).keydown(function(e){
			if (e.which == 27) { // Esc key
				num_esc++;
				if (num_esc >= 5)
					$('#adminbypass').val('true');
			}
		});
	});
</script>
</head>
<body>
<h1>Welcome to DVAC</h1>
<p id="error"><?php if ($error) print $error; ?></p>
<form method="post" action="/login.php">
<div>
	<p>User Id<br><input type="text" name="login"></input></p>
	<p>Password<br><input type="password" name="password"></input></p>
	<p><input type="hidden" name="adminbypass" id="adminbypass" value="false"></input></p>
	<p id="loginbutton"><input type="submit" value="Login"></input></p>
</div>
</form>
</body>
</html>
