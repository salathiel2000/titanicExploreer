<?php 
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
?>

<?php

	// Fetch form submission variables if included.
	if (isset($_GET['submit'])) $submit = $_GET['submit'];

	if (isset($_GET['filterDeck'])) $requestedDeck = $_GET['filterDeck']; // If user wants specific deck.
	if (isset($_GET['filterClass'])) $requestedClass = $_GET['filterClass']; // If user wants specific class.
	if (isset($_GET['search'])) $searchKeyword = $_GET['search'];
	require_once("includes/db_functions.php"); // Include basic database connection functions.
	require_once("includes/functions.php"); // Include utility functions.

	// Step 1: Connect.
	db_connect(); // Found in db_functions.

	require_once("includes/pagination.php"); //Include pagination functions.
?>

<?php

?>

<form action="explore.php" method="get">
	<header>
		<h1>Query</h1>
	</header>
	<p>
	<div class="panel">
	<h3 id="filter">Filters</h3>
	<p>Search:
	<input name="search" type="text" <?php if (isset($searchKeyword)) echo 'value="'.$searchKeyword.'"';?>>
	Cabin Deck:
		<select id="filterDeck" name="filterDeck">
			<option value="any">Any</option>	
			<?php

				// Step 2a: Process Query for Decks
				$query = "SELECT DISTINCT LEFT(cabinNumber, 1) "; 
				$query .= "FROM cabin order by cabinNumber;";
				$result = db_query($query);
				if (!$result) { 
					die("Unable to fetch decks."); // Basic error.
				}

				while($row = $result->fetch_assoc()){
					foreach($row as $deck => $deckValue){
						if ($deckValue != null) {
							echo "<option value=\"".$deckValue."\"";
							if ((!empty($requestedDeck)) && ($requestedDeck == $deckValue)) {
								echo " selected= \"selected\"";
							}
							echo ">".$deckValue."</option>"; // Make option for each known deck.
						}
					}
				}
				$result->free_result();
			?>
		</select>

		Class: 
		<select id="filterClass" name="filterClass">
			<option value="any">Any</option>	
			<?php

				// Step 2b: Process Query for Class
				$query = "SELECT DISTINCT class "; 
				$query .= "FROM ticket order by class;";
				$result = db_query($query);
				if (!$result) { 
					die("Unable to fetch ticket classes."); // Basic error.
				}

				while($row = $result->fetch_assoc()){
					foreach($row as $class => $classValue){
						if ($classValue != null) {
							echo "<option value=\"".$classValue."\"";
							if ((!empty($requestedClass)) && ($requestedClass == $classValue)) {
								echo " selected= \"selected\"";
							}
							echo ">".$classValue."</option>"; // Make option for each known class.
						}
					}
				}
				$result->free_result();
			?>
			
		</select>
	</p>



	<input type="submit" name="submit" value="Submit"><br>
			
</form>

<p>Pagination</p>

<?php

//display number of pages 
for($i=1; $i<$pages+1; $i++){ // TO-DO: Limit number of pages if the number of results is lower than the maximum.
	echo '<a href="explore.php?'.http_build_query(array_merge($_GET, array('pageNum'=> $i))).'">'.$i.' </a>'; // Revised line which adds a new URL parameter.
	if($i>1 && isset($_GET['pageNum'])){
		$currPage = (int) $_GET['pageNum']; 
	} else {
		$currPage = 1; 
	}
}

//which page are we currently on
$offset = ($currPage - 1) * $limit; 

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
			$query .= " AND (passenger.name LIKE '%".$searchKeyword."%' OR passenger.homeDest LIKE '%".$searchKeyword."%')";
		}
//LIMIR
		$query .= " LIMIT ".$limit." OFFSET ".$offset; 
//SUBMIT
	echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
	$result = db_query($query); // Send off query to msqli.

	
	?>
	<?php
		
			// Step 4: Display Result
			if (!$result) { 
				die("Bad query! Please mark mercifully."); 
			}

			// Print table.
			echo '<table>';
			echo '	<tr>';
			echo '		<td><strong>Name</strong></td>';
			echo '		<td><strong>Home/Destination</strong></td>';
			echo '		<td><strong>Cabin</strong></td>';
			echo '		<td><strong>Class</strong></td>';
			echo '	</tr>';

			// echo '<tr id="result"></tr>';
			while ($row = $result->fetch_assoc()) { // Get associative array row by row.
				echo '	<tr id="result">';
				echo '		<td><a href="passenger.php?pid='.$row['pid'].'">'.$row['name'].'</a></td>';
				echo '		<td>'.$row['homeDest'].'</td>';
				echo '		<td>'.$row['cabinNumber'].'</td>'; 
				echo '		<td>'.$row['class'].'</td>';
				echo '	</tr>';
			}
			
			echo "</table>";	

	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>