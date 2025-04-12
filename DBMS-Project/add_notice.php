<?php
// add_notice.php
session_start();
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert notice into the database
    $stmt = $pdo->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    echo "<p style='color: green;'>Notice added successfully!</p>";
}
?>

<!-- Notice Submission Form -->
<form action="add_notice.php" method="post" style="margin: 30px auto; width: 50%; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
    <label for="title" style="display: block; margin-bottom: 8px; font-weight: bold;">Notice Title:</label>
    <input type="text" id="title" name="title" required style="width: 100%; padding: 8px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px;">

    <label for="content" style="display: block; margin-bottom: 8px; font-weight: bold;">Notice Content:</label>
    <textarea id="content" name="content" rows="5" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>

    <button type="submit" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Add Notice</button>
</form>
