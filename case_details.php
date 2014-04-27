<?php
	session_start();
	if (!$_SESSION["user_id"])
		header("location: /login.php");
?>
<html>
<head>
	<title>Court Cases and Proceedings</title>

	<link rel="stylesheet" href="/css/jquery-ui.css"></link>
	<link rel="stylesheet/less" href="/css/common.css"></link>
	<link rel="stylesheet/less" href="/css/case_details.css"></link>

	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/less.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			//alert('ready');
		});
	</script>
</head>
<body>
<div class="header"><a href="">WEEKLY REPORTS</a><a class="hilite" href="/case_home.php">COURT CASES</a></div>
<div class="page">
<table id="details">
<tr>
	<td class="box" id="summary">
		<h3>CR0132</h3>
		<p>Created on Mar 04, 2014<br>by Insp. Kumaravel</p>
		<p>Investigated by<br>DSP. Raj Narayan</p>
		<p class="aligncenter"><button>Update</button></p>
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
		<td class="remarks"><p>Remarks</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
	<tr>
		<td><p>Mar 04, 2014</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu</p></td>
		<td><p>Adjourned for next week</p></td>
	</tr>
</table>

<div id="addproceedings" class="aligncenter"><button>Add Proceeding</button></div>

</div> <!-- page -->
</body>
</html>
