<?php
session_start();
include 'connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = ($_POST['password']); // Hash password using MD5

    // Correct SQL Query using Prepared Statements
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    
    // Check if statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password); // Bind variables
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: login.php");
            exit();
        }
    } else {
        die("Query preparation failed: " . $conn->error);
    }
}
?>
