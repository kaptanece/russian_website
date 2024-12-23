<?php
// Ensure the database connection is available
if (!isset($conn)) {
  die("Database connection is missing.");
}
// Check if the search variable is defined
if (!isset($search)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}

// Intentionally reflect user input in different vulnerable contexts
if (!empty($search)) {
  // HTML Content Context
  echo "<div>You searched for: $search</div>";

  // JavaScript Context
  echo "<script>console.log('User search: $search');</script>";

  // HTML Attribute Context
  echo "<img src='$search' onerror=\"alert('XSS in image source!')\" alt='Test Image'>";

  // Dynamic Link Context
  echo "<a href='details.php?query=$search'>Click here for details</a>";
}
?>
