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
	<link rel="stylesheet/less" href="/css/case_home.css"></link>

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
<div id="toolbar">
	<table>
	<tr>
		<td><p><button>Add New Case</button></p></td>
		<td><p class="alignright"><input type="text"></input></p></td>
	</tr>
	</table>
</div> <!-- toolbar -->

<div class="sectiontitle">Showing today's proceedings</div>

<table id="proceedings" class="list">
	<tr class="tableheader">
		<td class="case"><p>Case</p></td>
		<td class="petitioner"><p>Petitioner</p></td>
		<td class="respondent"><p>Respondent</p></td>
		<td class="court"><p>Court</p></td>
	</tr>
	<tr>
		<td><p><a href="">CR0132</a><br>Investigated by Insp. Kumaravel<br>from Madurai</p></td>
		<td><p>Tmt. A. Kanimozhi</p></td>
		<td><p>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu Balasubramanian</p></td>
	</tr>
	<tr>
		<td><p><a href="">CR0132</a><br>Investigated by Insp. Kumaravel<br>from Madurai</p></td>
		<td><p>Tmt. A. Kanimozhi</p></td>
		<td><p>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu Balasubramanian</p></td>
	</tr>
	<tr>
		<td><p><a href="">CR0132</a><br>Investigated by Insp. Kumaravel<br>from Madurai</p></td>
		<td><p>Tmt. A. Kanimozhi</p></td>
		<td><p>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu Balasubramanian</p></td>
	</tr>
	<tr>
		<td><p><a href="">CR0132</a><br>Investigated by Insp. Kumaravel<br>from Madurai</p></td>
		<td><p>Tmt. A. Kanimozhi</p></td>
		<td><p>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu Balasubramanian</p></td>
	</tr>
	<tr>
		<td><p><a href="">CR0132</a><br>Investigated by Insp. Kumaravel<br>from Madurai</p></td>
		<td><p>Tmt. A. Kanimozhi</p></td>
		<td><p>Insp. Kumaravel, TNHB<br>DSP. Raj Narayan, DVAC<br>SP. Shahul, DVAC</p></td>
		<td><p>Hall 13<br>in Madurai HC<br>by Honble Justice Mr. Nagamuthu Balasubramanian</p></td>
	</tr>
</table>

<div id="addproceedings" class="aligncenter"><button>Add Proceeding</button></div>

</div> <!-- page -->
</body>
</html>
