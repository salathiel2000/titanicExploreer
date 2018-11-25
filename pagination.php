<?php
    //which page am I currently on
    $currPage = "";

    $query = "SELECT COUNT(*) FROM passenger";
    $result = mysqli_query($connection, $query); 
    //store amount of rows in passengers tabl
    $row = mysqli_fetch_array($result); 
    $total = $row[0];

    //amount of rows to return
    $limit = 50; 

    //how many pages will there be
    $pages = ceil($total / $limit);

    // echo "<br>Total:".$total."<br>";  
    // echo "Number of pages:".$pages."<br>"; 
    // echo "Current Page:".$currPage; 
?>