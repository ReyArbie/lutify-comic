<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "comic_1";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("tidak terkoneksi: " . $conn->connect_error);
}


?>
