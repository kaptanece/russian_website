<?php

// Database connection
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'php_project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch unique records with the minimum ID
$query = "SELECT title, url, MIN(id) as keep_id
          FROM news
          GROUP BY title, url";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  $keep_ids = [];
  while ($row = $result->fetch_assoc()) {
    $keep_ids[] = $row['keep_id'];
  }

  // Create a comma-separated list of IDs to keep
  $keep_ids_list = implode(',', $keep_ids);

  // Delete all records not in the keep list
  $delete_query = "DELETE FROM news WHERE id NOT IN ($keep_ids_list)";
  $conn->query($delete_query);

  echo "Duplicate news entries removed.";
} else {
  echo "No duplicate news entries found.";
}

$conn->close();

