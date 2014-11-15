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
	<script type="text/javascript" src="/common/utils.js"></script>
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
	<tr><td>Title</td><td><select id="grade"></select></td></tr>
	<tr><td>Name</td><td><input type="text" id="name"></input></td></tr>
	<tr><td>&nbsp;</td><td><button id="save">Save</button><button id="reset_password">Reset Password</button></td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td><td>Move all cases investigated by/assigned to this user to:</td></tr>
	<tr><td>&nbsp;</td><td><input type="text" id="to"></input></td></tr>
	<tr><td>&nbsp;</td><td><button id="move_cases">Move Cases</button></td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td><td>Remove this user from the organization tree.
		Before removing, please ensure that no other user is reporting to this user.<br>
		[Note: The user will still remain in the database, but will only be removed from the tree]</td></tr>
	<tr><td>&nbsp;</td><td><button id="remove_user">Remove User</button></td></tr>

</table>
</div> <!-- #edit_user -->
</body>
</html>
