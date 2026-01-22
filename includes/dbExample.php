<?php
$host = "localhost";
$user = "your_db_user";
$password = "your_db_password";
$dbname   = "sahara_db";


$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
