<?php
	$admin_password = "fossil27";

	/*
	 * database related
	 */
	$db_host = "localhost";
	$db_user = "root";
	$db_password = "fossil27";
	$db_name = "dvac";

	/* upload directory */
	$upload_dir = "/case/uploads/";

	/* num_items_per_fetch
	 * specifies the number of items (cases) fetched and displayed
	 * per request for a case list. Remaining items can be displayed
	 * using the 'Show More' button.
	 * 
	 * NOTE: If this number is changed, do not forget to update
	 * caselist.js, which hardcodes this number to decide whether the
	 * 'more' button is displayed.
	 */
	$num_items_per_fetch = 10;

	/* num_days_recent_activity
	 * this specifies the number of days considered for recent activity.
	 */
	$num_days_recent_activity = 30;

	/* num_recent_activities_per_case
	 * this specifies the number of recent activities listed per case
	 * in the 'recent activity' page.
	 */
	$num_recent_activities_per_case = 3;

	/* num_days_upcoming_hearings
	 * this specifies the number of days to be considered to decide an
	 * 'upcoming' hearing. for eg, if this value is 5, a hearing is
	 * considered upcoming if it is in the next 5 days, and not considered
	 * upcoming if it is scheduled beyond 5 days.
	 * WARNING: This is obsolete now. All upcoming hearings are listed now.
	 */
	$num_days_upcoming_hearings = 5;

	/* user grades
	 * each user has a grade, which is a number corresponding
	 * to his current designation. it is intentionally defined
	 * in gaps of 10 to accomodate new grades in the future.
	 */
	$grades = array(
			"Inspector"	=> 10,
			"DSP"		=> 20,
			"ADSP"		=> 30,
			"SP"		=> 40,
			"Deputy Dir"	=> 50,
			"Joint Dir"	=> 60,
			"Director"	=> 70
			);

	/* courts
	 * court locations where DVAC cases/proceedings happen.
	 */
	$courts = array(
			"Madras"	=> 1,
			"Madurai"	=> 2,
			"Supreme Court"	=> 3
			);

	/* counsels
	 * list of counsels who can appear on a dvac case proceeding
	 */
	$counsels = array(
			"Advocate General"		=> 1,
			"Addl. Advocate General"	=> 2,
			"Public Prosecutor"		=> 3,
			"Addl. Public Prosecutor"	=> 4,
			"Government Pleader"		=> 5,
			"Addl. Government Pleader"	=> 6,
			"Spl. Public Prosecutor"	=> 7,
			"Spl. Government Pleader"	=> 8,
			"Government Advocate"		=> 9
			);

	/* locations
	 * locations of dvac offices or dvac officers.
	 */
	$locations = array(
			"Chennai"		=> 1,
			"Madurai"		=> 2,
			"Coimbatore"		=> 3,
			"Tirunelveli"		=> 4
			);

	/* categories
	 * categories to which a court case can belong.
	 */
	$categories = array(
			"Crl.OP"		=> 1,
			"Crl.RC"		=> 2,
			"Crl.Appeal"		=> 3,
			"WP"			=> 4,
			"WA"			=> 5,
			"Contempt Petition"	=> 6,
			"Contempt Appeal"	=> 7,
			"SR.No"			=> 8,
			"Misc"			=> 9,
			"Crl.MP"		=> 10,
			"SLP(Civil)"		=> 11,
			"SLP(Criminal)"		=> 12,
			"Diary No"		=> 13
			);

	/* activities
	 * list of activities for court cases.
	 */
	$activities = array(
			"ADDCASE"		=> 1,
			"UPDATECASE"		=> 2,
			"ADDPROCEEDING"		=> 3,
			"ADDCOMMENT"		=> 4,
			"ASSIGN"		=> 5,
			"CHANGESTATUS"		=> 6,
			"CLOSE"			=> 7,
			"ATTACH"		=> 8
			);

	/* statuses
	 * case statuses.
	 */
	$statuses = array(
		"PENDING_IN_COURT"		=> 10,
		"PENDING_WITH_DVAC"		=> 20,
		"CLOSED"			=> 30
	);
	
	/* disposals.
	 * court case disposals.
	 */
	$disposals = array(
			"NONE"			=> 1,
			"ADJOURNED"		=> 2,
			"INTERIM ORDER PASSED"	=> 3,
			"FINAL ORDER PASSED"	=> 4,
			"OTHER"			=> 5
			);
?>
