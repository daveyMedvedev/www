<?php
/**************************************
* Database Provisioning App           *
* Fidelity Investments 2016           *
* David Medvedev                      *
* Contact: David.Medvedev1@marist.edu *
* (203) 554-6157                      *
***************************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

$errors         = array();  	// array to hold validation errors
$data 			= array(); 		// array to pass back data
$conn_string	= array();		// array to hold connection string
$rolname		= array();		// array to hold user's username string
$rolpass		= array();		// array to hold user password string
$userrole		= array();		// array to hold user's role string
$database		= array();		// array to hold database string


	 $rolname = $_POST['username'];
	 $rolpass = $_POST['password'];


	 //connect to database
     $conn_string = "host=localhost port=5432 dbname=TrueCourse user=$rolname password=$rolpass";
     $db_found    = pg_connect($conn_string);
	 
	 
	 if (!$db_found) {
		$data['success'] = false;
		$data['errors']  = $errors;
		
		
		
	 } else {
		$data['success'] = true;
		$data['errors']  = "Success!";

	 }
		
	
	
	// return all our data to an AJAX call
	echo json_encode($data);
?>