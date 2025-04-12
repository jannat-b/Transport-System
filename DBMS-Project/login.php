<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Transportation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.2/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="CSS/Login.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="login-container">
    <div class="loginHeader">
        
    <h1 class="text-4xl md:text-6xl lg:text-9xl font-bold">DIU</h1>
    <p>STUDENT TRANSPORT SYSTEM</p>
    </div>
    <div class="overlay"></div>
    <div class="loginBody">
        <div class="error">
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            
            echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        </div>
        <form action="authenticate.php" method="post">
          
            <div class="loginInput">
            <label for="username">Username:</label>
            <input placeholder="username" type="text" id="username" name="username" required>
            </div>
            <div class="loginInput">
            <label  for="password">Password:</label>
            <input placeholder="password" type="password" id="password" name="password" required>
            </div>
            <div class="rememberMe">
            <div class="flex items-center mt-4">
                        <input type="checkbox" name="remember_me" id="rememberMe" class="mr-2" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?> />
                        <label for="rememberMe" class="text-sm">Remember Me</label>
            </div>
            </div>
            <div class="loginButton">
            <button type="submit" class="btn">Login</button>
            </div>
            <div class="flex justify-between mt-4 text-sm">
                    <a href="#" class="text-blue-400 hover:underline" onclick="forgotPassword()">Forgot Password?</a>
                    <a href="register.php" class="text-blue-400 hover:underline" >Sign Up</a>
            </div>
        </form>
          <!-- Forgot Password Modal -->
        <div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-80">
                <h3 class="text-xl font-bold mb-4 text-center">Forgot Password</h3>
                <p class="text-sm text-gray-600 mb-4 text-center">Enter your email address to receive a password reset link.</p>
                <form id="forgotPasswordForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="forgotPasswordEmail" class="input input-bordered w-full" placeholder="Enter your email" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Send Reset Link</button>
                </form>
                <button onclick="closeForgotPasswordModal()" class="btn btn-secondary w-full mt-4">Cancel</button>
            </div>
        </div>
    </div>
    </div>
    <?php include 'footer.php'; ?> <!-- Include the footer -->
    <script src="script.js"></script>
    <script>
        // Show the Forgot Password modal
        function forgotPassword() {
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        }

        // Close the Forgot Password modal
        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }

        // Handle Forgot Password form submission
        document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('forgotPasswordEmail').value;

            // Simulate sending a password reset link
            alert(`A password reset link has been sent to ${email}.`);
            closeForgotPasswordModal();
        });
    </script>
</body>
</html>
