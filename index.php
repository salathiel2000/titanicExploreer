<?php 
	
	if (!empty(($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) {
		header("Location: http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		exit();
	}

	// Includes <html> and <body>. Also includes navigation bar.
	include("includes/header.php"); 
	require_once("includes/db_functions.php"); 
	require_once("includes/functions.php");
	if (!empty($_SESSION['valid_user'])) {

    db_connect(); 

    //suquery that returns the user's class
    $subquery = "SELECT userClass"; 
    $subquery .= " FROM member"; 
    $subquery .= " WHERE emailAddress = \"".$_SESSION['valid_user']."\"";

    //query to recommend
    $query =    "SELECT passenger.pid, name, homeDest, class, cabinNumber"; 
    $query .=   " FROM passenger"; // Begin table selection.
    $query .=   " INNER JOIN ticket ON passenger.pid = ticket.pid";
    $query .=   " INNER JOIN cabin ON passenger.pid = cabin.pid";
    $query .=   " WHERE class IN ($subquery)"; 
    $query .=   " ORDER BY RAND()"; 
    $query .=   " LIMIT 10"; 

    if(percentChance(5)){
        $query  =   "SELECT passenger.pid, name, homeDest, class, cabinNumber"; 
        $query .=   " FROM passenger"; // Begin table selection.
        $query .=   " INNER JOIN ticket ON passenger.pid = ticket.pid";
        $query .=   " INNER JOIN cabin ON passenger.pid = cabin.pid";
        $query .=   " ORDER BY RAND()"; 
        $query .=   " LIMIT 10"; 
    }

    $result = db_query($query); 

    $date_arr = array("April 10th, 1912", "April 10th, 1912", "April 11th, 1912");
    $message_arr = array("Southampton", "Cherbourg", "Queenstown"); 

    ?>

    <div id="lobbyParent">
        <div id="time">
            <h1>It’s <?php date_default_timezone_set("America/Vancouver"); echo date("h:ia").", ";
                $randomVal = rand(0, 2); 
                echo $date_arr[$randomVal]; 
            ?>
            </h1>
            <p>We’ll be leaving 
            <?php
                if ($randomVal == 0) echo $message_arr[0]; 
                if ($randomVal == 1) echo $message_arr[1]; 
                if ($randomVal == 2) echo $message_arr[2]; 
            ?>
            soon. In the meantime get acquainted with others on the ship.</p>
        </div>
        <div id="lobby">
        <div id="resulting-ticket"></div>
        
    <?php
    while($row = $result->fetch_assoc()){
        $name = preg_split('/[,\.]+/', $row['name']);
        $fName = preg_split('/\s/', $name[2]); 
        echo "<div class=\"lobbyNames\">";
        echo "  <p class=\"individualName\">".$fName[1]."</p>"; 
        echo "  <a class=\"individualLink\" href=\"#\" id=".$row['pid']."\">More Info</a>"; 
        echo "</div>";
    }

    ?>

        </div>
    </div>

<?php
	} 
	else
	{
?>

<div id="crew">
    <h1>WELCOME ABOARD</h1>
    <h2>Let's meet the crew</h2>
    <div id="crewImg">
        <div class="crewImgChild">
            <img src="includes/assets/johnSmith_framed.png">
            <h3>CAPTAIN</h3>
            <h4>John Edward Smith</h4>
        </div>
        <div class="crewImgChild">
            <img src="includes/assets/henryTingleWilde_framed.png">
            <h3>CHIEF FIRST OFFICER</h3>
            <h4>Henry Tingle Wilde</h4>
        </div>
        <div class="crewImgChild">
            <img src="includes/assets/williamMurdoch_framed.png">
            <h3>FIRST OFFICER</h3>
            <h4>William McMaster Murdoch</h4>
        </div>
    </div>
</div>


<?php
		db_connect(); // Quick connect function.
//SELECT
		$query = "SELECT ";
		$query .= "*, ";
//FROM
		$query = substr($query, 0, -2); // Remove last comma.
		$query .= " FROM assignments"; // Begin table selection.
		$query .= " INNER JOIN passenger ON assignments.pid = passenger.pid";
		$query .= " INNER JOIN member ON assignments.emailAddress = member.emailAddress";
		$query .= " INNER JOIN (SELECT max(creationDate) as recentDate, emailAddress FROM member GROUP BY emailAddress LIMIT 3) Recents ON member.creationDate = Recents.recentDate AND member.emailAddress = Recents.emailAddress ";
//WHERE
		$query .= " ORDER BY member.creationDate DESC ";
//SUBMIT
		$result = db_query($query); // Send off query to msqli.

		if (!$result) {
				die("Bad query! Please mark mercifully."); 
			}
	}

	// Includes minimal header. Closes </body>, and closes </html>.
	include("includes/footer.php"); 
?>