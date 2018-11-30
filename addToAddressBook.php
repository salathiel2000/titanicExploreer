<?php
    session_start(); 
    require_once("includes/db_functions.php");  
    db_connect(); 

    if(isset($_SESSION['valid_user'])){

        //INSERT queey 
        $query = "INSERT INTO favorites (emailAddress, pid)"; 
        $query .= " VALUES(?, ?)"; 

        $stmt = $connection->prepare($query);
        //bind email address and product id to VALUES
        $stmt->bind_param('ss', $email, $pid); 
        //set variables that are bound 
        $email = $_SESSION['valid_user']; 
        if(isset($_GET['pid'])){
            $pid = (int) $_GET['pid'];
        }
        //execute query 
        $stmt->execute(); 
    }

    $stmt->close(); 
    $connection->close(); 

    //redirect to addressBook page 
    header("Location: addressBook.php"); 
?>