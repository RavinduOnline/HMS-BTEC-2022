<?php 

$servername ="localhost"; 
$username = "root"; 
$password = ""; 
$db = "hmsdb";

// Create connection 
$conn = new mysqli($servername, $username, $password,$db); 

	// Checking the connection
	if ($conn->connect_error) {
		die('Database connection failed ' . mysqli_connect_error());
	} else {
		// echo "Connected successfully"; 
	}

?>