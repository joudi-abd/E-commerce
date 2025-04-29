<?php 
    $host ='localhost';
    $user ='root';
    $password ='';
    $db='db_ecommerce';
    $conn = mysqli_connect($host,$user,$password,$db);
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully";
?>