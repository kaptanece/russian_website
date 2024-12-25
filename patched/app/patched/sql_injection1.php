<?php
// Database connection details are assumed to be passed from login.php
if (!isset($conn)) {
  die("Database connection is missing.");
}

// Retrieve raw user inputs
$input_username = isset($_POST['username']) ? trim($_POST['username']) : '';
$input_password = isset($_POST['password']) ? trim($_POST['password']) : '';

// PATCHED: Use prepared statements to prevent SQL injection
if ($stmt = $conn->prepare("SELECT * FROM users WHERE username = ?")) {
  // Bind parameters (s = string)
  $stmt->bind_param("s", $input_username);

  // Execute the statement
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user exists
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify the password securely
    if (password_verify($input_password, $user['password'])) {
      // Authentication successful
      echo "Login successful!";
    } else {
      // Invalid password
      echo "Invalid credentials.";
    }
  } else {
    // No user found
    echo "Invalid credentials.";
  }

  // Close the statement
  $stmt->close();
} else {
  // Handle query preparation error
  die("Database query failed.");
}
?>
