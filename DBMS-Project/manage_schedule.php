
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
?>
<div><bg-blur></div>
<form action="manage_schedule.php" method="get" style="margin: 40px; margin-right:10px; text-align: center; background-color: #f8f9fa; padding: 20px; width: fit-content; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);; height:40%">
    <label for="direction" style="font-weight: bold; margin-right: 10px; font-size: 16px;">Select Schedule Direction:</label>
    <select id="direction" name="direction" required style="padding: 6px 10px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px; margin-right: 10px;">
        <option value="">--Choose Direction--</option>
        <option value="to_location">DSC to Location</option>
        <option value="to_DSC">Location to DSC</option>
    </select>
    <button type="submit" style="padding: 7px 18px; background-color: #3490dc; color: white; font-size: 15px; border: none; border-radius: 5px; cursor: pointer;">
        Manage Schedule
    </button>
</form>

<?php
// Now handle the GET direction after the form
if (isset($_GET['direction'])) {
    $direction = $_GET['direction'];
    $table = ($direction == 'to_location') ? 'DSC_to_location' : 'location_to_DSC';
} else {
    exit(); // Stop here if no direction is selected
}
// manage_schedule.php

// Include the database connection file
require_once 'connection.php';

// Initialize variables
$schedule_id = $route_id = $bus_name = $time = "";
$update = false;
$table = "";

// Determine the table based on the direction
if (isset($_GET['direction']) && ($_GET['direction'] == 'to_location' || $_GET['direction'] == 'to_DSC')) {
    $direction = $_GET['direction'];
    $table = ($direction == 'to_location') ? 'DSC_to_location' : 'location_to_DSC';
} else {
    die("Invalid direction specified.");
}

// Handle form submission for adding and updating schedules
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $route_id = $_POST['route_id'];
    $bus_name = $_POST['bus_name'];
    $time = $_POST['time'];

    if (isset($_POST['schedule_id']) && !empty($_POST['schedule_id'])) {
        // Update existing schedule
        $schedule_id = $_POST['schedule_id'];
        $stmt = $pdo->prepare("UPDATE $table SET route_id = ?, bus_name = ?, time = ? WHERE id = ?");
        $stmt->execute([$route_id, $bus_name, $time, $schedule_id]);
    } else {
        // Add new schedule
        $stmt = $pdo->prepare("INSERT INTO $table (route_id, bus_name, time) VALUES (?, ?, ?)");
        $stmt->execute([$route_id, $bus_name, $time]);
    }
    // Redirect to avoid form resubmission
    header("Location: manage_schedule.php?direction=$direction");
    exit();
}

// Handle edit request
if (isset($_GET['edit'])) {
    $schedule_id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$schedule_id]);
    $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($schedule) {
        $route_id = $schedule['route_id'];
        $bus_name = $schedule['bus_name'];
        $time = $schedule['time'];
        $update = true;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $schedule_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$schedule_id]);
    // Redirect to avoid accidental deletions on page refresh
    header("Location: manage_schedule.php?direction=$direction");
    exit();
}

// Fetch all routes for dropdown
$routes_stmt = $pdo->query("SELECT route_id, route_name FROM routes ORDER BY route_name ASC");
$routes = $routes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all schedules for display
$schedules_stmt = $pdo->query("SELECT s.id, r.route_name, s.bus_name, s.time FROM $table s JOIN routes r ON s.route_id = r.route_id ORDER BY r.route_name ASC, s.time ASC");
$schedules = $schedules_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bus Schedules</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<div class="bg-blur"></div>
<div style="max-width: 70%; margin: 0px auto; background:white; padding:50px; margin-top:30px; border-radius:10px; margin-left:100px;">
    <h2><?php echo $update ? 'Edit Schedule' : 'Add New Schedule'; ?></h2>
    <form action="manage_schedule.php?direction=<?php echo $direction; ?>" method="post">
        <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>">
        
        <label for="route_id">Route:</label>
        <select id="route_id" name="route_id" required>
            <option value="">Select a route</option>
            <?php foreach ($routes as $route): ?>
                <option value="<?php echo $route['route_id']; ?>" <?php echo ($route['route_id'] == $route_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($route['route_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="bus_name">Bus Name:</label>
        <input type="text" id="bus_name" name="bus_name" value="<?php echo htmlspecialchars($bus_name); ?>" required>

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>" required>

        <button type="submit"><?php echo $update ? 'Update Schedule' : 'Add Schedule'; ?></button>
    </form>

    <h2>Existing Schedules</h2>
    <table>
        <thead>
            <tr>
                <th>Route Name</th>
                <th>Bus Name</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($schedules as $schedule): ?>
            <tr>
                <td><?php echo htmlspecialchars($schedule['route_name']); ?></td>
                <td><?php echo htmlspecialchars($schedule['bus_name']); ?></td>
                <td><?php echo htmlspecialchars($schedule['time']); ?></td>
                <td>
                    <a href="manage_schedule.php?direction=<?php echo $direction; ?>&edit=<?php echo $schedule['id']; ?>">Edit</a>
                    <a href="manage_schedule.php?direction=<?php echo $direction; ?>&delete=<?php echo $schedule['id']; ?>" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    <div style="text-align: center; margin-top: 30px;">
        <a href="admin_panel.php" style="padding: 10px 20px; background-color: #2563eb; color: white; border-radius: 8px; text-decoration: none;">⬅️ Back to Dashboard</a>
    </div>
    </table>
            
</div>
    
</body>
</html>
