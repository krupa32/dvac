<?php
	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");
?>
<html>
<head>
	<title>Court Cases and Proceedings</title>

	<link rel="stylesheet" href="/common/jquery-ui.css"></link>
	<link rel="stylesheet/less" href="/common/common.css"></link>
	<link rel="stylesheet/less" href="/user/home.css"></link>

	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/common/less.js"></script>
	<script type="text/javascript" src="/common/utils.js"></script>
	<script type="text/javascript" src="/user/home.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			home.init();
		});
	</script>
</head>
<body>
<div class="header">
	<a class="hilite" href="/user/home.php">MY PROFILE</a>
	<a>WEEKLY REPORTS</a>
	<a href="/case/">COURT CASES</a>
	<a href="/logout.php">LOGOUT</a>
</div>
<div class="page">
<p class="aligncenter" id="status"></p>
<table id="tbl_userdetails">
	<tr><td colspan="2" class="sectiontitle">Update User Information</td></tr>
	<tr><td><p>Name</p></td><td><p><input type="text" id="user_name"></input></p></td></tr>
	<tr><td><p>Title</p></td><td><p><select id="user_grade"></select></p></td></tr>
	<tr><td><p>Reporting Officer</p></td><td><p>
		<input type="text" id="user_rep_officer"></input><br>
		<span class="hint">Enter partial name to show a list</span><br>
		</p></td></tr>
	<tr><td><p>Location</p></td><td><p><select id="user_location"></select></p></td></tr>
	<tr><td>&nbsp;</td><td><p><button class="primary" id="btn_save">Save</button></p></td></tr>

	<tr><td colspan="2" class="sectiontitle"><br><br><br>Change Password</td></tr>
	<tr><td><p>Password</p></td><td><p><input type="password" id="user_password"></input></p></td></tr>
	<tr><td><p>Confirm Password</p></td><td><p><input type="password" id="user_confirm"></input></p></td></tr>
	<tr><td>&nbsp;</td><td><p><button class="primary" id="btn_changepassword">Change Password</button></p></td></tr>
</table>
</div> <!-- page -->
</body>
</html>
