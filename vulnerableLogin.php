<?php
$host = 'db'; // MySQL Docker container name
$user = 'root';  // MySQL root username
$pass = 'root';  // MySQL root password
$dbname = 'php_project';  // Database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // SQL Query to verify username, password, and role
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = '$role'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Redirect based on role
        if ($role === 'admin') {
          header("Location: admin_page.php");
        } else {
          header("Location: user_page.php");
        }
      
        exit;
    } else {
        $error = "Invalid username, password, or role.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Role-Based Login</title>
    <style>
        body {
            background-color: #e3f2fd;
            font-family: Arial, sans-serif;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; margin: 0;
        }
        .login-container {
            background-color: white; padding: 40px; border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%; max-width: 400px;
            text-align: center;
        }
        h1 { color: #004085; margin-bottom: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, button {
            width: 100%; padding: 10px; margin-top: 10px; font-size: 1rem;
            border: 1px solid #ccc; border-radius: 5px;
        }
        .role-buttons button {
            width: 45%; margin: 10px 2.5%; padding: 10px; font-size: 1.1rem;
            font-weight: bold; color: white; border: none; border-radius: 5px;
        }
        .admin-btn { background-color: #007bff; }
        .user-btn { background-color: #28a745; }
        .admin-btn:hover { background-color: #0056b3; }
        .user-btn:hover { background-color: #218838; }
        .error { color: red; margin-top: 10px; }
    </style>
    <script>
        function setRole(role) {
            document.getElementById('role').value = role; // Set the hidden role input
            document.getElementById('role-selected').innerText = `Selected Role: ${role}`;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h1>🔒 Role-Based Login</h1>

        <!-- Role Selection Buttons -->
        <div class="role-buttons">
            <button type="button" class="admin-btn" onclick="setRole('admin')">Admin</button>
            <button type="button" class="user-btn" onclick="setRole('user')">User</button>
        </div>

        <!-- Role Indicator -->
        <p id="role-selected">Please select a role</p>

        <!-- Login Form -->
        <form method="POST" action="vulnerableLogin.php">
            <!-- Hidden Role Input -->
            <input type="hidden" name="role" id="role" value="">

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>

            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
