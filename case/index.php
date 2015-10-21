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
	<link rel="stylesheet/less" href="/common/common_print.css" media="print"></link>
	<link rel="stylesheet/less" href="/case/recent.css"></link>
	<link rel="stylesheet/less" href="/case/caselist.css"></link>
	<link rel="stylesheet/less" href="/case/editcase.css"></link>
	<link rel="stylesheet/less" href="/case/details.css" media="screen,print"></link>
	<link rel="stylesheet/less" href="/case/addproceeding.css"></link>
	<link rel="stylesheet/less" href="/case/reminderlist.css"></link>
	<link rel="stylesheet/less" href="/case/dashboard.css"></link>
	<link rel="stylesheet/less" href="/case/advancedsearch.css"></link>
	<link rel="stylesheet/less" href="/case/report.css"></link>
	<link rel="stylesheet/less" href="/case/changestatus.css"></link>
	<link rel="stylesheet/less" href="/case/changedvacstatus.css"></link>

	<script type="text/javascript" src="/common/jquery.js"></script>
	<script type="text/javascript" src="/common/jquery-ui.js"></script>
	<script type="text/javascript" src="/common/less.js"></script>
	<script type="text/javascript" src="/common/utils.js"></script>
	<script type="text/javascript" src="/case/app.js"></script>
	<script type="text/javascript" src="/case/toolbar.js"></script>
	<script type="text/javascript" src="/case/warnings.js"></script>
	<script type="text/javascript" src="/case/navigation.js"></script>
	<script type="text/javascript" src="/case/recent.js"></script>
	<script type="text/javascript" src="/case/caselist.js"></script>
	<script type="text/javascript" src="/case/editcase.js"></script>
	<script type="text/javascript" src="/case/details.js"></script>
	<script type="text/javascript" src="/case/changestatus.js"></script>
	<script type="text/javascript" src="/case/changedvacstatus.js"></script>
	<script type="text/javascript" src="/case/addproceeding.js"></script>
	<script type="text/javascript" src="/case/addcomment.js"></script>
	<script type="text/javascript" src="/case/closecase.js"></script>
	<script type="text/javascript" src="/case/assign.js"></script>
	<script type="text/javascript" src="/case/attach.js"></script>
	<script type="text/javascript" src="/case/addreminder.js"></script>
	<script type="text/javascript" src="/case/reminderlist.js"></script>
	<script type="text/javascript" src="/case/dashboard.js"></script>
	<script type="text/javascript" src="/case/advancedsearch.js"></script>
	<script type="text/javascript" src="/case/report.js"></script>
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
			warnings.init();
			navigation.init();
			recent.init();
			caselist.init();
			editcase.init();
			details.init();
			changestatus.init();
			changedvacstatus.init();
			addproceeding.init();
			addcomment.init();
			closecase.init();
			assign.init();
			attach.init();
			addreminder.init();
			reminderlist.init();
			dashboard.init();
			advancedsearch.init();
			report.init();

			//console.log('document.ready state:' + JSON.stringify(history.state));
			recent.show(false, true);
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
		<input type="text" id="toolbar_data"></input>
		<p><a href="" id="toolbar_advanced">Advanced Search</a></p>
	</div>
</div>

<div class="warnings">
	<div id="warnings_close">X</div>
	<div id="warnings_text">You have cases under your range for which proceedings have not been updated. Please check the dashboard.</div>
</div> <!-- warnings -->

<div class="content">
	<table><tr>
		<td class="nav">
			<a href="" class="hilite" id="nav_recent">Recent Activity</a>
			<div class="count important" id="num_assigned">3</div><a href="" id="nav_assigned">Assigned to Me</a>
			<div class="count" id="num_upcoming_hearings">3</div><a href="" id="nav_upcoming_hearings">Upcoming Hearings</a>
			<div class="count important" id="num_reminders">3</div><a href="" id="nav_reminders">Reminders</a>
			<a href="" id="nav_dashboard">Dashboard</a>
			<!--
			<div class="count" id="num_nohearings">3</div><a href="" id="nav_nohearings">NOT SPECIFIED</a>
			<p class="navsectiontitle">CASES BY CATEGORY</p>
			<div id="categorylist">
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

