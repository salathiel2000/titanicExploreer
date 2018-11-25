<?php 	
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php");
	require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");
	if (isset($_SESSION['valid_user'])) {

		db_connect(); // Quick connect function.

	//SELECT
		$query = "SELECT ";
		$query .= "*";
	//FROM
		$query .= " FROM assignments"; // Begin table selection.
		$query .= " INNER JOIN member ON assignments.emailAddress = member.emailAddress";
		$query .= " INNER JOIN passenger ON assignments.pid = passenger.pid";
		$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";
		$query .= " INNER JOIN cabin ON passenger.pid = cabin.pid";
	
	//WHERE

			$query .= " WHERE (true)"; // Just makes it so I don't have to check for a condition being the FIRST in a long chain.
			$query .= " AND assignments.emailAddress = '". $_SESSION['valid_user'] . "'";
		
		
	//SUBMIT
		echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		$result = db_query($query); // Send off query to msqli.


		// Display Result
			if (!$result) { 
				die("Bad query! Please mark mercifully."); 
			}

			$row = $result->fetch_assoc(); // Get row.
			echo '<h3>Profile</h3>';
			echo '<p>Welcome '.$row['fName'].' '.$row['lName'].'! ('.$row['emailAddress'].')</p>';
			echo '<p>Your entered age is '.$row['userAge'].', your entered gender is '.$row['userGender'].', and your entered income ('.$row['annualIncome'].') adjusted for inflation puts you in class '.$row['userClass'].'.</p>';
			echo '<p>Based on your entered information, we have matched you to the following passenger from the Titanic:</p>';
			// Print table.
			echo '<table>';
			echo '	<tr><td><h3>Passenger Information</h3></td></tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Name: </strong></td>';
			echo '		<td>'.$row['name'].'</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Gender: </strong></td>';
			echo '		<td>'.$row['gender'].'</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Age: </strong></td>';
			echo '		<td>'.$row['age'].' years old</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Family Onboard: </strong></td>';
			echo '		<td>'.$row['familyOnboard'].'</td>';
			echo '	</tr>';
						echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Embarked from: </strong></td>';
			echo '		<td>'.$row['embarked'].'</td>';
			echo '	<tr><td><h3>Ticket Information</h3></td></tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Ticket Number: </strong></td>';
			echo '		<td>'.$row['ticketNumber'].'</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Fare Paid: </strong></td>';
			echo '		<td>$'.$row['fare'].' USD</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Class: </strong></td>';
			echo '		<td>'.$row['class'].'</td>';
			echo '	</tr>';
			echo '	<tr><td><h3>Cabin Information</h3></td></tr>';
			echo '	<tr>';
			echo '		<td style="text-align:right;"><strong>Cabin Number: </strong></td>';
			echo '		<td>'.$row['cabinNumber'].'</td>';
			echo '	</tr>';
			echo '</table>';

		db_close(); // Also found in db_functions.
	}
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>