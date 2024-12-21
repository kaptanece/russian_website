<?php
// Database connection
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'php_project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set session timeout
ini_set('session.gc_maxlifetime', 1800); // Session lasts for 30 minutes
session_set_cookie_params(1800);

session_start();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];

  // Fetch user from the database
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['last_activity'] = time(); // Track activity for timeout

      // Redirect based on role
      if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
      } else {
        header("Location: index.php");
      }
      exit;
    } else {
      $message = "Invalid username or password.";
    }
  } else {
    $message = "Invalid username or password.";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="POST" action="">
  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>
  <br>
  <button type="submit">Login</button>
</form>
<p><?= $message; ?></p>
</body>
</html>
