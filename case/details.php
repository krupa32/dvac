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
	<link rel="stylesheet/less" href="/case/details.css"></link>
	<link rel="stylesheet/less" href="/case/editcase.css"></link>
	<link rel="stylesheet/less" href="/case/editproceeding.css"></link>

	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/common/less.js"></script>
	<script type="text/javascript" src="/case/details.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			details.init();
		});
	</script>
</head>
<body>
<div class="header">
	<a href="/user/home.php">MY PROFILE</a>
	<a href="">WEEKLY REPORTS</a>
	<a class="hilite" href="/case/home.php">COURT CASES</a>
	<a href="/logout.php">LOGOUT</a>
</div>
<div class="page">
<table id="details">
<tr>
	<td class="box" id="summary">
		<h3>CR0132</h3>
		<p>Created on Mar 04, 2014<br>by Insp. Kumaravel</p>
		<p>Investigated by<br>DSP. Raj Narayan</p>
		<p class="aligncenter"><button id="btn_update">Update</button></p>
	</td>
	<td>
		<p>Filed by<br>Tmt. Kanimozhi</p>
		<p>Respondents<br>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p>
		<p>To disburse the death cum retirement gratuity of my unblemished service within a stipulated period
		as fixed by the court.</p>
	</td>
</tr>
</table>

<div class="sectiontitle">Proceedings History</div>

<table class="list"i id="proceedings">
	<tr class="tableheader">
		<td class="date"><p>Date</p></td>
		<td class="court"><p>Court</p></td>
		<td class="counsel"><p>Counsel Appeared</p></td>
		<td class="remarks"><p>Remarks</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p><a href="">Mar 04, 2014</a></p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Advocate General</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
</table>

<div id="addproceedings" class="aligncenter"><button id="btn_addproceeding">Add Proceeding</button></div>

<div class="dialog" id="dlg_editcase"></div>

<div class="dialog" id="dlg_editproceeding"></div>

</div> <!-- page -->
</body>
</html>
