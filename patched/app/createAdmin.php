<?php
$host = 'db_patched';
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Username and password for admin
$username = 'admin';
$password = 'adminpassword';  // In plaintext
$role = 'admin';

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the admin user
$query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashedPassword', '$role')";
if ($conn->query($query)) {
  echo "Admin user created successfully!";
} else {
  echo "Error creating admin user: " . $conn->error;
}

$conn->close();
?>
