<?php
$host = "localhost";
$user = "root"; // Default XAMPP MySQL user
$pass = ""; // No password in XAMPP by default
$dbname = "transport_system";

// Connect to MySQL

$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
