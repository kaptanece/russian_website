<?php

// Database connection details
$host = 'db_patched'; // MySQL container name (or 127.0.0.1 for host machine if PHP is on the same machine)
$user = 'root'; // MySQL username
$password = 'root_password'; // MySQL password
$dbname = 'patched_db'; // Database name

// Create a connection
$mysqli = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($mysqli->connect_error) {
  // If connection fails
  die("Connection failed: " . $mysqli->connect_error);
} else {
  // If connection is successful
  echo "Database connected successfully!";
}

// Close the connection
$mysqli->close();
?>