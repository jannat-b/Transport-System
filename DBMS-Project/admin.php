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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUser = $_POST['username'];
    $adminPass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->execute([$adminUser, $adminPass]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="login-container">
        <form method="post">
            <h2>Admin Login</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
