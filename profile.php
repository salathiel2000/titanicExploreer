<?php 	

	if (!empty(($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) {
		header("Location: http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		exit();
	}
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php");
	require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");
	echo '<div id="result">';
	include("includes/displayProfile.php");
	echo '</div>';
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>