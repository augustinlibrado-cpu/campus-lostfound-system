<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "campus_lost_found";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8mb4");
?>