<?php 
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
	require_once("includes/db_functions.php"); 

	db_connect(); 

	$fName = $lName = $email = $password1 = $password2 = $age = $income = $gender = $class = ""; 
	$error = array(); 

	if(isset($_POST['submit'])){
		//validate inputs and assign values to variables, otherwise push an error message to the 
		//error array

		//fName validation
        if(!empty($_POST['fName'])){
            $fName = trim($_POST['fName']);
            if(!validate_string($fName)){
                $error["fName"] = "Please enter a valid name"; 
            }
        } else {
            $error["fName"] = "Please enter a value"; 
		}
		
		//lName validation
        if(!empty($_POST['lName'])){
            $lName = trim($_POST['lName']); 
            if(!validate_string($lName)){
                $error["lName"] = "Please enter a valid name"; 
            }
        } else {
            $error["lName"] = "Please enter a value"; 
        }

		//email validation
        if(!empty($_POST['email'])){
            $email = trim($_POST['email']);
            if(!validate_email($email)){
                $error["email"] = "Please enter a valid email address"; 
            } 
        } else {
                $error["email"] = "Please enter a value"; 
        }

		//password1 validation
        if(!empty($_POST['password1'])){
            $password1 = $_POST['password1']; 
        } else {
            $error["password1"] = "Please enter a value"; 
        }

		//password2 validation
        if(!empty($_POST['password2'])){
            $password2 = $_POST['password2'];
        } else {
            $error["password2"] = "Please enter a value";
        }

		//check if password1 and 2 match
        if($password1 != $password2){
            $error["passwords_not_match"] = "Passwords do not match"; 
		}

		//age validation
		if(!empty($_POST['age'])){
			$age = (int) $_POST['age']; 
			if(!is_numeric($age) || $age > 150){
				$error["age"] = "Please enter a valid age"; 
			}
		}

		//income validation 
		if(!empty($_POST['income'])){
			$income = (int) $_POST['income']; 
			if(!is_numeric($income)){
				$error["income"] = "Please enter a valid value"; 
			}
		}

		$gender = $_POST['gender']; 
    
		$class = 3; // Third class.
		if ($income > 12000) {
			$class -= 1; // Second class.
			if ($income > 40000) {
				$class -= 1; // First class.
			}
		}

	}

    if((count($error) == 0) && isset($_POST['submit'])){

		$query = "SELECT";
		$query .= " passenger.pid";
		$query .= " FROM passenger"; // Begin table selection.
		$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
		$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
		$query .= " AND ticket.class = '". $class ."'";
		$query .= " AND (ABS(passenger.age - ". $age .") < 5)";
		$query .= " AND passenger.gender = '".$gender."'";
		$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
		$query .= " ORDER BY passenger.pid LIMIT 1";

		echo "<p>SQL Query:<br><div class=\"code-block\"><code>".$query."</code></div></p>"; // Print SQL statement in plain text.
		
		$result = db_query($query); // Send off query to msqli.
        if (!$result) { 
			$query = "SELECT";
			$query .= " passenger.pid";
			$query .= " FROM passenger"; // Begin table selection.
			$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
			$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
			$query .= " AND ticket.class = '". $class ."'";
			$query .= " AND passenger.gender = '".$gender."'";
			$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
			$query .= " ORDER BY passenger.pid LIMIT 1";
			$result = db_query($query); // Send off query to msqli.
			if (!$result) {
				$query = "SELECT";
				$query .= " passenger.pid";
				$query .= " FROM passenger"; // Begin table selection.
				$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
				$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
				$query .= " AND ticket.class = '". $class ."'";
				$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
				$query .= " ORDER BY passenger.pid LIMIT 1";
				$result = db_query($query); // Send off query to msqli.
				if (!$result) {
				$query = "SELECT";
				$query .= " passenger.pid";
				$query .= " FROM passenger"; // Begin table selection.
				$query .= " INNER JOIN ticket ON passenger.pid = ticket.pid";	
				$query .= " WHERE NOT EXISTS (SELECT * FROM assignments WHERE assignments.pid = passenger.pid)";
				$query .= " AND passenger.pid >= RAND() * ( SELECT MAX(passenger.pid ) FROM passenger )";
				$query .= " ORDER BY passenger.pid LIMIT 1";
					$result = db_query($query); // Send off query to msqli.
					if (!$result) {
						die("Error: All passengers are assigned!");
					}
				}
			}
		}
		$row = $result->fetch_assoc();
		mysqli_free_result($result);
		$insertQuery = "INSERT INTO assignments (emailAddress, pid) ";
		$insertQuery .= "VALUES (?, ?)";
		$stmt = $connection->prepare($insertQuery); 
        $stmt->bind_param('si', $email, $row['pid']);
		$stmt->execute();
		$stmt->close(); 

        $insertQuery = "INSERT INTO member (fName, lName, emailAddress, password, userAge, userGender, annualIncome, creationDate, userClass) ";



        $insertQuery .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

		$creationDate = date("Y-m-d H:i:s");

        $stmt = $connection->prepare($insertQuery); 
        $stmt->bind_param('ssssisisi', $fName, $lName, $email, $encrypted_pass, $age, $gender, $income, $creationDate, $class); 
		$encrypted_pass = password_hash($password1, PASSWORD_DEFAULT);

        if($stmt->execute()){
           $_SESSION['valid_user'] = $email; 
             $callback_url = "/titanicExplorer/index.php"; 
             if(isset($_SESSION['callback_url'])){
                 $callback_url = $_SESSION['callback_url']; 
             }
             header("Location: http://".$_SERVER['HTTP_HOST'].$callback_url); 
         } else {
             echo "Error: </br>".$connection->error; 
         }

		$stmt->close(); 
		$connection->close(); 
	}    	
	
	// print_r($error); 
