<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "lutify_comic";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("tidak terkoneksi: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['login'])) {
    // Kalau belum login, kembalikan ke login.php
    header('Location: login.php');
    exit;
}
?>
