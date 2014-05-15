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
	<link rel="stylesheet/less" href="/case/recent.css"></link>
	<link rel="stylesheet/less" href="/case/editcase.css"></link>
	<link rel="stylesheet/less" href="/case/search.css"></link>
	<link rel="stylesheet/less" href="/case/details.css"></link>
	<link rel="stylesheet/less" href="/case/addproceeding.css"></link>

	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/common/less.js"></script>
	<script type="text/javascript" src="/common/utils.js"></script>
	<script type="text/javascript" src="/case/toolbar.js"></script>
	<script type="text/javascript" src="/case/navigation.js"></script>
	<script type="text/javascript" src="/case/recent.js"></script>
	<script type="text/javascript" src="/case/editcase.js"></script>
	<script type="text/javascript" src="/case/search.js"></script>
	<script type="text/javascript" src="/case/details.js"></script>
	<script type="text/javascript" src="/case/addproceeding.js"></script>
	<script type="text/javascript" src="/case/addcomment.js"></script>
	<script type="text/javascript" src="/case/closecase.js"></script>
	<script type="text/javascript" src="/case/assign.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.page').detach().appendTo('.pagearea').hide();

			toolbar.init();
			navigation.init();
			recent.init();
			editcase.init();
			search.init();
			details.init();
			addproceeding.init();
			addcomment.init();
			closecase.init();
			assign.init();

			recent.show();
		});
	</script>
</head>
<body>
<div class="header">
	<a href="/user/home.php">MY PROFILE</a>
	<a>WEEKLY REPORTS</a>
	<a class="hilite" href="/case/">COURT CASES</a>
	<a href="/logout.php">LOGOUT</a>
</div>

<div class="toolbar">
	<div class="toolbarbuttonarea">
		<button id="btn_addcase">Add Case</button>
	</div>

	<div class="toolbarsearcharea">
		<select id="toolbar_category">
			<option value="case_num">Case Number</option>
			<option value="petitioner">Petitioner</option>
			<option value="respondent">Respondent</option>
			<option value="investigator">IO</option>
			<option value="assigned_to">Assigned</option>
		</select>
		<input type="text" id="toolbar_data"></input>
		<button id="btn_search">Search</button></p></td>
	</div>
</div>

<div class="content">
	<div class="nav">
		<p><a href="" id="nav_recent">Recent Activity</a></p>
		<p><a href="" id="nav_open">Open Cases (<span id="nav_num_open_cases"></span>)</a></p>
		<p><a href="" id="nav_my">My Cases (<span id="nav_num_my_cases">3</span>)</a></p>
	</div>
	<div class="pagearea">
	</div>
	<div class="clear"></div>
</div>

<div class="page" id="page_recent">
	<p class="sectiontitle">RECENT ACTIVITY</p>
	<div id="activityarea">
		<!--
		<div class="activity">
			<p class="title floatright">Today</p>
			<p class="title">Raj Narayan, DSP added case <a href="10">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
				Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC</p>
			<p class="text">To disburse death cum retirement grtuity for my unblemished service within a stipulated
				period fixed by the court.</p>
		</div>
		<div class="activity">
			<p class="title floatright">Today</p>
			<p class="title">Raj Narayan, DSP assigned case <a href="">Crl.OP/2000/14</a> to Shahul, SP</p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
			Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC</p>
			<p class="text">Assigning to Shahul for filing in court</p>
		</div>
		<div class="activity">
			<p class="title floatright">2 days ago</p>
			<p class="title">Raj Narayan, DSP updated a proceeding for case <a href="">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
			Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC<br>
			At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu</p>
			<p class="text">Court has directed to submit more documents</p>
		</div>
		<div class="activity">
			<p class="title floatright">2 days ago</p>
			<p class="title">Raj Narayan, DSP updated a proceeding for case <a href="">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
				Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC</br>
				At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu</p>
			<p class="text"></p>
		</div>
		-->
	</div> <!-- activityarea -->
</div> <!-- page_recent -->

<div class="page" id="page_editcase">
	<table class="form">
	<colgroup>
		<col class="field"></col>
		<col class="data"></col>
	</colgroup>
	<tr>
		<td>Category</td>
		<td><select id="sel_category"></select></td>
	</tr>
	<tr>
		<td><p>Case Number</p></td>
		<td><p><input type="text" id="txt_case_num" value="Crl.OP"></input></p>
	</tr>
	<tr>
		<td><p>Investigating Officer</p></td>
		<td><p><input type="text" class="fullwidth" id="txt_investigator"></input></p>
	</tr>
	<tr>
		<td><p>Petitioner</p></td>
		<td><p><textarea class="fullwidth" id="ta_petitioner"></textarea></p>
	</tr>
	<tr>
		<td><p>Respondent</p></td>
		<td><p><textarea class="fullwidth" id="ta_respondent"></textarea></p>
	</tr>
	<tr>
		<td><p>Prayer</p></td>
		<td><p><textarea class="fullwidth" id="ta_prayer"></textarea></p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><p class="aligncenter"><button id="btn_save_case">Save</button></p></td>
	</tr>

	</table>
</div> <!-- page_editcase -->

