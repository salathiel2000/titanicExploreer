<?php
    include("includes/header.php");
    require_once("includes/db_functions.php"); 
    db_connect(); 

    //suquery that returns the user's class
    $subquery = "SELECT class"; 
    $subquery .= " FROM member"; 
    $subquery .= " WHERE emailAddress = \"".$_SESSION['valid_user']."\"";

    //query to recommend
    $query =    "SELECT passenger.pid, name, homeDest, class, cabinNumber"; 
    $query .=   " FROM passenger"; // Begin table selection.
    $query .=   " INNER JOIN ticket ON passenger.pid = ticket.pid";
    $query .=   " INNER JOIN cabin ON passenger.pid = cabin.pid";
    $query .=   " WHERE class IN ($subquery)"; 
    $query .=   " ORDER BY RAND()"; 
    $query .=   " LIMIT 10"; 
    
    // $checkVal = 0; //used to test if values are returning correctly with the percentageChance

    if(percentChance(5)){
        // $checkVal = 1;
        $query  =   "SELECT passenger.pid, name, homeDest, class, cabinNumber"; 
        $query .=   " FROM passenger"; // Begin table selection.
        $query .=   " INNER JOIN ticket ON passenger.pid = ticket.pid";
        $query .=   " INNER JOIN cabin ON passenger.pid = cabin.pid";
        $query .=   " ORDER BY RAND()"; 
        $query .=   " LIMIT 10"; 
    }

    $result = db_query($query); 
    // echo $checkVal; 

    ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Home Destination</th>
                <th>Class</th>
                <th>Cabin Number</th>
            </tr>
        </thead>
        
    <?php
    while($row = $result->fetch_assoc()){
        echo    "<tr>"; 
        echo        "<td><a href=\"passenger.php?pid=".$row['pid']."\">".$row['name']."</a></td>"; 
        echo        "<td>".$row['homeDest']."</td>"; 
        echo        "<td>".$row['class']."</td>"; 
        echo        "<td>".$row['cabinNumber']."</td>"; 
        echo    "</tr>";
    }
    ?>

    </table>

