<?php
/*// Database connection
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'php_project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// No sanitization or validation (vulnerable)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'published_at';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Vulnerable query: allows SQL injection
$query = "SELECT * FROM news WHERE 1=1";

if (!empty($search)) {
  $query .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

if (!empty($category)) {
  $query .= " AND category = '$category'";
}

$query .= " ORDER BY $order_by $order_dir";

// Debugging the generated query
echo "<pre>Generated Query: $query</pre>";

// Execute query
$result = $conn->query($query);
echo "<pre>Generated Result: $result</pre>";

if (!$result) {
  die("Error: " . $conn->error);
}

return $result;

*/?>
