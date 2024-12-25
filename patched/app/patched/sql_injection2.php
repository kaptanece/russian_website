<?php
// Ensure the database connection is available
if (!isset($conn)) {
  // Critical: Terminate if the database connection is missing.
  die("Database connection is missing.");
}

// Check if required variables are set
if (!isset($search)) {
  // Prevent execution if 'search' variable is undefined.
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}
if (!isset($order_by)) {
  // Prevent execution if 'order_by' variable is undefined.
  die("Error: Undefined variable 'order_by'. Ensure it is defined before including this file.");
}
if (!isset($order_dir)) {
  // Prevent execution if 'order_dir' variable is undefined.
  die("Error: Undefined variable 'order_dir'. Ensure it is defined before including this file.");
}

// Sanitize user inputs
$search = isset($search) ? trim($search) : '';
$order_by = isset($order_by) ? trim($order_by) : 'id';
$order_dir = isset($order_dir) ? trim($order_dir) : 'ASC';
$category = isset($category) ? trim($category) : '';

// Whitelist allowed order_by and order_dir values to prevent injection
$allowed_order_by = ['id', 'title', 'date'];
$allowed_order_dir = ['ASC', 'DESC'];

if (!in_array($order_by, $allowed_order_by)) {
  $order_by = 'id';
}
if (!in_array($order_dir, $allowed_order_dir)) {
  $order_dir = 'ASC';
}

// Use prepared statements to prevent SQL injection
$query = "SELECT * FROM news WHERE 1=1";

// Add search filter
if (!empty($search)) {
  $query .= " AND CONCAT(title, ' ', content) LIKE ?";
}

// Add category filter
if (!empty($category)) {
  $query .= " AND category = ?";
}

$query .= " ORDER BY $order_by $order_dir";

if ($stmt = $conn->prepare($query)) {
  // Bind parameters dynamically based on provided inputs
  $types = '';
  $params = [];

  if (!empty($search)) {
    $types .= 's';
    $params[] = "%$search%";
  }
  if (!empty($category)) {
    $types .= 's';
    $params[] = $category;
  }

  if ($types) {
    $stmt->bind_param($types, ...$params);
  }

  // Execute the statement
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Return the result to the caller
  return $result;
} else {
  // Handle query preparation error
  die("Database query preparation failed.");
}
?>
