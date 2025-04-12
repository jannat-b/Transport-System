<?php
session_start();
include 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// Fetch available routes
$sql = "SELECT * FROM routes";
$result = $conn->query($sql);
?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.2/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/addRoute.css">
    <!-- Navigation Bar -->
     <div class="bg-blur"></div>
    <nav class="top-nav">

            <h1>Add Your Routes</h1>
           
        <div class="nav-right">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
<!-- Routes Table -->
<div class="tableBox">
<div class="header">
<h2 class="text-lg font-bold mb-4">Available Bus Routes</h2>
</div>

<table class="w-full border-collapse border border-gray-300">
    <tr class="bg-gray-200">
        <th class="border p-2">Route Name</th>
        <th class="border p-2">Semester Price</th>
        <th class="border p-2">One-Way Price</th>
        <th class="border p-2">Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr class="text-center">
        <td class="border p-2"><?php echo htmlspecialchars($row['route_name']); ?></td>
        <td class="border p-2">$<?php echo htmlspecialchars($row['semester_fare']); ?></td>
        <td class="border p-2">$<?php echo htmlspecialchars($row['oneway_fare']); ?></td>
        <td class="border p-2">
            <button onclick="showModal('routesModal', 
                                       '<?php echo $row['route_id']; ?>', 
                                       '<?php echo $row['route_name']; ?>', 
                                       '<?php echo $row['semester_fare']; ?>', 
                                       '<?php echo $row['oneway_fare']; ?>')" 
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Select Route
            </button>
        </td>
    </tr>
    <?php } ?>
</table>
<a href="dashboard.php" class="back-button">Back to Dashboard</a>
</div>

<!-- Modal for Route Selection -->
<div id="routesModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Choose Payment Method</h2>
        <form id="routeForm" action="process_route.php" method="POST">
            <input type="hidden" name="username" value="<?php echo $username; ?>">
            <input type="hidden" id="routeId" name="route_id">  <!-- Modified to store route_id -->

            <label class="block mb-2">Selected Route:</label>
            <input type="text" id="routeName" name="route_name" class="w-full p-2 border rounded mb-4" readonly>

            <label class="block mb-2">Payment Type:</label>
            <select name="payment_type" class="w-full p-2 border rounded mb-4" required>
                <option value="semester">Semester Payment - $<span id="semPrice"></span></option>
                <option value="oneway">One-Way Ticket - $<span id="oneWayPrice"></span></option>
            </select>

            <label class="block mb-2">Payment Method:</label>
            <select name="payment_method" class="w-full p-2 border rounded mb-4" required>
                <option value="onecard">One-Card Payment</option>
                <option value="bkash">bKash Payment</option>
            </select>

            <div class="flex justify-between">
                <button type="button" onclick="closeModal('routesModal')" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Confirm & Pay
                </button>
            </div>
        </form>
    </div>
    
</div>

<script>
    function showModal(modalId, routeId, routeName, semPrice, oneWayPrice) {
        document.getElementById("routeId").value = routeId; // Store route_id
        document.getElementById("routeName").value = routeName;
        document.getElementById("semPrice").innerText = semPrice;
        document.getElementById("oneWayPrice").innerText = oneWayPrice;
        document.getElementById(modalId).classList.remove("hidden");
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add("hidden");
    }
</script>
