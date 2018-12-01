<?php 
	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
	require_once("includes/db_functions.php"); 

	db_connect(); 

	$fName = $lName = $email = $password1 = $password2 = $age = $income = $gender = ""; 
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
		} else {
			$error["age"] = "Please enter a value"; 
		}

		//income validation 
		if(!empty($_POST['income'])){
			$income = (int) $_POST['income']; 
			if(!is_numeric($income)){
				$error["income"] = "Please enter a valid value"; 
			}
		} else {
			$error["income"] = "Please enter a value"; 
		}

		$gender = $_POST['gender']; 
    }

    if((count($error) == 0) && isset($_POST['submit'])){
        
        $insertQuery = "INSERT INTO member (fName, lName, emailAddress, password, userAge, userGender, annualIncome, creationDate) "; 
        $insertQuery .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 

		$creationDate = date("Y-m-d H:i:s");

        $stmt = $connection->prepare($insertQuery); 
        $stmt->bind_param('ssssisis', $fName, $lName, $email, $encrypted_pass, $age, $gender, $income, $creationDate); 
		$encrypted_pass = password_hash($password1, PASSWORD_DEFAULT);
		$stmt->execute(); 

        // if($stmt->execute()){
    //         $_SESSION['loggedin'] = true;
    //         $_SESSION['email'] = $email; 
    //         $callback_url = "/srwells/Assignments/A4/showmodels.php"; 
    //         if(isset($_SESSION['callback_url'])){
    //             $callback_url = $_SESSION['callback_url']; 
    //         }
    //         header("Location: http://".$_SERVER['HTTP_HOST'].$callback_url); 
    //     } else {
    //         echo "Error: </br>".$db->error; 
    //     }

		$stmt->close(); 
		$connection->close(); 
	}    	
	
	print_r($error); 
?>

<body>
<h1>Register</h1>
    
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <div>
            <label for="fName">First Name</label>
            <input type="text" name="fName" value="<?php echo $fName?>">
        </div>

        <div>
            <label for="lName">Last Name</label>
            <input type="text" name="lName" value="<?php echo $lName?>">
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $email?>">
        </div>

        <div>
            <label for="password1">Enter Password</label>
            <input type="password" name="password1">
        </div>

        <div>
            <label for="password2">Re-enter Password</label>
            <input type="password" name="password2">
        </div>

		 <div>
            <label for="age">Age</label>
            <input type="number" name="age" min="0" max="150" value="<?php echo $age?>">
        </div>

		<div>
            <label for="income">Annual Income</label>
            <input type="number" name="income" placeholder="$" value="<?php echo $income?>">
        </div>

		<fieldset>
			<legend>Gender</legend>
			<input type="radio" name="gender" <?php if(isset($gender) && $gender == "male") { echo "checked";}?> value="male" checked> Male<br>
			<input type="radio" name="gender" <?php if(isset($gender) && $gender == "female") { echo "checked";}?> value="female"> Female<br>
		</fieldset>

        <input type="submit" name="submit" value="Submit">
    </form>
</body>

<?php 
	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>