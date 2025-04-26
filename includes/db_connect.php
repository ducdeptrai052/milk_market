<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "milk_market";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

?>