<div class="page" id="page_recent">
	<div class="recent" id="recentarea">
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
	</div> <!-- recentarea -->

	<div class="aligncenter action"><button class="primary" id="recent_more">Show More</button></div>

</div> <!-- page_recent -->

<div class="page" id="page_caselist">
	<div class="cl_header">
		<div class="cl_serial_no">No.</div>
		<div class="cl_details">Case Details</div>
		<div class="cl_next_hearing">Next Hearing</div>
		<div class="cl_investigator">Investigated By</div>
		<div class="cl_location">Detachment</div>
	</div>
	<div id="caselistarea">
		<!--
		<div class="cl_data">
			<div class="cl_details"><a href="1">Crl.OP.123/2014</a>
				<p class="extra">Petitioner - Tmt. Kanimozhi</p></div>
			<div class="cl_investigator">Raj Narayan, DSP
				<p class="extra">Madurai</p></div>
			<div class="cl_next_hearing">Jul 24, 2014</div>
			<div class="cl_last green">OPEN
				<p class="extra">Last activity 2 days ago</p></div>
		</div>
		<div class="cl_data">
			<div class="cl_details"><a href="1">Crl.OP.123/2014</a>
				<p class="extra">Petitioner - Tmt. Kanimozhi</p></div>
			<div class="cl_investigator">Raj Narayan, DSP
				<p class="extra">Madurai</p></div>
			<div class="cl_next_hearing">Jul 24, 2014</div>
			<div class="cl_last green">OPEN
				<p class="extra">Last activity 2 yrs ago</p></div>
		</div>
		-->
	</div> <!-- caselistarea -->

	<div class="aligncenter" id="total">Showing 20 of 200</div>	
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
		<td class="field"><p>Tag (RCNo, CCNo etc)</p></td>
		<td><p><input type="text" class="fullwidth" id="editcase_tag"></input></p>
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
		<td class="field"><p>Next Hearing<br>(Optional)</p></td>
		<td><p><input type="text" id="editcase_next_hearing"></input></p></td>
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
		<p class="status">
			<span id="details_status"></span> &nbsp;<a href="" id="details_change">Change</a>
		</p>
		<p class="status">
			<span id="details_dvac_status"></span> &nbsp;<a href="" id="details_dvac_change">Change</a>
		</p>
		<br>
		<p class="text">Petitioner<br><span id="details_petitioner"></span></p>
		<p class="text">Respondent<br><span id="details_respondent"></span></p>
		<p class="text">Investigated by<br><span id="details_io"></span></p>
		<p class="text">Assigned to<br><span id="details_assigned_to"></span></p>
		<p class="text">Next hearing<br><span id="details_next_hearing"></span></p>
		<p class="text">Tag<br><span id="details_tag"></span></p>
		<p class="text">Direction<br><span id="details_direction"></span></p>
		<p class="text">Prayer<br><span id="details_prayer"></span></p>
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
		<button class="primary" id="btn_attach">Attach</button>
		<button class="primary" id="btn_addproceeding">Add Proceeding</button>
		<button class="primary" id="btn_addcomment">Comment</button>
		<button class="primary" id="btn_assign">Assign</button>
		<button class="primary" id="btn_addreminder">Add Reminder</button>
	</p>

</div> <!-- page_details -->

<div class="page" id="page_reminderlist">
	<div class="rl_header">
		<div class="rl_case">Case</div>
		<div class="rl_comment">Remarks</div>
		<div class="rl_remind_on">When</div>
	</div>
	<div id="reminderlistarea">
		<!--
		<div class="rl_data">
			<div class="rl_case"><a href="1">Crl.OP.123/2014</a></div>
			<div class="rl_comment">Remind about the case</div>
			<div class="rl_remind_on">Jul 24, 2014</div>
		</div>
		<div class="rl_data">
			<div class="rl_case"><a href="1">Crl.OP.123/2014</a></div>
			<div class="rl_comment">Remind about the case</div>
			<div class="rl_remind_on">Jul 24, 2014</div>
		</div>
		-->
	</div> <!-- reminderlistarea -->
	
