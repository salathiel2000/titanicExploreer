<?php 
	
	
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php");
	require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");

	// Fetch form submission variables if included.
	if (isset($_POST['submit'])) $submit = $_POST['submit'];

	if (isset($_POST['loginEmail'])) $loginEmail = $_POST['loginEmail'];
	if (isset($_POST['loginPassword'])) $loginPassword = $_POST['loginPassword'];

	
	if ($_SERVER["HTTPS"] != "on") {
		header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		exit();
	}

	db_connect(); // Quick connect function.
	$authenticated = "";

	if (!empty($loginEmail) && !empty($loginPassword)) { // Make sure this has already gone through a submission cycle.

		// Establish query. Returns hashed + salted password, so even if the user sees it in plain text, they shouldn't be able to crack it.
		$query = "SELECT password FROM member WHERE emailAddress = ?";
		// Put statement into preparation zone.
		$stmt = $connection->prepare($query);

		// Replace ? with loginEmail.
		$stmt->bind_param('s', $loginEmail);

		// And send the statement off.
		$stmt->execute();

		// Get returned result (hashed+salted PW) and bind it to a PHP variable.
		$stmt->bind_result($hashWord);
		
		// If the result exists, and is correct.
		if ($stmt->fetch()) {
		
			if (password_verify($loginPassword, $hashWord)) {
				// Authenticate the loginEmail.
				$authenticated = $loginEmail;
			}
		} else { // Otherwise...
			// Print error message.
			error("This email and password combination does not exist.");
		}
		// Close statement, because it is out of use.
		$stmt->close();
			
		// Since we can't actually assume the user is authentic, do a check:
		if ($authenticated) {
			// If they are authenticated, give the session a verified email.
			$_SESSION['valid_user'] = $loginEmail;

			// Make a default callback URL in case the user didn't come from another page.
			$callback_url = "/titanicExplorer/index.php";

			// But... if the user came from another page, overwrite the default address.
			if (isset($_SESSION['callback_url'])) {
				$callback_url = $_SESSION['callback_url'];
			}
			// And then go to that address.
			header("Location: http://".$_SERVER['HTTP_HOST'].$callback_url); // Redirect out of HTTPS.
			// Force exit.
			exit();
		} else {
			//error($message); // Print error.
		}
	}

	if (!isset($_SESSION['valid_user'])) { // NOTE: checking for NULL might not work.
?>
<div id="register">
	<div class="formImg">
		<img src="includes/assets/titanicPostcard_bw.jpg">
	</div>
	<div id="registerForm">
		<img src="includes/assets/topDecoration.png">
		<h1>LOGIN</h1>

		<form id="login" action="login.php" method="post">
			<div class="stacked row">
				<label for="loginEmail">Email<span class="mandatory">*</span></label>
				<input type="text" name="loginEmail">
			</div>
			<div class="stacked row">
				<label for="loginPassword">Password<span class="mandatory">*</span></label>
				<input type="password" name="loginPassword">
			</div>
			<input id="registerSubmit" type="submit" name="submit" value="SUBMIT">
		</form>
		<p class="bottom">Don't have an account?</p>
		<a class="bottom" href="register.php">Register</a>
	</div>
</div>

<?php
	}
?>





<?php
	if (!empty($submit)) {
		db_close(); // Also found in db_functions.
	}
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>