<?php
    include("includes/header.php");
    require_once("includes/db_functions.php"); 
    db_connect(); 

    $query = "SELECT name, homeDest, class, cabinNumber"; 
    $query .= " FROM passenger"; // Begin table selection.
	$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";
    $query .= " INNER JOIN cabin ON passenger.pid = cabin.pid";
    $query .= " WHERE passenger.class = ?";

    echo $query; 

    // $stmt = $connectin->prepare($query);
?>

