<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require_once("db_functions.php"); 
	db_connect(); // Quick connect function.


if (isset($_SESSION['valid_user'])) {
	
	//fName validation
	if(!empty($_POST['fName'])){
            $fName = trim($_POST['fName']);
            if(!validate_string($fName)){
                $error["fName"] = "Please enter a valid name"; 
            }
    } else {
            $error["fName"] = "Please enter a value"; 
	}
	//lName validation
    if(!empty($_POST['lName'])){
        $lName = trim($_POST['lName']); 
        if(!validate_string($lName)){
            $error["lName"] = "Please enter a valid name"; 
        }
    } else {
        $error["lName"] = "Please enter a value"; 
    }

	//age validation
	if(!empty($_POST['age'])){
		$age = (int) $_POST['age']; 
		if(!is_numeric($age) || $age > 150){
			$error["age"] = "Please enter a valid age"; 
		}
	} else {
		$error["age"] = "Please enter a value"; 
	}

	//income validation 
	if(!empty($_POST['income'])){
		$income = (int) $_POST['income']; 
		if(!is_numeric($income)){
			$error["income"] = "Please enter a valid value"; 
		}
	} else {
		$error["income"] = "Please enter a value"; 
	}

	$gender = $_POST['gender'];

	$class = 3; // Third class.
	if ($income > 12000) {
		$class -= 1; // Second class.
		if ($income > 40000) {
			$class -= 1; // First class.
		}
	}

	//UPDATE
	$query = "UPDATE ";
	$query .= "member";

	// SET
	$query .= " SET fName = '".$fName."', lName = '".$lName."', userAge = '".$age."', annualIncome = '".$income."', userGender = '".$gender."', userClass = '".$class."' ";
	//$query .= "lName = '". ."' ";

	//WHERE
	$query .= " WHERE member.emailAddress = '".$_SESSION['valid_user']."'"; // Just makes it so I don't have to check for a condition being the FIRST in a long chain.
    
	//SUBMIT
	echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		
	$result = db_query($query); // Send off query to msqli.
	mysqli_free_result($result);

	$query = "DELETE FROM assignments WHERE assignments.emailAddress = '".$_SESSION['valid_user']."'";

	$result = db_query($query); // Send off query to msqli.

	$query = "SELECT";
	$query .= " passenger.pid";
	$query .= " FROM passenger"; // Begin table selection.
	$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
	$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
	$query .= " AND ticket.class = '". $class ."'";
	$query .= " AND (ABS(passenger.age - ". $age .") < 5)";
	$query .= " AND passenger.gender = '".$gender."'";
	$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
	$query .= " ORDER BY passenger.pid LIMIT 1";

	echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		
	$result = db_query($query); // Send off query to msqli.
    if (!$result) { 
		$query = "SELECT";
		$query .= " passenger.pid";
		$query .= " FROM passenger"; // Begin table selection.
		$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
		$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
		$query .= " AND ticket.class = '". $class ."'";
		$query .= " AND passenger.gender = '".$gender."'";
		$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
		$query .= " ORDER BY passenger.pid LIMIT 1";
		$result = db_query($query); // Send off query to msqli.
		if (!$result) {
			$query = "SELECT";
			$query .= " passenger.pid";
			$query .= " FROM passenger"; // Begin table selection.
			$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
			$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
			$query .= " AND ticket.class = '". $class ."'";
			$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
			$query .= " ORDER BY passenger.pid LIMIT 1";
			$result = db_query($query); // Send off query to msqli.
			if (!$result) {
			$query = "SELECT";
			$query .= " passenger.pid";
			$query .= " FROM passenger"; // Begin table selection.
			$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
			$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
			$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
			$query .= " ORDER BY passenger.pid LIMIT 1";
				$result = db_query($query); // Send off query to msqli.
				if (!$result) {
					die("Error: All passengers are assigned!");
				}
			}
		}
	}
	$row = $result->fetch_assoc();
	mysqli_free_result($result);
	$insertQuery = "INSERT INTO assignments (emailAddress, pid) ";
	$insertQuery .= "VALUES (?, ?)";
	$stmt = $connection->prepare($insertQuery); 
    $stmt->bind_param('si', $_SESSION['valid_user'], $row['pid']);
	$stmt->execute();
	$stmt->close(); 

	
	db_close(); // Also found in db_functions.
}
?>