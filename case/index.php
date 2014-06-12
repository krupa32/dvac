<?php
	session_start();
	if (!$_SESSION["user_id"] || $_SESSION["user_id"] == "admin")
		header("location: /login.php");
?>
<html>
<head>
	<title>Court Cases and Proceedings</title>

	<link rel="stylesheet" href="/common/jquery-ui.css"></link>
	<link rel="stylesheet/less" href="/common/common.css"></link>
	<link rel="stylesheet/less" href="/case/caselist.css"></link>
	<link rel="stylesheet/less" href="/case/editcase.css"></link>
	<link rel="stylesheet/less" href="/case/search.css"></link>
	<link rel="stylesheet/less" href="/case/details.css"></link>
	<link rel="stylesheet/less" href="/case/addproceeding.css"></link>

	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/common/less.js"></script>
	<script type="text/javascript" src="/common/utils.js"></script>
	<script type="text/javascript" src="/case/app.js"></script>
	<script type="text/javascript" src="/case/toolbar.js"></script>
	<script type="text/javascript" src="/case/navigation.js"></script>
	<script type="text/javascript" src="/case/caselist.js"></script>
	<script type="text/javascript" src="/case/editcase.js"></script>
	<script type="text/javascript" src="/case/search.js"></script>
	<script type="text/javascript" src="/case/details.js"></script>
	<script type="text/javascript" src="/case/changestatus.js"></script>
	<script type="text/javascript" src="/case/addproceeding.js"></script>
	<script type="text/javascript" src="/case/addcomment.js"></script>
	<script type="text/javascript" src="/case/closecase.js"></script>
	<script type="text/javascript" src="/case/assign.js"></script>
	<script type="text/javascript">
		<?php
			print "var user_id = ${_SESSION['user_id']};\n";
			print "var user_name = '${_SESSION['user_name']}';\n";
			print "var user_grade = ${_SESSION['user_grade']};\n";
		?>
		$(document).ready(function(){
			$('.page').detach().appendTo('.pagearea').hide();


			app.init();
			toolbar.init();
			navigation.init();
			caselist.init();
			editcase.init();
			search.init();
			details.init();
			changestatus.init();
			addproceeding.init();
			addcomment.init();
			closecase.init();
			assign.init();

			//console.log('document.ready state:' + JSON.stringify(history.state));
			if (history.state)
				app.popstate(history.state);
			else
				caselist.show({ type:'recent' }, false, true);
		});
	</script>
</head>
<body>

<div class="ajaxstatus">
	Loading...
</div>

<div class="current_user floatleft">
Welcome <?php print $_SESSION["user_name"]; ?>
</div>

<div class="header">
	<a href="/user/home.php">MY PROFILE</a>
	<a>WEEKLY REPORTS</a>
	<a class="hilite" href="/case/">COURT CASES</a>
	<a href="/logout.php">LOGOUT</a>
</div>

<div class="toolbar">
	<div class="toolbarbuttonarea">
		<button class="primary" id="toolbar_addcase">Add Case</button>
	</div>

	<div class="toolbarsearcharea">
		<select id="toolbar_field">
			<option value="case_num">Case Number</option>
			<option value="petitioner">Petitioner</option>
			<option value="respondent">Respondent</option>
			<option value="investigator">IO</option>
			<option value="assigned_to">Assigned</option>
			<option value="location">Detachment</option>
		</select>
		<input type="text" id="toolbar_data"></input>
		<button class="primary" id="toolbar_search">Search</button></p></td>
	</div>
</div>

<div class="content">
	<table><tr>
		<td class="nav">
			<a href="" class="hilite" id="nav_recent">RECENT ACTIVITY</a>
			<p class="navsectiontitle">CASE SUMMARY</p>
			<div class="count important" id="num_my">3</div><a href="" id="nav_my">MY CASES</a>
			<div class="count" id="num_pending_court">3</div><a href="" id="nav_pending_court">PENDING IN COURT</a>
			<div class="count important" id="num_pending_dvac">3</div><a href="" id="nav_pending_dvac">PENDING WITH DVAC</a>
			<p class="navsectiontitle">HEARINGS</p>
			<div class="count" id="num_hearings">3</div><a href="" id="nav_hearings">UPCOMING HEARINGS</a>
			<div class="count" id="num_nohearings">3</div><a href="" id="nav_nohearings">NOT SPECIFIED</a>
			<p class="navsectiontitle">CASES BY CATEGORY</p>
			<div id="categorylist">
			<!--
			<div class="count" id="num_crlop">305</div><a href="" id="nav_category_crlop">Crl.OP</a>
			<div class="count" id="num_rc">3</div><a href="" id="nav_category_rc">Crl.RC</a>
			<div class="count" id="num_wp">3</div><a href="" id="nav_category_wp">WP</a>
			<div class="count" id="num_wa">3</div><a href="" id="nav_category_wa">WA</a>
			<div class="count" id="num_ca">3</div><a href="" id="nav_category_ca">CA</a>
			<div class="count" id="num_contemptpetition">3</div><a href="" id="nav_category_contemptpetition">Contempt Petition</a>
			<div class="count" id="num_contemptappeal">3</div><a href="" id="nav_category_contemptappeal">Contempt Appeal</a>
			<div class="count" id="num_srno">3</div><a href="" id="nav_category_srno">SR.No</a>
			-->
			</div>
		</td>

		<td class="pagearea"></td>

	</tr></table>

