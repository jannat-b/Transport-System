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

// manage_routes.php

// Include the database connection file
require_once 'connection.php';

// Initialize variables
$route_id = $route_name = $semester_fare = $oneway_fare = "";
$update = false;

// Handle form submission for adding and updating routes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $route_name = $_POST['route_name'];
    $semester_fare = $_POST['semester_fare'];
    $oneway_fare = $_POST['oneway_fare'];

    if (isset($_POST['route_id']) && !empty($_POST['route_id'])) {
        // Update existing route
        $route_id = $_POST['route_id'];
        $stmt = $pdo->prepare("UPDATE routes SET route_name = ?, semester_fare = ?, oneway_fare = ? WHERE route_id = ?");
        $stmt->execute([$route_name, $semester_fare, $oneway_fare, $route_id]);
    } else {
        // Add new route
        $stmt = $pdo->prepare("INSERT INTO routes (route_name, semester_fare, oneway_fare) VALUES (?, ?, ?)");
        $stmt->execute([$route_name, $semester_fare, $oneway_fare]);
    }
    // Redirect to avoid form resubmission
    header("Location: manage_routes.php");
    exit();
}

// Handle edit request
if (isset($_GET['edit'])) {
    $route_id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM routes WHERE route_id = ?");
    $stmt->execute([$route_id]);
    $route = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($route) {
        $route_name = $route['route_name'];
        $semester_fare = $route['semester_fare'];
        $oneway_fare = $route['oneway_fare'];
        $update = true;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $route_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM routes WHERE route_id = ?");
    $stmt->execute([$route_id]);
    // Redirect to avoid accidental deletions on page refresh
    header("Location: manage_routes.php");
    exit();
}

// Fetch all routes for display
$stmt = $pdo->query("SELECT * FROM routes ORDER BY route_name ASC");
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Routes</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="bg-blur"></div>
     <div style="max-width: 70%; margin: 0px auto; background:white; padding:50px; margin-top:30px; border-radius:10px;">
    <h2><?php echo $update ? 'Edit Route' : 'Add New Route'; ?></h2>
   
    <form action="manage_routes.php" method="post">
        <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
        <label for="route_name">Route Name:</label>
        <input type="text" id="route_name" name="route_name" value="<?php echo htmlspecialchars($route_name); ?>" required>

        <label for="semester_fare">Semester Fare:</label>
        <input type="number" step="0.01" id="semester_fare" name="semester_fare" value="<?php echo htmlspecialchars($semester_fare); ?>" required>

        <label for="oneway_fare">Oneway Fare:</label>
        <input type="number" step="0.01" id="oneway_fare" name="oneway_fare" value="<?php echo htmlspecialchars($oneway_fare); ?>" required>

        <button type="submit"><?php echo $update ? 'Update Route' : 'Add Route'; ?></button>
    </form>

    <h2>Existing Routes</h2>
    <table>
        <thead>
            <tr>
                <th>Route Name</th>
                <th>Semester Fare</th>
                <th>Oneway Fare</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routes as $route): ?>
            <tr>
                <td><?php echo htmlspecialchars($route['route_name']); ?></td>
                <td><?php echo htmlspecialchars($route['semester_fare']); ?></td>
                <td><?php echo htmlspecialchars($route['oneway_fare']); ?></td>
                <td>
                    <a href="manage_routes.php?edit=<?php echo $route['route_id']; ?>">Edit</a>
                    <a href="manage_routes.php?delete=<?php echo $route['route_id']; ?>" onclick="return confirm('Are you sure you want to delete this route?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <div style="text-align: center; margin-top: 30px;">
            <a href="admin_panel.php" style="padding: 10px 20px; background-color: #2563eb; color: white; border-radius: 8px; text-decoration: none;">⬅️ Back to Admin_Panel</a>
        </div>


    </table>
    </div>
</body>
</html>
