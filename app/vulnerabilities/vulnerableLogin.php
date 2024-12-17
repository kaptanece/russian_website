<?php
// vulnerableLogin.php - Vulnerable SQL Injection page
// In Docker, MySQL is running in its own container.
// To connect to it, you use the service name defined in the docker-compose.yml file (db in this case) as the hostname.
// Correct Host for Docker-based MySQL
$host = 'db'; // 'db' is the container name for the MySQL service defined in docker-compose.yml
$user = 'root';  // MySQL root username
$pass = 'root';  // MySQL root password (set in docker-compose.yml)
$dbname = 'php_project';  // Name of the database to connect to

// Establishing database connection
$conn = new mysqli($host, $user, $pass, $dbname);
// Check if the connection fails
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// To store error messages
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if form is submitted via POST
  $username = $_POST['username']; // Get username input
  $password = $_POST['password']; // Get password input

  // VULNERABLE SQL QUERY
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) { // If a user is found
    header("Location: /index.php"); // Redirect to the RSS page on success
    exit;  // Stop script execution
  } else {
    $error = "Invalid username or password"; // Set error message
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
<h1>Vulnerable Login</h1>
<form method="POST" action="vulnerableLogin.php">
  <label>Username:</label><br>
  <input type="text" name="username"><br>
  <label>Password:</label><br>
  <input type="password" name="password"><br><br>
  <button type="submit">Login</button>
</form>
<?php if ($error): ?>
  <p style="color:#0066ff;"><?php echo $error; ?></p>
<?php endif; ?>
</body>
</html>