</div> <!-- content -->

<div class="page" id="page_caselist">
	<div class="caselist" id="caselistarea">
		<!--
		<div class="case">
			<p class="casenum"><a href="2">Crl.OP.2003/43/4</a></p>	
			<p class="extra">Filed by Tmt. Kanimozhi<br>Respondent Raj Narayan, DSP, DVAC, Shahul, SP, DVAC</p>
			<p class="text">To disburse death cum retirement grtuity for my unblemished service within a stipulated
				period fixed by the court.</p>
			<div class="activityarea">
				<div class="activity">
					<p class="title floatright">Today</p>
					<p class="title">Raj Narayan, DSP added a proceeding</p>
					<p class="extra">At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu<br>
						ADJOURNED to Mar 04, 2014</p>
					<p class="text">Court has directed to submit more documents</p>
				</div>
				<div class="activity">
					<p class="title floatright">Today</p>
					<p class="title">Raj Narayan, DSP added a proceeding</p>
					<p class="extra">At Hall 13, Madurai HC, by Hon'ble Judge Mr. Nagamuthu<br>
						ADJOURNED to Mar 04, 2014</p>
					<p class="text">Court has directed to submit more documents</p>
				</div>
			</div>
		</div>
		-->
	</div> <!-- caselist -->

	<div class="aligncenter action"><button class="primary" id="caselist_more">Show More</button></div>

</div> <!-- page_caselist -->

<div class="page" id="page_editcase">
	<table class="form">
	<colgroup>
		<col class="field"></col>
		<col class="data"></col>
	</colgroup>
	<tr>
		<td class="field">Category, Court, No., Year</td>
		<td>
			<select id="editcase_category"></select>
			<select id="editcase_court"></select>
			<input type="text" id="editcase_no"></input>
			<input type="text" id="editcase_year"></input>
		</td>
	</tr>
	<tr>
		<td class="field"><p>Case Number</p></td>
		<td><p><span id="editcase_case_num"></span></p>
	</tr>
	<tr>
		<td class="field"><p>Investigating Officer</p></td>
		<td><p><input type="text" class="fullwidth" id="editcase_investigator"></input></p>
	</tr>
	<tr>
		<td class="field"><p>Petitioner</p></td>
		<td><p><textarea class="fullwidth" id="editcase_petitioner"></textarea></p>
	</tr>
	<tr>
		<td class="field"><p>Respondent</p></td>
		<td><p><textarea class="fullwidth" id="editcase_respondent"></textarea></p>
	</tr>
	<tr>
		<td class="field"><p>Prayer</p></td>
		<td><p><textarea class="fullwidth" id="editcase_prayer"></textarea></p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><p class="aligncenter"><button class="primary" id="editcase_save">Save</button></p></td>
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
	<div class="details">
		<p class="floatright"><button class="primary" id="details_edit">Edit</button></p>
		<p class="casenum" id="details_case_num"></p>
		<p class="status"><span id="details_status"></span> &nbsp;<a href="" id="details_change">Change</a></p>
		<p class="petitioner ">Petitioner<br><span id="details_petitioner"></span></p>
		<p class="respondent ">Respondent<br><span id="details_respondent"></span></p>
		<p class="io ">Investigated by<br><span id="details_io"></span></p>
		<p class="assigned_to ">Assigned to<br><span id="details_assigned_to"></span></p>
		<p class="assigned_to ">Next hearing<br><span id="details_next_hearing"></span></p>
		<p class="text" id="details_prayer"></p>
		<p>CASE HISTORY</p>
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
		</div> <!-- historyarea -->

	</div>

	<p class="actions aligncenter">
		<input type="file" name="attachment" id="details_attachment"></input>
		<button class="primary" id="btn_attach">Attach</button>
		<button class="primary" id="btn_addproceeding">Add Proceeding</button>
		<button class="primary" id="btn_addcomment">Comment</button>
		<button class="primary" id="btn_assign">Assign</button>
	</p>

</div> <!-- page_details -->

<div class="dialog" id="dlg_changestatus">
	<p><select id="changestatus_status"></select> &nbsp;<button class="primary" id="changestatus_save">Save</button></p>
</div> <!-- dlg_changestatus -->

<div class="dialog" id="dlg_addproceeding">
	<table id="tbl_proceeding">
	<tr><td>Court</td><td><select id="proc_court"></select></td></tr>
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
	<tr><td>Next Hearing<br>(if applicable)</td><td><input type="text" id="proc_hearing"></input></td></tr>
	<tr><td>Remarks</td><td><textarea class="fullwidth" id="proc_remarks"></textarea></td></tr>
	<tr><td>&nbsp;</td><td class="alignright"><button class="primary" id="btn_save_proceeding">Save</button></td></tr>
	</table>
</div> <!-- dlg_addproceeding -->

<div class="dialog" id="dlg_addcomment">
	<p><textarea class="fullwidth" id="comment_text"></textarea></p>
	<p class="alignright"><button class="primary" id="btn_save_comment">Save</button></p>
</div> <!-- dlg_addcomment -->

<div class="dialog" id="dlg_assign">
	<p>Assign to<br><input type="text" class="fullwidth" id="assign_to"></input></p>
	<p>Remarks<br><textarea class="fullwidth" id="assign_comment"></textarea></p>
	<p class="alignright"><button class="primary" id="btn_save_assignment">Save</button></p>
</div> <!-- dlg_addcomment -->

</body>
</html>
