<?php
include 'connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $department = $_POST['department'];
    $batch = $_POST['batch'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Secure password hashing

    // Insert data into 'users' table
    $sql = "INSERT INTO users (name, student_id, department, batch, phone, email, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $student_id, $department, $batch, $phone, $email, $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Transport System</title>
    <link rel="stylesheet" href="CSS/sign_up.css">
</head>
<body>
<div class="nav-right">
            <a href="index.php" class="text-blue-400 hover:underline">Back to Home Page</a>
</div>
<div class="bg-blur"></div>
    <div class="register-container">
        <h1>Student Registration</h1>
        <div class="formContainerr">
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="text" name="batch" placeholder="Batch" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
           <div class="sign-upButton"> <button type="submit">Sign Up</button></div>
        </form>
    
        <p>Already have an account? <a href="login.php" class="text-blue-400 hover:underline">Log in here</a></p>
    </div>
</div>
</body>
</html>
