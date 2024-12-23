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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];

  // Check if username already exists
  $checkUser = $conn->query("SELECT id FROM users WHERE username = '$username'");
  if ($checkUser->num_rows > 0) {
    $message = "Username already exists. Please choose another.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user with 'user' role
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', 'user')";
    if ($conn->query($query)) {
      $message = "Registration successful! You can now <a href='login.php'>log in</a>.";
    } else {
      $message = "Error registering user: " . $conn->error;
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>
<body>
<h1>Register</h1>
<form method="POST" action="">
  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>
  <br>
  <button type="submit">Register</button>
</form>
<p><?= $message; ?></p>
</body>
</html>