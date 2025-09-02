<?php
$servername = "localhost";
$username   = "root";
$password   = "vertrigo";
$dbase      = "bteslife";

$conn = mysqli_connect($servername, $username, $password, $dbase);

include_once 'logger.php';

if (!$conn) {
    log_event("Database connection FAILED: " . mysqli_connect_error());
    die("Connection failed: " . mysqli_connect_error());
} else {
    log_event("Database connection SUCCESS.");
}

mysqli_set_charset($conn, 'utf8mb4');
?>
