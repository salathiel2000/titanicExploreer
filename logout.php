<?php
    include("includes/header.php");
    require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");

	if (!empty($_POST['logout']) || empty($_SESSION['valid_user'])) {
	
		$_SESSION['valid_user'] = array();
		session_destroy(); 
		echo 'header';
		header("Location: login.php"); 
	}

	if ($_SESSION['valid_user'] != null) {

	?>
<div id="register">
	<div class="formImg">
		<img src="includes/assets/titanicPostcard_bw.jpg">
	</div>
	<div id="registerForm">
		<img src="includes/assets/topDecoration.png">
		<h1>LOGOUT</h1>


		
	<?php
		db_connect(); // Quick connect function.
		//echo print_r($_SESSION['valid_user']);
		//SELECT
		$query = "SELECT ";
		$query .= "*, ";
	
		//FROM
		$query = substr($query, 0, -2); // Remove last comma.
		$query .= " FROM assignments"; // Begin table selection.
		$query .= " INNER JOIN passenger ON assignments.pid = passenger.pid";

		//WHERE

		$query .= " WHERE assignments.emailAddress = '". $_SESSION["valid_user"] . "'";

		// UNION

		$query .= " UNION";

		//SELECT
		$query .= " SELECT ";
		$query .= "*, ";
	
		//FROM
		$query = substr($query, 0, -2); // Remove last comma.
		$query .= " FROM favorites"; // Begin table selection.
		$query .= " INNER JOIN passenger ON favorites.pid = passenger.pid";

		//WHERE

		$query .= " WHERE favorites.emailAddress = '". $_SESSION["valid_user"] . "'";
		//SUBMIT
		//echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		$result = db_query($query); // Send off query to msqli.

		if (!$result) { 
					die("Bad query! Please mark mercifully."); 
				}

				// Print table.
				echo '<table class="logout-table">';
				echo '	<thead>';
				echo '		<tr>';
				echo '		<th class="right-bar"><strong>Name</strong></th>';
				echo '		<th><strong>Fate</strong></th>';
				echo '		</tr>';
				echo '	</thead>';

				// echo '<tr id="result"></tr>';
			
				$i = 0;
				while ($row = $result->fetch_assoc()) { // Get associative array row by row.
					echo '<tr>';
					echo '<td  class="right-bar" id="passengerName">'.$row['name'];
					if ($i == 0) echo ' (You)';
					echo '</td>';
					
					if ($row['survived'] == 1) {
					echo '<td id="survived">';
						echo '>Survived';
						if (empty($row['body'])) {
							echo ' — raft # unknown.';
						} else {
							echo ' — raft # '.$row['raft'].'.';
						}
					} else {
					echo '<td class="redout" id="survived">';
						echo 'Killed';
						if (empty($row['body'])) {
							echo ' — no body recovered.';
						} else {
							echo ' — identified as body '.$row['body'].'.';
						}
					}
					echo '</td>';
					echo '	</tr>';
					$i += 1;
				}
			
				echo '	</table>';	
		 
	echo '<form action="logout.php" method="post">';
	echo '<p><input class="logout-button" type="submit" name="logout" value="Logout"></p>';
	echo '</form>';
	}
?>
		</div>
</div>
		
