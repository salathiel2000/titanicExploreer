<?php
    include("includes/header.php");
    
    $_SESSION['valid_user'] = array();

    session_destroy(); 

    header("login.php"); 


?>