<div class="page" id="page_search">
	<p class="sectiontitle">SEARCH RESULTS</p>
	<div id="resultarea">
		<!--
		<div class="result">
			<p class="link"><a href="">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
				Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC<br>
				Assigned to Raj Narayan, DSP</p>
			<p class="text">To disburse death cum retirement grtuity for my unblemished service within a 
				stipulated period fixed by the court.</p>
		</div>
		<div class="result">
			<p class="link"><a href="">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
				Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC<br>
				Assigned to Raj Narayan, DSP</p>
			<p class="text">To disburse death cum retirement grtuity for my unblemished service within a 
				stipulated period fixed by the court.</p>
		</div>
		<div class="result">
			<p class="link"><a href="">Crl.OP/2000/14</a></p>
			<p class="extra">Filed by Tmt. Kanimozhi<br>
				Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC<br>
				Assigned to Raj Narayan, DSP</p>
			<p class="text">To disburse death cum retirement grtuity for my unblemished service within a 
				stipulated period fixed by the court.</p>
		</div>
		-->
	</div>

</div> <!-- page_search -->

<div class="page" id="page_details">
	<p class="caseid" id="details_case_num"></p>
	<p class="status"><span id="details_status"></span></p>
	<p class="petitioner">Petitioner<br><span id="details_petitioner"></span></p>
	<p class="respondent">Respondent<br><span id="details_respondent"></span></p>
	<p class="io">Investigated by<br><span id="details_io"></span></p>
	<p class="assigned_to">Assigned to<br><span id="details_assigned_to"></span></p>
	<p class="text" id="details_prayer"></p>
	<p class="actions aligncenter">
		<button id="details_update">Update</button>
		<button id="details_close">Close</button>
	</p>

	<p class="sectiontitle">CASE HISTORY</p>
	<div id="historyarea">
		<!--
		<div class="activity">
			<p class="title floatright">Mar 04, 2014</p>
			<p class="title">Raj Narayan, DSP added case</p>
		</div>
		<div class="activity">
			<p class="title floatright">Mar 04, 2014</p>
			<p class="title">Raj Narayan, DSP assigned case to Shahul, SP</p>
			<p class="text">Assigning to Shahul for filing in court</p>
		</div>
		<div class="activity">
			<p class="title floatright">Mar 04, 2014</p>
			<p class="title">Raj Narayan, DSP updated a proceeding for case</p>
			<p class="extra">At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu</p>
			<p class="text"></p>
		</div>
		<div class="activity">
			<p class="title floatright">Mar 04, 2014</p>
			<p class="title">Raj Narayan, DSP updated a proceeding for case<p>
			<p class="extra">At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu<br>
				Counsel Advocate General appeared</p>
			<p class="text">Court has directed to submit more documents</p>
		</div>
		-->
	</div>
	<p class="actions aligncenter">
		<button id="btn_addproceeding">Add Proceeding</button>
		<button id="btn_addcomment">Add Comment</button>
		<button id="btn_assign">Assign</button>
	</p>

</div> <!-- page_details -->

<div class="dialog" id="dlg_addproceeding">
	<table id="tbl_proceeding">
	<tr><td>Court</td><td><select id="proc_court"><option value="1">Chennai</option><option value="2">Madurai</option></select></td></tr>
	<tr><td class="field">Hall No.</td><td class="data"><input type="text" id="proc_hall"></input></td></tr>
	<tr><td>Item No.</td><td><input type="text" id="proc_item"></input></tr>
	<tr><td>Judge</td><td><input type="text" class="fullwidth" id="proc_judge"></input></tr>
	<tr><td>Counsel</td><td><select id="proc_counsel">
		<option value="1">Advocate General</option>
		<option value="2">Addl. Advocate General</option>
		<option value="3">Public Prosecutor</option>
		<option value="4">Addl. Public Prosecutor</option>
		<option value="5">Government Pleader</option>
		<option value="6">Addl. Government Pleader</option>
		<option value="7">Spl. Public Prosecutor</option>
		</select></td></tr>
	<tr><td>Disposal</td><td><select id="proc_disposal">
		<option value="1">None</option>
		<option value="2">Adjourned</option>
		<option value="3">Interim Order</option>
		<option value="4">Final Order</option>
		<option value="5">Other</option>
		</select></td></tr>
	<tr><td>Remarks</td><td><textarea class="fullwidth" id="proc_remarks"></textarea></tr>
	<tr><td>&nbsp;</td><td class="alignright"><button id="btn_save_proceeding">Save</button></td></tr>
	</table>
</div> <!-- dlg_addproceeding -->

<div class="dialog" id="dlg_addcomment">
	<p><textarea class="fullwidth" id="comment_text"></textarea></p>
	<p class="alignright"><button id="btn_save_comment">Save</button></p>
</div> <!-- dlg_addcomment -->

<div class="dialog" id="dlg_closecase">
	<p>Enter a comment<br>
		<textarea class="fullwidth" id="close_text"></textarea></p>
	<p class="alignright"><button id="btn_close_case">Save and Close</button></p>
</div> <!-- dlg_closecase -->

<div class="dialog" id="dlg_assign">
	<p>Assign to<br><input type="text" class="fullwidth" id="assign_to"></input></p>
	<p>Remarks<br><textarea class="fullwidth" id="assign_comment"></textarea></p>
	<p class="alignright"><button id="btn_save_assignment">Save</button></p>
</div> <!-- dlg_addcomment -->

</body>
</html>
