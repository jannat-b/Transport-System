<?php
$host = 'localhost';
$db = 'transport_system';
$user = 'root'; // or your MySQL username
$pass = '';     // or your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

require 'connection.php'; // Database connection

$stmt = $pdo->query("SELECT name, email, phone,department,batch FROM users");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<div class="bg-blur"></div>
    <div class="container">
        <h2>User Information</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['department']) ?></td>
                    <td><?= htmlspecialchars($user['batch']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 30px;">
            <a href="admin_panel.php" style="padding: 10px 20px; background-color: #2563eb; color: white; border-radius: 8px; text-decoration: none;">⬅️ Back to Admin_Panel</a>
        </div>
    </div>
</body>
</html>