</div> <!-- page_reminderlist -->

<div class="page" id="page_dashboard">
	<div class="card" id="global">
		<h6>Total Cases in DVAC</h6>
		<div class="section green" type="global_open">
			<h1 id="global_num_open"></h1><div class="small">Open in Court</div></div>
		<div class="section red" type="global_dvac_open">
			<h1 id="global_num_dvac_open"></h1><div class="small">Open With DVAC</div></div>
		<div class="section gray" type="global_closed">
			<h1 id="global_num_closed"></h1><div class="small">Closed</div></div>
	</div>
	<div class="card" id="range">
		<h6>Cases in My Range</h6>
		<div class="section green" type="range_open">
			<h1 id="range_num_open"></h1><div class="small">Open in Court</div></div>
		<div class="section red" type="range_dvac_open">
			<h1 id="range_num_dvac_open"></h1><div class="small">Open With DVAC</div></div>
		<div class="section gray" type="range_closed">
			<h1 id="range_num_closed"></h1><div class="small">Closed</div></div>
	</div>
	<div class="card" id="detachment">
		<h6>By Detachment</h6>
		<div id="dash_location"></div>
	</div>
	<div class="card" id="hearing">
		<h6>By Hearing</h6>
		<div id="dash_hearing">
			<div class="count" id="hearing_num_upcoming"></div><p type="upcoming_hearings">Upcoming</p>
			<div class="count" id="hearing_num_notspecified"></div><p type="notspecified_hearings">Not Specified by Court</p>
			<div class="count" id="hearing_num_notupdated"></div><p type="notupdated_hearings">Proceeding not Updated</p>
		</div>
	</div>
	<div class="card" id="category">
		<h6>By Category</h6>
		<div id="dash_category"></div>
	</div>
	<div class="card" id="team">
		<h6>By Team</h6>
		<ul id="dash_team">
			<!--
			<li><div class="count">12</div><p>Krupa</p>
				<ul>
					<li><div class="count">12</div><p>Shanmuga Priya</p>
						<ul>
							<li><div class="count">12</div><p>Shahul</p></li>
							<li><div class="count">2</div><p>Raj Narayan</p></li>
						</ul>
					</li>
					<li><div class="count">2</div><p>Kumaravel</p></li>
				</ul>
			</li>
			-->
		</ul>
	</div>
</div> <!-- page_dashboard -->

