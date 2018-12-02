<?php 
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
	require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");
	if (!empty($_SESSION['valid_user'])) {

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

<?php
	} 
	else
	{
		

		echo '<Insert Crew>';
		db_connect(); // Quick connect function.
		//echo print_r($_SESSION['valid_user']);
//SELECT
		$query = "SELECT ";
		$query .= "*, ";
//FROM
		$query = substr($query, 0, -2); // Remove last comma.
		$query .= " FROM assignments"; // Begin table selection.
		$query .= " INNER JOIN passenger ON assignments.pid = passenger.pid";
		$query .= " INNER JOIN member ON assignments.emailAddress = member.emailAddress";
		$query .= " INNER JOIN (SELECT max(creationDate) as recentDate, emailAddress FROM member GROUP BY emailAddress LIMIT 3) Recents ON member.creationDate = Recents.recentDate AND member.emailAddress = Recents.emailAddress ";
//WHERE
		$query .= " ORDER BY member.creationDate DESC ";
//SUBMIT
		//echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		$result = db_query($query); // Send off query to msqli.

		if (!$result) {
				die("Bad query! Please mark mercifully."); 
			}

			// Print table.
			echo '<h3>Recent Members</h3>';
			echo '<table>';
			echo '	<thead>';
			echo '		<tr>';
			echo '			<th><strong>Name</strong></th>';
			echo '			<th><strong>Date</strong></th>';
			echo '		</tr>';
			echo '	</thead>';

			// echo '<tr id="result"></tr>';
			while ($row = $result->fetch_assoc()) { // Get associative array row by row.
				echo '	<tr>';
				echo '		<td>'.$row['fName'].' (<a href="passenger.php?pid='.$row['pid'].'">'.$row['name'].'</a>)</td>'; 
				echo '		<td>'.$row['creationDate'].'</td>';
				echo '	</tr>';
			}
			
			echo '</table>';	
	}

	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>