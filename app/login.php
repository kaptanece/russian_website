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
$debug_query = ''; // To display the generated query for debugging
$query_error = ''; // To capture SQL errors

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve inputs directly without sanitization
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Intentionally vulnerable SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $debug_query = $sql; // Capture the generated query for debugging

  // Execute the query
  $result = $conn->query($sql);

  // Handle the query result
  if ($result) {
    if ($result->num_rows > 0) {
      $message = "<h3>Login Successful!</h3>";
      while ($row = $result->fetch_assoc()) {
        $message .= "User ID: " . $row["id"] . " - Username: " . $row["username"] . "<br>";
      }
    } else {
      $message = "<h3>Login Failed</h3>";
    }
  } else {
    $query_error = "SQL Error: " . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intentionally Vulnerable Login</title>
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

<!-- Debugging Information -->
<h2>Debugging Information</h2>
<p><strong>Generated SQL Query:</strong></p>
<pre><?= htmlspecialchars($debug_query); ?></pre>
<?php if (!empty($query_error)): ?>
  <p style="color: red;"><strong>Error:</strong> <?= htmlspecialchars($query_error); ?></p>
<?php endif; ?>

<!-- Message -->
<p><?= $message; ?></p>
</body>
</html>
