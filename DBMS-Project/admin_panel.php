<?php
session_start();
// Set up PDO connection
$host = 'localhost';
$dbname = 'transport_system';
$username = 'root';  // replace if different
$password = '';      // replace if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}




require 'connection.php'; // Database connection
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="manage_routes.php">Manage Routes</a></li>
            <li><a href="manage_schedule.php">Manage Schedules</a></li>
            <li><a href="view_users.php">View Users</a></li>
            <li><a href="add_notice.php">Post a Notice</a></li>
            <li><a href="admin.php">Logout</a></li>
        </ul>
    </div>
 
</body>
</html>
