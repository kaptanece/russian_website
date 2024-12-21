<?php
if (!isset($conn)) {
  $host = 'db';
  $user = 'root';
  $pass = 'root';
  $dbname = 'php_project';

  $conn = new mysqli($host, $user, $pass, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve and escape POST data
  $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
  $content = isset($_POST['content']) ? $conn->real_escape_string($_POST['content']) : '';
  $category = isset($_POST['category']) ? $conn->real_escape_string($_POST['category']) : '';
  $url = isset($_POST['url']) ? $conn->real_escape_string($_POST['url']) : '';
  $published_at = isset($_POST['published_at']) && !empty($_POST['published_at'])
    ? $conn->real_escape_string($_POST['published_at'])
    : date('Y-m-d H:i:s');

  // Check for missing fields
  if (empty($title) || empty($content) || empty($category) || empty($url)) {
    $message = "Error: All fields except published_at are required.";
  } else {
    // Insert news into the database
    $query = "INSERT INTO news (title, content, category, url, published_at) 
              VALUES ('$title', '$content', '$category', '$url', '$published_at')";

    if ($conn->query($query)) {
      $message = "News added successfully!";
    } else {
      $message = "Error adding news: " . $conn->error;
    }
  }
}

// Do not close the connection here
echo $message;
?>
