<?php
session_start();
include 'connection.php'; 

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$_SESSION['name'] = $user['name'];
$_SESSION['student_id'] = $user['student_id'];
$_SESSION['email'] = $user['email'];
$_SESSION['phone'] = $user['phone'];
$_SESSION['department'] = $user['department'];
$_SESSION['batch'] = $user['batch'];

// Fetch user info for sidebar display

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.2/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/dash.css">
    <script>
        function loadPage(page) {
            document.getElementById("content-frame").src = page;
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.dashboard-container');
            sidebar.classList.toggle('active');

            // Adjust content width when sidebar is active
            if (sidebar.classList.contains('active')) {
                content.style.marginLeft = "250px"; // Moves content right when sidebar is open
            } else {
                content.style.marginLeft = "0"; // Moves content back when sidebar is closed
            }
        }
    </script>
</head>
<body>

    <div class="bg-blur"> </div>
        <!-- Navigation Bar -->
        <nav class="top-nav">
            <div class="nav-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <div class="Logo">
           <h1><?php echo $_SESSION['username']?>&apos;s DashBoard </h1>
            </div>
            </div>
        <div class="nav-right">
            <a href="dashboard.php">Home</a>
            <a href="view_notice.php">Notice</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <!-- Sidebar -->
<!-- Sidebar -->
<div class="sidebar">
<aside class="w-64 bg-gradient-to-r from-blue-600 to-blue-800 text-white h-screen fixed left-0 top-0 p-6 shadow-lg">
    <div class="text-center mb-6">
        <div class="w-20 h-20 mx-auto rounded-full bg-white flex items-center justify-center text-blue-600 text-3xl font-bold">
            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?> <!-- First letter of username -->
        </div>
        <div class="profile_name">
        <h2 class="text-xl font-bold mt-3"><?php echo $_SESSION['name']; ?></h2>
    </div>
        <p class="text-sm"> <a href="#"><?php echo $_SESSION['email']; ?> </a></p>
    </div>

    <ul class="space-y-4">
    
        <li><a href="#" onclick="showModal('editProfileModal')" class="block p-2 bg-white text-blue rounded-lg">📝 Edit Profile</a></li>
        <li><a href="#" onclick="showModal('userInfoModal')" class="block p-2 bg-white text-blue rounded-lg">👤 View Full Info</a></li>
        <li><a href="about.php" class="about"> Learn About Us</a></li>
    </ul>
</aside>
    </div>

    <div class="dashboard-container">
   

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
             <!-- Bus Schedule -->
            <div class="card bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-lg rounded-lg p-6">
                 <h2 class="text-lg md:text-xl font-bold mb-2">Bus Schedule</h2>
                <p class="mb-4">View the latest bus schedules for your routes.</p>
                <a href="show_schedule.php" class="btn btn-primary w-full text-center">Today's Schedules</a>
            </div>
             <!-- Add Routes -->
             <div class="card bg-gradient-to-r from-green-500 to-green-700 text-white shadow-lg rounded-lg p-6">
                <h2 class="text-lg md:text-xl font-bold mb-2">Bus Routes</h2>
                <p class="mb-4">Explore the available bus routes.</p>
                <a href="add_route.php" class="btn btn-primary w-full text-center">Add Routes</a>
            </div>
            <!-- Other Options -->
            <div class="card bg-gradient-to-r from-purple-500 to-purple-700 text-white shadow-lg rounded-lg p-6">
                 <h2 class="text-lg md:text-xl font-bold mb-2">Your Routes</h2>
                 <p class="mb-4">Look at the List of the Routes u have Added.</p>
                 <a href="Show_routes.php" class="btn btn-primary w-full text-center">Show Routes</a>
            </div>
        </div>
      
   
    </div>

    <!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
        <form id="editProfileForm" action="edit_profile.php" method="POST">
            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
            
            <label class="block mb-2">Full Name:</label>
            <input type="text" name="name" class="w-full p-2 border rounded mb-4" value="<?php echo $user['name']; ?>" required>

            <label class="block mb-2">Email:</label>
            <input type="email" name="email" class="w-full p-2 border rounded mb-4" value="<?php echo $user['email']; ?>" required>
            
            <label class="block mb-2">Phone Number:</label>
            <input type="text" name="phone" class="w-full p-2 border rounded mb-4" value="<?php echo $user['phone']; ?>" required>

            <label class="block mb-2">New Password:</label>
            <input type="password" name="password" class="w-full p-2 border rounded mb-4">

            <div class="flex justify-between">
                <button type="button" onclick="closeModal('editProfileModal')" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<!-- User Info Modal -->
<div id="userInfoModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">User Details</h2>
        <p><strong>Name:</strong> <?php echo $_SESSION['name']; ?></p>
        <p><strong>Student Id:</strong> <?php echo $_SESSION['student_id']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $_SESSION['phone']; ?></p>
        <p><strong>Department:</strong> <?php echo $_SESSION['department']; ?></p>
        <p><strong>Batch:</strong> <?php echo $_SESSION['batch']; ?></p>
        
        <div class="mt-4 text-center">
            <button onclick="closeModal('userInfoModal')" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                Close
            </button>
        </div>
    </div>
</div>



    <?php include 'footer.php'; ?> <!-- Include the footer -->
    <script src="script.js"></script>
    <script>
    function showModal(modalId) {
        document.getElementById(modalId).classList.remove("hidden");
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add("hidden");
    }
</script>

 
</body>
</html>
