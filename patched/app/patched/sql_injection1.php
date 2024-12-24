<?php
// Ensure the database connection is available
if (!isset($conn)) {
  die("Database connection is missing.");
}

// Check if the required parameters are set
if (!isset($search)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}

if (!isset($order_by)) {
  die("Error: Undefined variable 'order'. Ensure it is defined before including this file.");
}

if (!isset($order_dir)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}

// Validate inputs to prevent XSS and other attacks
$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
$category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
$order_by = htmlspecialchars($order_by, ENT_QUOTES, 'UTF-8');
$order_dir = htmlspecialchars($order_dir, ENT_QUOTES, 'UTF-8');

// Ensure that order_by and order_dir contain only valid column names and directions
$valid_order_columns = ['title', 'date', 'category']; // List of allowed columns for ordering
$valid_order_dirs = ['ASC', 'DESC']; // Allowed order directions

if (!in_array($order_by, $valid_order_columns)) {
  die("Invalid column for ordering.");
}

if (!in_array($order_dir, $valid_order_dirs)) {
  die("Invalid order direction.");
}

// Start building the base query
$query = "SELECT * FROM news WHERE 1=1";

// Add the search filter using prepared statements
if (!empty($search)) {
  $query .= " AND CONCAT(title, ' ', content) LIKE ?";
}

// Add the category filter
if (!empty($category)) {
  $query .= " AND category = ?";
}

// Add ordering
$query .= " ORDER BY $order_by $order_dir";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if ($stmt === false) {
  die("SQL Error: " . $conn->error);
}

// Bind the parameters if they are set
if (!empty($search) && !empty($category)) {
  $search_param = "%" . $search . "%";
  $stmt->bind_param("ss", $search_param, $category);
} elseif (!empty($search)) {
  $search_param = "%" . $search . "%";
  $stmt->bind_param("s", $search_param);
} elseif (!empty($category)) {
  $stmt->bind_param("s", $category);
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if (!$result) {
  die("SQL Error: " . $conn->error);
}

// Return the result to the caller
return $result;
?>
