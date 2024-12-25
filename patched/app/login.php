<?php
// Database connection (with error handling)
$host = 'db_patched';  // Use the alias for the db container
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

// Create a secure connection using MySQLi with error handling
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_lifetime' => 1800, // Session expires in 30 minutes
    'use_strict_mode' => true, // Prevents session fixation
    'cookie_secure' => true,   // Use secure cookies (only for HTTPS)
    'cookie_httponly' => true, // Prevent JavaScript access to session cookie
    'cookie_samesite' => 'Strict', // Mitigates CSRF attacks
  ]);
}

$message = '';  // To display messages (success or error)
$debug_query = ''; // To store the generated query for debugging
$result = null; // Ensure $result is always initialized

// Handle form submission
$input_username = '';
$input_password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize and validate user inputs
  $input_username = htmlspecialchars(trim($_POST['username']));
  $input_password = htmlspecialchars(trim($_POST['password']));

  // Prepare and bind the query securely
  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
  $stmt->bind_param("s", $input_username); // "s" for string (bind input_username)
  $stmt->execute();
  $result = $stmt->get_result();

  // If user data was found
  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user details
    $debug_query = "Last executed query: SELECT id, username, password, role FROM users WHERE username = ?";

    // Verify the password using password_hash and password_verify
    if (password_verify($input_password, $user['password'])) {
      // Password matches, store user in session
      $_SESSION['user'] = $user;

      // Redirect based on user role
      if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
      } elseif ($user['role'] === 'user') {
        header("Location: index.php");
        exit;
      } else {
        $message = "Unauthorized role detected.";
      }
    } else {
      $message = "Login Failed: Incorrect password.";
    }
  } else {
    $message = "Login Failed: No user found.";
  }
  $stmt->close(); // Close prepared statement
}
// Close connection only if it's open
if ($conn && !$conn->connect_error) {
  $conn->close(); // Close connection after all processing is done
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: Arial, sans-serif;
    }


    .container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }


    h1 {
      text-align: center;
      color: #333;
      font-weight: bold;
      margin-bottom: 20px;
    }


    label {
      font-weight: bold;
      color: #555;
    }


    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }


    .btn-primary {
      width: 100%;
      padding: 10px;
      border: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }


    .btn-primary:hover {
      background-color: #0056b3;
    }


    .form-footer {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
      color: #555;
    }


    .form-footer a {
      color: #007bff;
      text-decoration: none;
    }


    .form-footer a:hover {
      text-decoration: underline;
    }


    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 15px;
      color: red;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Login</h1>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
  <div class="form-footer">
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
  <?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message); ?></p>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
