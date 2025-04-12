<?php
session_start();
include 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

// Fetch routes from database
$sql = "SELECT * FROM routes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.2/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/showSchedule.css">
</head>
<body>

    <div class="container mx-auto my-8 p-4">
        <div class="container1">
        <div class="header"><h2 class="text-2xl font-bold text-center mb-6">Available Bus Routes</h2></div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300 shadow-lg">
                <tr class="bg-gray-200 text-gray-700">
                    <th class="border p-3">Route Name</th>
                    <th class="border p-3">Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr class="text-center bg-white hover:bg-gray-100 transition">
                    <td class="border p-3"><?php echo htmlspecialchars($row['route_name']); ?></td>
                    <td class="border p-3">
                        <button onclick="fetchSchedule(<?php echo $row['route_id']; ?>, '<?php echo $row['route_name']; ?>')"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Show Schedule
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </table>
        
            <a href="dashboard.php" class="back-button">Back to Dashboard</a>
        </div>
        </div>
    </div>

    <!-- Modal for Schedule -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-black p-6 rounded-lg shadow-lg w-4/5 max-w-2xl">
            <div class="table_header"><h2 id="modalTitle" class="text-xl font-bold mb-4"></h2></div>
            <div class="overflow-x-auto">
                <div class="table_header"><h3 class="text-lg font-semibold mb-2">Location to DSC</h3></div>
                <table class="pop_table w-full border border-gray-300">
                    <tr class="bg-gray-200">
                        <th class="border p-2">Bus Name</th>
                        <th class="border p-2">Time</th>
                    </tr>
                    <tbody id="locationToDSC"></tbody>
                </table>
            </div>
            <div class="overflow-x-auto mt-4">
                <div class="table_header"><h3 class="text-lg font-semibold mb-2">DSC to Location</h3></div>
                <table class=" pop_table w-full border border-gray-300">
                    <tr class="bg-gray-200">
                        <th class="border p-2">Bus Name</th>
                        <th class="border p-2">Time</th>
                    </tr>
                    <tbody id="DSCToLocation"></tbody>
                </table>
            </div>

            <div class="flex justify-end mt-4">
                <button onclick="closeModal()" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function fetchSchedule(routeId, routeName) {
            document.getElementById('modalTitle').innerText = `Schedule for ${routeName}`;

            fetch(`fetch_schedule.php?route_id=${routeId}`)
                .then(response => response.json())
                .then(data => {
                    let locationToDSC = document.getElementById('locationToDSC');
                    let DSCToLocation = document.getElementById('DSCToLocation');

                    locationToDSC.innerHTML = "";
                    DSCToLocation.innerHTML = "";

                    data.locationToDSC.forEach(schedule => {
                        locationToDSC.innerHTML += `
                            <tr>
                                <td class="border p-2">${schedule.bus_name}</td>
                                <td class="border p-2">${schedule.time}</td>
                            </tr>
                        `;
                    });

                    data.DSCToLocation.forEach(schedule => {
                        DSCToLocation.innerHTML += `
                            <tr>
                                <td class="border p-2">${schedule.bus_name}</td>
                                <td class="border p-2">${schedule.time}</td>
                            </tr>
                        `;
                    });

                    document.getElementById('scheduleModal').classList.remove("hidden");
                });
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add("hidden");
        }
    </script>

</body>
</html>
