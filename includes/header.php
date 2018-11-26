<?php
	session_start();
?>

<html>

	<head>
		<title>TitanicExplorer</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" media="screen" href="includes/main.css"/>
	</head>

	<body>

	<!-- Navigation bar. -->
	<nav>
		<a href="index.php"><h1>TitanicExplorer</h1></a>
		<ul>
			<a href="index.php"><li>Home</li></a>
			<a href="explore.php"><li>Explore</li></a>
			<!-- TODO Make profile button appear if logged in. -->
			<?php 
				if (isset($_SESSION['valid_user'])) {
					echo '<a href="profile.php"><li class="bound-right">My Profile</li></a>';
					echo '<a href="logout.php"><li class="bound-right">Logout</li></a>';
				} else {
					echo '<a href="login.php"><li class="bound-right">Login</li></a>';
				}
			?>
		</ul>
	</nav>

	<!-- PHP content created after this point. -->