<div class="page" id="page_advancedsearch">
	<div id="filterarea">
		<h3>Court Status</h3>
		<div id="filter_status">
			<!--
			<label><input type="checkbox" value="10"></input> OPEN</label>
			<label><input type="checkbox" value="30"></input> CLOSED</label>
			-->
		</div>

		<h3>DVAC Status</h3>
		<div id="filter_dvac_status">
			<!--
			<label><input type="checkbox" value="10"></input> OPEN</label>
			<label><input type="checkbox" value="30"></input> CLOSED</label>
			-->
		</div>

		<h3>Direction</h3>
		<div id="filter_direction">
			<!--
			<label><input type="checkbox" value="20"></input> Direction 1</label>
			<label><input type="checkbox" value="30"></input> Direction 2</label>
			-->
		</div>

		<h3>Detachment</h3>
		<div id="filter_detachment">
			<!--
			<label><input type="checkbox"></input> Madras</label>
			<label><input type="checkbox"></input> Madurai</label>
			-->
		</div>

		<h3>Category</h3>
		<div id="filter_category">
			<!--
			<label><input type="checkbox"></input> Crl.OP</label>
			<label><input type="checkbox"></input> Contempt Appeal</label>
			-->
		</div>

		<h3>Court</h3>
		<div id="filter_court">
			<!--
			<label><input type="checkbox"></input> Madras</label>
			<label><input type="checkbox"></input> Madurai</label>
			<label><input type="checkbox"></input> Supreme Court</label>
			-->
		</div>

		<h3>Officer</h3>
		<div id="filter_officer">
			<div id="filter_investigator">
				Investigated by<br><input type="text" id="advanced_investigator"></input>
			</div>
			<div id="filter_assignedto">
				Assigned to<br><input type="text" id="advanced_assignedto"></input>
			</div>
		</div>

		<h3>Hearing</h3>
		<div id="filter_hearing">
			<div id="filter_hearingafter">
				After<br><input type="text" id="advanced_hearingafter"></input>
			</div>
			<div id="filter_hearingbefore">
				Before<br><input type="text" id="advanced_hearingbefore"></input>
			</div>
		</div>

		<h3>Other</h3>
		<div id="filter_other">
			<div id="filter_tag">
				<p>Tag (RCNo, CCNo, etc)<br><input type="text" id="advanced_tag"></input></p>
			</div>
			<div id="filter_year">
				<p>Year(yyyy)<br><input type="text" id="advanced_year"></input></p>
			</div>
			<div id="filter_petitioner">
				<p>Petitioner<br><input type="text" id="advanced_petitioner"></input></p>
			</div>
			<div id="filter_respondent">
				<p>Respondent<br><input type="text" id="advanced_respondent"></input></p>
			</div>
			<div id="filter_prayer">
				<p>Prayer<br><input type="text" id="advanced_prayer"></input></p>
			</div>
		</div>


	</div>
	<div class="actions aligncenter">
		<button class="primary" id="advanced_search">Advanced Search</button>
		<button class="secondary" id="advanced_report">Generate Report</button>
		<button class="secondary" id="advanced_reset">Reset</button>
	</div>
</div> <!-- page_advancedsearch -->


<div class="dialog" id="dlg_changestatus">
	<p><select id="changestatus_status"></select></p>
	<p class="alignright"><button class="primary" id="changestatus_save">Save</button></p>
</div> <!-- dlg_changestatus -->

<div class="dialog" id="dlg_changedvacstatus">
	<p><select id="changedvacstatus_status"></select></p>
	<p><select id="changedvacstatus_direction"></select></p>
	<p class="alignright"><button class="primary" id="changedvacstatus_save">Save</button></p>
</div> <!-- dlg_changedvacstatus -->


<div class="dialog" id="dlg_addproceeding">
	<table id="tbl_proceeding">
	<tr><td>Court</td><td><select id="proc_court"></select></td></tr>
	<tr><td class="field">Hall No.</td><td class="data"><input type="text" id="proc_hall"></input></td></tr>
	<tr><td>Item No.</td><td><input type="text" id="proc_item"></input></tr>
	<tr><td>Justice</td><td><input type="text" class="fullwidth" id="proc_judge"></input></tr>
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
</div> <!-- dlg_assign -->

<div class="dialog" id="dlg_attach">
	<p>Type<br><select id="attach_type"></select></p>
	<p><input type="file" name="attachment" id="attach_file"></input></p>
	<p>Remarks<br><textarea class="fullwidth" id="attach_comment"></textarea></p>
	<p class="alignright"><button class="primary" id="attach_save">Save</button></p>
</div> <!-- dlg_attach -->


<div class="dialog" id="dlg_addreminder">
	<p>Remind On<br><input type="text" class="fullwidth" id="reminder_on"></input></p>
	<p>Remarks<br><textarea class="fullwidth" id="reminder_comment"></textarea></p>
	<p class="alignright"><button class="primary" id="reminder_save">Save</button></p>
</div> <!-- dlg_addreminder -->

<div class="dialog" id="dlg_report">
	<div id="report"></div>
</div> <!-- dlg_report -->

</body>
</html>
