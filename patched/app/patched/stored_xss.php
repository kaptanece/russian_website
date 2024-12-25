<?php
// Ensure the database connection is available
if (!isset($conn)) {
  die("Database connection is missing."); // Exit the script if the database connection is not initialized
}

// Check if the search variable is defined
if (!isset($search)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file."); // Exit if the 'search' variable is not defined
}

// Define a function to sanitize input for output in different contexts
function sanitize_input($input) {
  return htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); // Convert special characters to HTML entities
}

// Check if the search input is not empty
if (!empty($search)) {
  // Sanitize the search input
  $safe_search = sanitize_input($search);

  // HTML Content Context
  echo "<div>You searched for: $safe_search</div>";

  // JavaScript Context
  // Safely escape input for JavaScript context
  $safe_js_search = json_encode($search);
  echo "<script>console.log('User search: ' + $safe_js_search);</script>";

  // HTML Attribute Context
  // Ensure attributes are properly sanitized
  $safe_attr_search = filter_var($search, FILTER_SANITIZE_URL); // Sanitize for URL context
  echo "<img src='$safe_attr_search' onerror=\"alert('Invalid image source!')\" alt='Test Image'>";

  // Dynamic Link Context
  // Use urlencode to safely include user input in a URL
  $safe_url_search = urlencode($search);
  echo "<a href='details.php?query=$safe_url_search'>Click here for details</a>";
}
?>
