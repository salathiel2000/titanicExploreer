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

	function validate_string($value) {
        $string_regex = '/^[a-zA-Z ]*$/';
        return preg_match($string_regex, $value) === 1;
      }

    function validate_email($value) {
        $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i'; 
        return preg_match($email_regex, $value) === 1; 
	}
	
	function validate_number($value){
		$number_regex = '/[^0-9]/';
		return preg_match($number_regex, $value) === 1; 
	}

	//generates a random number between the entered values and checks if the entered value is 
	//
	function percentChance($chance){
		$randPercent = mt_rand(0,99);
		return $chance > $randPercent;
	  }
?>