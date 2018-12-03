
<?php
	session_start();
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
					<p>EA</p>
				</div>
				<h1>Elisabeth Allen</h1>
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
		<a href="addressBook.php">Address Book</a>
		<?php if (isset($_SESSION['valid_user'])) {
			echo '<a href="profile.php">My Profile</a>';
			echo '<a href="logout.php">Logout</a>';
			} else {
			echo '<a href="login.php">Login</a>';
			}
		?>
	</div>
</div>
	<!-- PHP content created after this point. -->
