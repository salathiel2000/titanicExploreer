<?php
    include("includes/header.php");
    require_once("includes/db_functions.php"); 
    db_connect(); 


    if(isset($_SESSION['valid_user'])){
        $email = $_SESSION['valid_user']; 
    }

    $query = "SELECT favorites.pid, name, class, cabinNumber";
    $query .= " FROM favorites"; 
    $query .= " INNER JOIN passenger ON favorites.pid = passenger.pid"; 
    $query .= " INNER JOIN ticket ON passenger.pid = ticket.pid"; 
    $query .= " INNER JOIN cabin ON ticket.pid = cabin.pid"; 
    $query .= " WHERE emailAddress = ?"; 

    $stmt = $connection->prepare($query); 
    $stmt->bind_param('s', $email); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
?>
<div id="addressbook">
<div id="resulting-ticket"></div>

<div class="ab">
	<h1>Address Book</h1>
	<table class="ab-table">
		<thead>
			<tr>
				<th class="right-bar">Name</th>
				<th>Cabin Number</th>
				<th>Class</th>
			</tr>
		</thead>

		<?php
		while($row = $result->fetch_assoc()){
			echo '<tr class="row-listener" id="'.$row['pid'].'">';
			echo    '<td class="right-bar">'.$row['name'].'</td>';
			echo    '<td>'.$row['cabinNumber'].'</td>';
			echo    '<td>'.$row['class'].'</td>';
			echo '</tr>'; 
		}

		$stmt->close();
		$connection->close(); 

		?>

	</table>
</div>
</div>