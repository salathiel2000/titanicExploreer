<?php

	// Just basic DB info. Root as requested.	
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'titanic';
	$connection;

	function db_connect() {
		global $dbhost, $dbuser, $dbpass, $dbname, $connection; // Access globals.

		$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		// Test if connection succeeded
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . 
			mysqli_connect_error() . 
			" (" . mysqli_connect_errno() . ")"
			); // Just the basic example error message.
		}
		echo '<br>Debug: Connection Successful</br>';
	}
	function db_query($queryString) {
		global $connection;
		return $result = mysqli_query($connection, $queryString);
	}
	
	function db_close() {
	global $result, $connection;
		//$result->free_result();
		$connection->close();
		
	}
?>