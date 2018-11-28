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

?>

<?php 
if($stmt->fetch()){
    echo "  <h1>Passenger Card</h1>";
    echo"   <h2>Name</h2>";
    echo    $name;
    echo"   <h2>Age</h2>"; 
    echo    $age;
    echo"   <h2>Gender</h2>"; 
    echo    $gender; 
    echo"   <h2>Home Destination</h2>"; 
    echo    $homeDest;
    echo"   <h2>Cabin</h2>"; 
    echo    $cabin;
    echo"   <h2>Class</h2>";

    //display class as their relevant strings based on the return
    if($class == 1){
        echo"   <p>First Class</p>";
    } else if($class == 2){
        echo"   <p>Second Class</p>"; 
    } else if($class == 3){
        echo"   <p>Third Class</p>"; 
    }

}

//display button if user is logged in
if(isset($_SESSION['valid_user'])){
    echo "<a href=\"#\">Add to address book</a>";
}

//have to add in functionality to check if passenger is already in address book 
/*


*/

?>
