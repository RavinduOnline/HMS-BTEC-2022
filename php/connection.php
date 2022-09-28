<?php 

$servername ="localhost"; 
$username = "root"; 
$password = ""; 
$db = "hmsdb";

// Create connection 
$connection = new mysqli($servername, $username, $password,$db); 

	// Checking the connection
	if ($connection->connect_error) {
		die('Database connection failed ' . mysqli_connect_error());
	} else {
		// echo "Connected successfully"; 
	}

?>