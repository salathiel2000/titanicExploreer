<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require_once("db_functions.php"); 
	db_connect(); // Quick connect function.
		

if (isset($_SESSION['valid_user'])) {
//SELECT
	$query = "SELECT ";
	$query .= "member.fName, member.lName, member.userAge, member.userGender, member.annualIncome";
//FROM
	$query .= " FROM member"; // Begin table selection.	
//WHERE

	$query .= " WHERE (true)"; // Just makes it so I don't have to check for a condition being the FIRST in a long chain.
	$query .= " AND member.emailAddress = '". $_SESSION['valid_user'] . "'";
		
	
//SUBMIT		
	$result = db_query($query); // Send off query to msqli.

	// Display Result
	if (!$result) { 
		die("Bad query! Please mark mercifully."); 
	}

	$row = $result->fetch_assoc(); // Get row.
	$fName = $row['fName'];
	$lName = $row['lName'];
	$userAge = $row['userAge'];
	$userGender = $row['userGender'];
	$annualIncome = $row['annualIncome'];

	echo '<h3>Edit Profile</h3>';
			
	echo '<div class="row">';
		echo '<div class="stacked half">';

			echo '<label for="fName">First Name</label> <input id="fName" name="fName" type="text"';
			if (!empty($fName)) echo 'value="'.$fName.'"';
			echo '> ';
		echo '</div>';
		echo '<div class="stacked half">';
			echo '<label for="lName">Last Name</label> <input id="lName" name="lName" type="text"';
			if (!empty($lName)) echo 'value="'.$lName.'"';
			echo'>';
		echo '</div>';
	echo '</div>';


	echo '<div class="row">';
		echo '<div class="stacked third">';
			echo '<label for="age">Age </label>';
			echo '<input id="age" type="number" name="age" min="0" max="150" ';
			if (!empty($userAge)) echo 'value="'.$userAge.'"';
			echo '>';
		echo '</div>';
		echo '<div class="stacked third">';
			echo '<label for="income">Annual Income </label>';
			echo '<input id="annualIncome" type="number" name="income" placeholder="$"';
			if (!empty($annualIncome)) echo 'value="'.$annualIncome.'"';
			echo '">';
		echo '</div>';
		echo '<div class="stacked third">';
			echo '<fieldset>';
			echo '<legend>Gender </legend>';
			echo '<input id="male" type="radio" name="gender" value="male"';
			if(!empty($userGender) && $userGender == "male") { echo "checked";}
			echo '> Male<br>';
			echo '<input id="female" type="radio" name="gender" value="female"';
			if(!empty($userGender) && $userGender == "female") { echo "checked";}
			echo '> Female<br>';
			echo '</fieldset>';
		echo '</div>';
	echo '</div>';

	echo '<input id="saveProfile" type="submit" name="save" value="Save">';
 
	db_close(); // Also found in db_functions.
}
?>