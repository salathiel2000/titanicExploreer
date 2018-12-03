<?php 
	
	if (!empty(($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) {
		header("Location: http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		exit();
	}
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
?>
<div id="manifest">

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

<div class="manifest-wrapper">
<div id="resulting-ticket"></div>
	<div class="manifest-head">

	<form action="explore.php" method="get">

	<header>
		<h1>Passenger Manifest</h1>
	</header>
	<p>
	<div class="panel">
	<input id="search" placeholder="Search..." name="search" type="text" <?php if (isset($searchKeyword)) echo 'value="'.$searchKeyword.'"';?>>
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
	</div>
	</form>
</div>

<?php

$limit = 10; 

$pageNum = 131; 



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
	//echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
	$result = db_query($query); // Send off query to msqli.
	//echo "<code id=\"testCode\"></code>";

	
	?>
	<?php
		
			// Step 4: Display Result
			if (!$result) { 
				die("Bad query! Please mark mercifully."); 
			}

			// Print table.
			echo '<table class="manifest-table">';
			echo '	<thead>';
			echo '		<tr class="head">';
			echo '		<th><strong>Name</strong></th>';
			echo '		<th><strong>Home / Destination</strong></th>';
			echo '		<th><strong>Cabin</strong></th>';
			echo '		<th><strong>Class</strong></th>';
			echo '		</tr>';
			echo '	</thead>';

			// echo '<tr id="result"></tr>';
			
			
			while ($row = $result->fetch_assoc()) { // Get associative array row by row.
				echo '	<tr class="row-listener" id="'.$row['pid'].'">';
				echo '		<td id="passengerName">'.$row['name'].'</td>';
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
			echo '<div class="manifest-page-control">';

			echo "<select id=\"chooseNumber\">";
			for($i=1; $i < 28; $i++){
				echo "<option id=\"".$i."\">".$i."</option>";

			}
			echo "</select>"; 
			echo "<span id=\"pages\">/ ".$pageNum."</span>"; 
			echo '</div';

?>
	</div>
</div>
<?php
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>