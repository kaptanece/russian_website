<?php
// Database connection details are assumed to be passed from login.php
if (!isset($conn)) {
  die("Database connection is missing.");
}

// Retrieve user inputs securely (no need for raw data handling if using prepared statements)
$input_username = isset($_POST['username']) ? $_POST['username'] : '';
$input_password = isset($_POST['password']) ? $_POST['password'] : '';

// PATCH: Use prepared statements to prevent SQL Injection
// Instead of directly embedding user input into the query, we use placeholders for the inputs.

// Prepare a parameterized query to prevent SQL Injection
$query = "SELECT * FROM users WHERE username = ?";

// PATCH: Prepare the SQL statement
$stmt = $conn->prepare($query);

// Check if the prepared statement is successful
if ($stmt === false) {
  die("Database query failed: " . $conn->error);
}

// PATCH: Bind the user input parameters to the prepared statement
$stmt->bind_param("s", $input_username); // Only bind the username since we check the password securely later

// PATCH: Execute the prepared statement
$stmt->execute();

// PATCH: Get the result from the query
$result = $stmt->get_result();

// Check if a user exists
if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();

  // Verify the password securely using password_verify() (assuming password is hashed in the database)
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

// Close the prepared statement
$stmt->close();
?>
