<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require_once("db_functions.php");
    //connect to the database
    db_connect(); 

    $pid = ""; 

    //get id from URL
    if(isset($_POST['ticketPid'])){
        $pid = (int) $_POST['ticketPid']; 
    }

    //establish query 
    $query = "SELECT name, gender, age, homeDest, class, cabinNumber"; 
    $query .= " FROM passenger";
    $query .= " INNER JOIN ticket ON passenger.pid = ticket.pid"; 
    $query .= " INNER JOIN cabin ON passenger.pid = cabin.pid";
    $query .= " WHERE passenger.pid = ?"; 

    //prepare the query 
    $stmt = $connection->prepare($query); 
    //bind id from URL to query 
    $stmt->bind_param('i', $pid); 
    //execute the query 
    $stmt->execute(); 
    //bind the results from the query to variables
    $stmt->bind_result($name, $gender, $age, $homeDest, $class, $cabin);

	
?>

<?php 
if($stmt->fetch()){
	echo '<div id="modal"></div>';

	echo '<div class="ticket">';
	echo '<div class="ticket-content">'; 

	echo '	<div class="ticket-column">'; 

	echo '<div class="ticket-left-content">'; 

	echo '	<div class="ticket-item">'; 
    echo"   <h2>Name</h2>";
    echo    $name;
	echo '	</div>'; 

	echo '	<div class="ticket-item">'; 
	echo"   <h2>Home Destination</h2>"; 
    echo    $homeDest;
	echo '	</div>'; 

	echo '	<div class="ticket-double">'; 
	echo '	<div class="ticket-item">'; 

    echo '		<h2>Age</h2>'; 
	echo ''; 
	if($age !== NULL){
		echo (int) $age;
	}
	echo '	</div>'; 

	echo '	<div class="ticket-item">'; 

    echo '		<h2>Gender</h2>'; 
    echo $gender; 
	echo '	</div>'; 

	echo '	</div>'; 
	echo '</div>';

	echo '<div class="ticket-right-content">'; 

			echo '	<div class="ticket-item">'; 
				echo '<h2>Cabin</h2>'; 
				echo $cabin;
			echo '</div>';

				echo '<div class="ticket-item">'; 
					echo '<h2>Class</h2>';
					//display class as their relevant strings based on the return
					if($class == 1){
						echo '<p>First Class</p>';
					} else if($class == 2){
						echo '<p>Second Class</p>'; 
					} else if($class == 3){
						echo '<p>Third Class</p>'; 
					}
				echo '</div>';
			echo '</div>'; 
		echo '</div>';
		echo '</div>'; 

		if(isset($_SESSION['valid_user'])){
			db_connect(); // Found in db_functions.

			$checkIfExistsQuery = "SELECT * FROM favorites";
			$checkIfExistsQuery .= " WHERE favorites.pid = '".$pid."'";
			$checkIfExistsQuery .= " AND favorites.emailAddress = '".$_SESSION['valid_user']."'";
			//echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$checkIfExistsQuery."</code></div></p>"; // Print SQL statement in plain text.

			$result = db_query($checkIfExistsQuery); // Send off query to msqli.
			// Step 4: Display Result
			if (!$result) { 
				die("Bad query! Please mark mercifully."); 
			}
			$row = $result->fetch_assoc();    //SOMETHING'S WRONG HERE


			if ($row['emailAddress'] != $_SESSION['valid_user']) {
				echo '<a href="addToAddressBook.php?pid='.$pid.'" class="ticket-button">Add to address book</a>';
			} else {
				echo '<a href="removeFromAddressBook.php?pid='.$pid.'" class="ticket-button">Remove from address book</a>';

			}
		}
	echo '</div>'; 

}

?>
