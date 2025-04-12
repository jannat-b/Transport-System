<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $route_id = $_POST['route_id'];
    $fare_type = $_POST['payment_type'];
    $payment_method = $_POST['payment_method'];

    // Fetch selected route details
    $sql = "SELECT * FROM routes WHERE route_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $route_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $route = $result->fetch_assoc();

    if (!$route) {
        echo "<script>alert('Invalid Route!'); window.location='add_route.php';</script>";
        exit();
    }

    // Get the fare amount based on user selection
    $amount = ($fare_type == "semester") ? $route['semester_fare'] : $route['oneway_fare'];

    // Store payment details in the database
    $sql = "INSERT INTO payments (username, route_id, fare_type, amount, payment_method, payment_date) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisds", $username, $route_id, $fare_type, $amount, $payment_method);

    if ($stmt->execute()) {
        echo "<script>alert('Payment Successful!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
