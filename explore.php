<?php 
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
?>

<?php

	// Fetch form submission variables if included.
	if (isset($_POST['submit'])) $submit = $_POST['submit'];

	if (isset($_POST['filterDeck'])) $requestedDeck = $_POST['filterDeck']; // If user wants specific deck.
	if (isset($_POST['filterClass'])) $requestedClass = $_POST['filterClass']; // If user wants specific class.
	
	require_once("includes/db_functions.php"); // Include basic database connection functions.
	require_once("includes/functions.php"); // Include utility functions.

	// Step 1: Connect.
	db_connect(); // Found in db_functions.
?>

<?php

?>

<form action="explore.php" method="post">
	<header>
		<h1>Query</h1>
	</header>
	<p>

	<div class="panel">
	<h3>Filters</h3>
	<p>Cabin Deck:
		<select name="filterDeck">
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
		<select name="filterClass">
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



	<input type="submit" name="submit" value="Submit">
			

</form>

<?php 
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>