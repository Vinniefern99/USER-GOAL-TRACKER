<?php
require 'Slim/Slim.php';
require 'lib/mysql.php';

\Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim ();

/*
 * Display the homepage
 */
$app->get ( '/', function () use($app) {
	$db = connect_db ();
	
	$app->render ( './homepage.php', array (
			'page_title' => "My Visits App",
	) );
} );

/*
 * Get a list of all states
 */
$app->get ( '/state', function () use($app) {
	$db = connect_db ();
	$state_results = $db->query ( 'SELECT name FROM state;' );
	while ( $row = $state_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$state_results_array [] = $row;
	}
	
	$app->render ( 'state.php', array (
			'page_title' => "All States",
			'state_results_array' => $state_results_array 
	) );
} );

/*
 * Get a list of all cities in a particular state
 */
$app->get ( '/state/:state/cities', function ($state) use($app) {
	$db = connect_db ();
	$city_results = $db->query ( "SELECT name FROM city WHERE state in (SELECT abbreviation FROM state where name = '$state');" );
	
	while ( $row = $city_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$city_results_array [] = $row;
	}
	
	if (! isset ( $city_results_array )) {
		$app->render ( 'end_page.php', array (
				'page_title' => "No Records",
				'text_to_display' => "There are no cities in $state. Click Back to try again" 
		) );
		return;
	}
	
	$app->render ( 'list_of_cities.php', array (
			'page_title' => "All Cities in $state",
			'city_results_array' => $city_results_array,
			'state' => $state 
	) );
} );

/*
 * Get a list of all users
 */
$app->get ( '/user', function () use($app) {
	$db = connect_db ();
	$user_results = $db->query ( 'SELECT first_name, last_name, user_id FROM user;' );
	while ( $row = $user_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$user_results_array [] = $row;
	}
	
	$app->render ( 'user.php', array (
			'page_title' => "All Users",
			'user_results_array' => $user_results_array 
	) );
} );

/*
 * View all cities a user has visited
 */
$app->get ( '/user/:user/visits', function ($user) use($app) {
	
	$db = connect_db ();
	$visit_results = $db->query ( "SELECT city, state_abbreviation, visit_id FROM visits WHERE user_id = '$user';" );
	$visit_results_array = [ ];
	while ( $row = $visit_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$visit_results_array [] = $row;
	}
	
	$user_record = $db->query ( "SELECT first_name, last_name, user_id FROM user WHERE user_id = '$user';" );
	$exctract_user_record = $user_record->fetch_array ( MYSQLI_ASSOC );
	$user_id = $exctract_user_record ['user_id'];
	$full_name = $exctract_user_record ['first_name'] . " " . $exctract_user_record ['last_name'];
	
	$city_results = $db->query ( "SELECT name,state FROM city;" );
	while ( $row3 = $city_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$city_results_array [] = $row3;
	}
	
	$state_results = $db->query ( "SELECT abbreviation FROM state;" );
	while ( $row4 = $state_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$state_results_array [] = $row4;
	}
	
	$app->render ( 'user_visits.php', array (
			'page_title' => "All visits by this user",
			'visit_results_array' => $visit_results_array,
			'city_results_array' => $city_results_array,
			'state_results_array' => $state_results_array,
			'user' => $user,
			'full_name' => $full_name 
	) );
} );

/*
 * View the states that a user has visited
 */
$app->get ( '/user/:user/visits/states', function ($user) use($app) {
	
	$db = connect_db ();
	$visits_results = $db->query ( "SELECT name FROM state WHERE abbreviation IN (SELECT state_abbreviation FROM visits WHERE user_id = '$user');" );
	
	while ( $row = $visits_results->fetch_array ( MYSQLI_ASSOC ) ) {
		$visits_results_array [] = $row;
	}
	
	if (! isset ( $visits_results_array )) {
		$app->render ( 'end_page.php', array (
				'page_title' => "No Records",
				'text_to_display' => "This user has never travelled. Click Back to add visits." 
		) );
		return;
	}
	
	$app->render ( 'user_visits_state.php', array (
			'page_title' => "All visits by this user",
			'visits_results_array' => $visits_results_array 
	) );
} );

/*
 * Create rows of data to indicate they have visited a particular city.
 */
$app->post ( '/user/:user/visits', function ($user) use($app) {
	$city = $app->request->post ( 'city' );
	$state = $app->request->post ( 'state' );
	
	$db = connect_db ();
	
	// verify that the city and state the user selected are valid
	$validateState = $db->query ( "SELECT * FROM city WHERE name = '$city' AND state = '$state';" );
	if (! $validateState->fetch_array ( MYSQLI_ASSOC )) {
		echo "Not a valid city.";
		return;
	}
	
	$result = $db->query ( "SELECT first_name,last_name FROM user WHERE user_id = '$user';" );
	$row = $result->fetch_array ( MYSQLI_ASSOC );
	$userfirst = $row ['first_name'];
	$userlast = $row ['last_name'];
	
	$db->query ( "INSERT INTO visits(`user_id`, `city`,`state_abbreviation`,`date_added`,`datetime_added`) 
				VALUES ('$user', '$city', '$state', now(),now());" );
	
	$app->render ( 'end_page_modify.php', array (
			'page_title' => "No Records",
			'user' => $user,
			'text_to_display' => "Visit has been added. Click Back to return." 
	) );
} );

/*
 * Allow a user to remove an improperly pinned visit.
 */
$app->post ( '/user/:user/visit/:visit', function ($user, $visit) use($app) {
	
	$visit_id = $app->request->post ( 'visit_id' );
	
	$db = connect_db ();
	$sql = "DELETE FROM visits WHERE visit_id = '$visit_id';";
	
	if ($db->query ( $sql ) === TRUE) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . $conn->error;
	}
	
	$app->render ( 'end_page_modify.php', array (
			'page_title' => "No Records",
			'user' => $user,
			'text_to_display' => "Visit succussfully deleted. Click Back or exit." 
	) );
} );

$app->run ();