?>

<body>

<div id="register">
	<div class="formImg">
		<img src="includes/assets/titanicPostcard_bw.jpg">
	</div>
	<div id="registerForm">
		<img src="includes/assets/topDecoration.png">
		<h1>REGISTER</h1>

		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="row">
				<div class="stacked half">
					<label for="fName">First Name<span class="mandatory">*</span></label>
					<input type="text" name="fName" value="<?php echo $fName?>">
					<p class="error"><?php if(!empty($error['fName'])) echo $error['fName'] ?>
				</div>

				<div class="stacked half">
					<label for="lName">Last Name<span class="mandatory">*</span></label>
					<input type="text" name="lName" value="<?php echo $lName?>">
					<p class="error"><?php if(!empty($error['lName'])) echo $error['lName'] ?>
				</div>
			</div>

			<div class="stacked row">
				<label for="email">Email<span class="mandatory">*</span></label>
				<input type="email" name="email" value="<?php echo $email?>">
				<p class="error"><?php if(!empty($error['email'])) echo $error['email'] ?>
			</div>

			<div class="row">
				<div class="stacked half">
					<label for="password1">Enter Password<span class="mandatory">*</span></label>
					<input type="password" name="password1">
					<p class="error"><?php if(!empty($error['password1'])) echo $error['password1'] ?>
					<p class="error"><?php if(!empty($error['passwords_not_match'])) echo $error['passwords_not_match'] ?>
				</div>

				<div class="stacked half">
					<label for="password2">Re-enter Password<span class="mandatory">*</span></label>
					<input type="password" name="password2">
					<p class="error"><?php if(!empty($error['password2'])) echo $error['password2'] ?>
				</div>
			</div>

			<div class="row">
				<div class="stacked third">
					<label for="age">Age</label>
					<input type="number" name="age" min="0" max="150" value="<?php echo $age?>">
					<p class="error"><?php if(!empty($error['age'])) echo $error['age'] ?>
				</div>

				<div class="stacked third">
					<label for="income">Annual Income</label>
					<input type="number" name="income" placeholder="$" value="<?php echo $income?>">
					<p class="error"><?php if(!empty($error['income'])) echo $error['income'] ?>
				</div>

				<fieldset class="third">
					<legend>Gender</legend>
					<div id="gender">
						<input type="radio" name="gender" <?php if(isset($gender) && $gender == "male") { echo "checked";}?> value="male" checked><span class="radioLabel">Male</span><br>
						<input id="rightGender" type="radio" name="gender" <?php if(isset($gender) && $gender == "female") { echo "checked";}?> value="female"><span class="radioLabel">Female</span><br>
					</div>
				</fieldset>
			</div>

			<input id="registerSubmit" type="submit" name="submit" value="SUBMIT">
		</form>
		<p class="bottom">Already registered</p>
		<a class="bottom" href="login.php">Log In</a>
	</div>

</div>
</body>

<?php 
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>