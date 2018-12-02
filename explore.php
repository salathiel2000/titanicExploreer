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


<form action="explore.php" method="get">
	<header>
		<h1>Query</h1>
	</header>
	<p>
	<div class="panel">
	<h3 id="filter">Filters</h3>
	<p>Search:
	<input id="search" name="search" type="text" <?php if (isset($searchKeyword)) echo 'value="'.$searchKeyword.'"';?>>
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

$limit = 50; 

$pageNum = 27; 

echo "<select id=\"chooseNumber\">";
for($i=1; $i < 28; $i++){
	echo "<option id=\"".$i."\">".$i."</option>";
}
echo "</select>";
echo "<p id=\"pages\">".$pageNum."</p>"; 

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
//LIMIT
		$query .= " LIMIT ".$limit; 
		// $query .= " OFFSET ".$offset; 
//SUBMIT
	echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
	$result = db_query($query); // Send off query to msqli.
	echo "<code id=\"testCode\"></code>";

	
	?>
	<?php
		
			// Step 4: Display Result
			if (!$result) { 
				die("Bad query! Please mark mercifully."); 
			}

			// Print table.
			echo '<table>';
			echo '	<thead>';
			echo '		<tr>';
			echo '		<th><strong>Name</strong></th>';
			echo '		<th><strong>Home/Destination</strong></th>';
			echo '		<th><strong>Cabin</strong></th>';
			echo '		<th><strong>Class</strong></th>';
			echo '		</tr>';
			echo '	</thead>';

			// echo '<tr id="result"></tr>';
			
			
			while ($row = $result->fetch_assoc()) { // Get associative array row by row.
				echo '	<tr>';
				echo '		<td id="passengerName"><a href="passenger.php?pid='.$row['pid'].'">'.$row['name'].'</a></td>';
				echo '		<td id="homeDest"';
				if (!empty($row['homeDest'])) echo '>'.$row['homeDest'];
				else echo ' class="unknown">Unknown';
				echo '</td>';
				echo '		<td id="cabinNumber"';
				if (!empty($row['cabinNumber'])) echo '>'.$row['cabinNumber'];
				else echo ' class="unknown">Unknown';
				echo'</td>'; 
				echo '		<td id="class">'.$row['class'].'</td>';
				echo '	</tr>';
			}
			
			echo '	</table>';	

	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>