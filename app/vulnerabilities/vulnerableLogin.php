<?php
// Database connection
$conn = new mysqli('db', 'root', 'root', 'php_project');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Execute the query
$result = $conn->query($query);

// Output query debug
echo "<pre>$query</pre>";

// Display results
if ($result) {
  while ($row = $result->fetch_assoc()) {
    echo "<pre>";
    print_r($row);
    echo "</pre>";
  }
} else {
  echo "No results found or query error.";
}
?>
