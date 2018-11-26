<?php
    require_once("includes/db_functions.php"); 
    db_connect(); 

    if (isset($_POST['filterDeck'])) $requestedDeck = $_POST['filterDeck']; // If user wants specific deck.
    if (isset($_POST['filterClass'])) $requestedClass = $_POST['filterClass']; // If user wants specific class.
    if (isset($_POST['search'])) $searchKeyword = $_POST['search'];
    
    // Step 3: Process Query for Results
    //SELECT
	$query = "SELECT ";
	$query .= "passenger.pid, name, homeDest, class, cabinNumber, ";
	
    //FROM
	$query = substr($query, 0, -2); // Remove last comma.
	$query .= " FROM passenger"; // Begin table selection.
	$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";
	$query .= " INNER JOIN cabin ON passenger.pid = cabin.pid";
    
    //WHERE
    $query .= " WHERE (true)"; // Just makes it so I don't have to check for a condition being the FIRST in a long chain.
    if (!empty($requestedDeck) && ($requestedDeck != 'any')) {
        $query .= " AND LEFT(cabin.cabinNumber, 1) = '". $requestedDeck . "'";
    } 
    if (!empty($requestedClass) && ($requestedClass != 'any')) {
        $query .= " AND ticket.class = '" . $requestedClass . "'";
    }

    if (!empty($searchKeyword)) {
        $searchKeyword = filter_var($searchKeyword, FILTER_SANITIZE_SPECIAL_CHARS);
        $query .= " AND passenger.name LIKE '%".$searchKeyword."%' OR passenger.homeDest LIKE '%".$searchKeyword."%'";
    }

    //LIMIT
    $query .= " LIMIT ".$limit." OFFSET ".$offset; 

    //SUBMIT
	$result = db_query($query); // Send off query to msqli.
                
?>