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

	//UPDATE
	$query = "UPDATE ";
	$query .= "member";

	// SET
	$query .= " SET fName = '".$fName."', lName = '".$lName."', userAge = '".$age."', annualIncome = '".$income."', userGender = '".$gender."' ";
	//$query .= "lName = '". ."' ";

	//WHERE
	$query .= " WHERE member.emailAddress = '".$_SESSION['valid_user']."'"; // Just makes it so I don't have to check for a condition being the FIRST in a long chain.
    
	//SUBMIT

	echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		
	$result = db_query($query); // Send off query to msqli.

	

	
	db_close(); // Also found in db_functions.
}
?>