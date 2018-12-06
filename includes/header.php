
<?php
	session_start();
	require_once("includes/db_functions.php"); 
	db_connect(); 

	if(isset($_SESSION['valid_user'])){
	$query = "SELECT name FROM passenger INNER JOIN assignments ON passenger.pid = assignments.pid"; 
	$query .= " WHERE assignments.emailAddress = \"".$_SESSION['valid_user']."\"";
	$result = db_query($query);
	while($row = $result->fetch_assoc()){
		$name = $row['name']; 
	}

	$fullName = preg_split("/[\s,\.]+/", $name);
	}
?>

<html>

	<head>
		<title>TitanicExplorer</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" media="screen" href="includes/fonts.css"/>
		<link rel="stylesheet" type="text/css" media="screen" href="includes/main.css"/>
		<script src="includes/jquery-3.3.1.min.js" type="text/javascript"></script>
		<script src="includes/titanic.js" type="text/javascript"></script>
	</head>

	<body>

	<!-- Navigation bar. -->

<div id="headerParent">
	<div id="header">
		<?php if(isset($_SESSION['valid_user'])){ ?>
			<div id="nameHeader">
				<div id="nameIcon">
					<p>
						<?php
							echo substr($fullName[2], 0, 1)."".substr($fullName[0], 0, 1); 
						?>
					</p>
				</div>
				<a id="nameHeader" href="index.php"><h1>
					<?php  
						echo $fullName[2]." ".$fullName[0]; 
					?>
				</h1></a>
			</div>
		<?php } else { ?>
		<div id="nameHeader"></div>
		<?php } ?>
		<div id="menuHeader">
			<img src="includes/assets/menuIcon.svg">
			<a id="openMenu" href="#">MENU</a>
		</div>
	</div>
</div>


<div id="menuOverlay">
	<div id="closeHeader">
		<img src="includes/assets/closeIcon.svg">
		<a id="closeMenu" href="#">CLOSE</a>
	</div>
	<div id="menuOptions">
		<a href="index.php">Home</a>
		<a href="explore.php">Explore</a>
		
		<?php if (isset($_SESSION['valid_user'])) {
			echo '<a href="addressBook.php">Address Book</a>';
			echo '<a href="profile.php">My Profile</a>';
			echo '<a href="logout.php">Logout</a>';
			} else {
			echo '<a href="login.php">Login</a>';
			}
		?>
	</div>
</div>
	<!-- PHP content created after this point. -->
