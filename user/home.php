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
	<a href="">WEEKLY REPORTS</a>
	<a href="/case/home.php">COURT CASES</a>
	<a href="/logout.php">LOGOUT</a>
</div>
<div class="page">
<p class="aligncenter" id="status"></p>
<table id="tbl_userdetails">
	<tr><td colspan="2" class="sectiontitle">Update User Information</td></tr>
	<tr><td><p>Name</p></td><td><p><input type="text" id="user_name"></input></p></td></tr>
	<tr><td><p>Title</p></td><td><p><select id="user_grade">
		<option value="10">Inspector</option>
		<option value="20">DSP</option>
		<option value="30">Additional SP</option>
		<option value="40">SP</option>
		<option value="50">Deputy Director</option>
		<option value="60">Joint Director</option>
		<option value="70">Director</option>
		</select></p></td></tr>
	<tr><td><p>Reporting Officer</p></td><td><p><input type="text" id="user_rep_officer"></input></p></td></tr>
	<tr><td><p>Location</p></td><td><p><select id="user_location">
		<option value="1">Chennai</option>
		<option value="70">Madurai</option>
		</select></p></td></tr>
	<tr><td>&nbsp;</td><td><p><button id="btn_save">Save</button></p></td></tr>

	<tr><td colspan="2" class="sectiontitle">Change Password</td></tr>
	<tr><td><p>Password</p></td><td><p><input type="password" id="user_password"></input></p></td></tr>
	<tr><td><p>Confirm Password</p></td><td><p><input type="password" id="user_confirm"></input></p></td></tr>
	<tr><td>&nbsp;</td><td><p><button id="btn_changepassword">Change Password</button></p></td></tr>
</table>
</div> <!-- page -->
</body>
</html>
