<?php
	$admin_password = "fossil27";

	/*
	 * database related
	 */
	$db_host = "localhost";
	$db_user = "root";
	$db_password = "fossil27";
	$db_name = "dvac";

	/* num_recent_activities_per_fetch
	 * this specifies the number of recent activities shown initially,
	 * AND the number of activities fetched when 'more' button is clicked.
	 */
	$num_recent_activities_per_fetch = 20;

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
			"Chennai HC"	=> 1,
			"Madurai HC"	=> 2
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
			"Spl. Public Prosecutor"	=> 7
			);

	/* locations
	 * locations of dvac offices or dvac officers.
	 */
	$locations = array(
			"Chennai"		=> 1,
			"Madurai"		=> 2,
			"Coimbatore"		=> 3
			);

	/* categories
	 * categories to which a court case can belong.
	 */
	$categories = array(
			"Crl.OP"	=> 1,
			"RC"		=> 2,
			"CA"		=> 3,
			"WP"		=> 4,
			"WA"		=> 5
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
			"CLOSE"			=> 6
			);

	/* statuses
	 * case statuses.
	 */
	$statuses = array(
			"OPEN"		=> 1,
			"CLOSED"	=> 2
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
