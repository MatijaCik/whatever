<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FitCalorie";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error trying to connect(check your connection): " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
