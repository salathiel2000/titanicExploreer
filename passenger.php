<?php
    include("includes/header.php"); 
    require_once("includes/db_functions.php");

    //connect to the database
    db_connect(); 

    $pid = ""; 

    //get id from URL
    if(isset($_GET['pid'])){
        $pid = (int) $_GET['pid']; 
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

    $checkIfExistsQuery = "SELECT * FROM favorites";
    $checkIfExistsQuery .= " WHERE pid = ? AND emailAddress = ?"; 

    //SOMETHING'S WRONG HERE

    // //prepare the statemen
    // $stmt2 = $connection->prepare($checkIfExistsQuery); 
    // //bind variables for product code and email to the query
    // $stmt2->bind_param('is', $pid, $email); 
   
    // echo $connection->$error; 

    // //only set email variable if the user is actually logged in
    // if(isset($_SESSION['valid_user'])){
    //     $email = $_SESSION['valid_user']; 
    // }
    // //execute the query
    // $stmt2->execute(); 
    // //store result in a variable
    // $checkRes = $stmt2->get_result(); 
?>

<?php 
if($stmt->fetch()){

	echo '<div class="ticket">';
	echo '<div class="ticket-content">'; 

	//echo '	<div class="ticket-title">';
	//echo '	</div>';
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
	echo '<div class="ticket-columns">'; 

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
			echo '<a href="addToAddressBook.php?pid='.$pid.'" class="ticket-button">Add to address book</a>';
		}
	echo '</div>'; 

}

//display button if user is logged in
// if((isset($_SESSION['valid_user'])) && ($checkRes->num_rows == 0)){


//have to add in functionality to check if passenger is already in address book 
/*


*/

?>
