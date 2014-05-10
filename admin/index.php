<?php
	session_start();
	if (!$_SESSION["user_id"] || $_SESSION["user_id"] != "admin")
		header("location: /admin/login.php");
?>
<html>
<head>
	<link rel="stylesheet" href="/common/jquery-ui.css"></link>
	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/admin/index.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			app.init();
		});
	</script>
</head>
<body>
<h1>DVAC Administration Panel</h1>
<h2 id="listofusers">List of Users <button id="user_add">Add New User</button></h2>
<hr>
<div id="users"></div>
<div id="user_edit" class="dialog">
<table>
	<tr><td>Login Id</td><td><input type="text" id="login"></input></td></tr>
	<tr><td>Title</td><td><select id="grade">
		<option value="10">Inspector</option>
		<option value="20">DSP</option>
		<option value="30">Additional SP</option>
		<option value="40">SP</option>
		<option value="50">Deputy Director</option>
		<option value="60">Joint Director</option>
		<option value="70">Director</option>
		</select></td></tr>
	<tr><td>Name</td><td><input type="text" id="name"></input></td></tr>
	<tr><td>&nbsp;</td><td><button id="save">Save</button><button id="reset_password">Reset Password</button></td></tr>
</table>
</div> <!-- #edit_user -->
</body>
</html>
