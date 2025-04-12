<?php
session_start();
include 'connection.php'; // Include database connection

// Redirect to login if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch the routes the user has paid for
$sql = "SELECT p.payment_id, r.route_name, p.fare_type, p.amount, p.payment_method, p.payment_date
        FROM payments p
        JOIN routes r ON p.route_id = r.route_id
        WHERE p.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Selected Routes</title>
    <link rel="stylesheet" href="CSS/showRoute.css">

</head>
<body>
    <div class="bg-blur"></div>
    <!-- Navigation Bar -->
    <nav class="top-nav">

            <h1>All Your Selected Routes Here</h1>
           
        <div class="nav-right">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    <div class="routes-container">
        <div class="header"><h1 class="text-lg font-bold mb-4">Your Routes</h1></div>
        
        <?php if ($result->num_rows > 0) { ?>
                <div class="bg-blue-100 text-blue-900 p-4 rounded-lg shadow-md text-center mb-4">


            <table>
                <tr>
                    <th>Route Name</th>
                    <th>Fare Type</th>
                    <th>Amount Paid (BDT)</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['route_name']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['fare_type'])); ?></td>
                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['payment_method'])); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>You have not paid for any routes yet.</p>
        <?php } ?>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

</body>
</html>
