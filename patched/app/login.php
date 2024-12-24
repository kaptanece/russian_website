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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: center;
    }
    h1 {
      color: #333;
    }
    label {
      display: block;
      margin-bottom: 8px;
      text-align: left;
      font-weight: bold;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    p {
      margin-top: 10px;
      font-size: 14px;
      color: #555;
    }
    a {
      color: #007bff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .message {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
<h1>Login</h1>

<!-- Display user info and logout if the user is logged in -->
<?php if (isset($_SESSION['user'])): ?>
  <p>Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?>!</p>
<?php else: ?>
  <!-- Show login form if not logged in -->
  <form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Login</button>
  </form>

  <!-- If user is not logged in, show Register link -->
  <p>Don't have an account? <a href="register.php">Register here</a></p>
<?php endif; ?>

<!-- Message -->
<p class="message"><?= $message; ?></p>

<!-- Display Debugging Query -->
<?php if (!empty($debug_query)): ?>
  <pre>Last executed query: <?= htmlspecialchars($debug_query); ?></pre>
<?php endif; ?>

</body>
</html>
