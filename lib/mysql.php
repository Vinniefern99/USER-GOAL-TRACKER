<?php
// a simple database connection function - returns a mysqli connection object
function connect_db() {
	$server = 'localhost'; 
	$user = 'root';
	$pass = 'root';
	$database = 'rest_api_data';
	$connection = new mysqli($server, $user, $pass, $database);

	return $connection;
}