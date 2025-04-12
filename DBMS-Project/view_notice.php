<?php
// view_notices.php

// Include database connection
require_once 'connection.php';
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


// Fetch all notices from the database
$stmt = $pdo->query("SELECT title, content, created_at FROM notices ORDER BY created_at DESC");
$notices = $stmt->fetchAll();
?>

<link rel="stylesheet" href="CSS/styles.css">
<div class="bg-blur"></div>

<div style="margin: 30px auto; width: 50%; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; height:60%;">
    <h2 style="text-align: center; margin-top:20px; color:red;">Notice</h2>
    <?php foreach ($notices as $notice): ?>
        <div style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <h3 style="margin-bottom: 5px; margin-top:20px"><?php echo htmlspecialchars($notice['title']); ?></h3>
            <p style="margin-bottom: 5px; margin-top:20px;"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
            <small style="color: #666;">Posted on: <?php echo date('F j, Y, g:i a', strtotime($notice['created_at'])); ?></small>
        </div>

    <?php endforeach; ?>

</div>
