<?php
    include("includes/header.php");
    require_once("includes/db_functions.php"); 
    db_connect(); 


    if(isset($_SESSION['valid_user'])){
        $email = $_SESSION['valid_user']; 
    }

    $query = "SELECT favorites.pid, name, homeDest, class, cabinNumber";
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

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Home/Destination</th>
            <th>Class</th>
            <th>Cabin Number</th>
        </tr>
    </thead>

<?php
    while($row = $result->fetch_assoc()){
        echo "<tr>";
        echo    "<td><a href=\"passenger.php?pid=".$row['pid']."\">".$row['name']."</a></td>";
        echo    "<td>".$row['homeDest']."</td>";
        echo    "<td>".$row['class']."</td>";
        echo    "<td>".$row['cabinNumber']."</td>";
        echo "</tr>"; 
    }

    $stmt->close();
    $connection->close(); 

?>

</table>