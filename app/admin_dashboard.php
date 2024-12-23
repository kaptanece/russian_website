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

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

$message = '';
$users = [];

// Handle user search
// Include the vulnerable user search logic if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
  include __DIR__ . '/vulnerabilities/sql_injection2.php';
}

  // Vulnerable query

  /*$query = "SELECT id, username, role FROM users WHERE username LIKE '%$search%'";
  $result = $conn->query($query);

  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $users[] = $row;
    }
  } else {
    $message = "Error: " . $conn->error;
  } */


// File upload logic
/*$upload_dir = __DIR__ . '/uploads/';
if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $file_name = basename($_FILES['file']['name']);
  $target_path = $upload_dir . $file_name;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    $message = "<p style='color: green;'>File uploaded successfully!</p>";
  } else {
    $message = "<p style='color: red;'>File upload failed.</p>";
  }
}

// File Delete Logic
if (isset($_POST['delete_file'])) {
  $file_to_delete = $upload_dir . $_POST['delete_file'];
  if (file_exists($file_to_delete)) {
    unlink($file_to_delete);
    $message = "<p style='color: green;'>File deleted successfully!</p>";
  }
}*/

/*// Include the stored_xss.php file if a form is submitted
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
  include __DIR__ . '/vulnerabilities/stored_xss.php';
}*/
// Fetch RSS feed
require_once '/var/www/vendor/autoload.php';

$feed_url = 'https://tass.com/rss/v2.xml';
$feed = new SimplePie();
$feed->set_feed_url($feed_url);
$feed->force_feed(true);
$feed->set_input_encoding('UTF-8');
$feed->enable_cache(false);
$feed->set_timeout(10);
$feed->init();
$feed->handle_content_type();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
  <style>
    body { background-color: #e3f2fd; font-family: Arial, sans-serif; }
    .container { margin-top: 20px; }
    .card { margin: 20px 0; padding: 20px; background: white; border-radius: 8px; }
    .news-container { margin-top: 40px; }
    h2 { color: #004085; }
  </style>
</head>
<body>
<div class="container">
  <h1 class="text-center">Admin Dashboard</h1>
  <a href="index.php" class="btn btn-secondary">Go to News Feed</a> |
  <a href="logout.php" class="btn btn-danger">Logout</a>
  <hr>

  <!-- User Search Section -->
  <div class="card">
    <h2>Search Users</h2>
    <form method="POST" action="">
      <input type="text" name="search" placeholder="Search by username" required>
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <p><?= $message; ?></p>

    <?php if (!empty($users)): ?>
      <table class="table">
        <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Role</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['id']); ?></td>
            <td><?= htmlspecialchars($user['username']); ?></td>
            <td><?= htmlspecialchars($user['role']); ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No users found.</p>
    <?php endif; ?>
  </div>

  <div class="card mb-4">
    <h2>Ping a Host</h2>
    <form method="GET" action="vulnerabilities/os_command.php">
      <div class="mb-3">
        <label for="ip" class="form-label">Enter IP/Hostname</label>
        <input type="text" class="form-control" id="ip" name="ip" placeholder="e.g., 8.8.8.8" required>
      </div>
      <button type="submit" class="btn btn-primary">Ping</button>
    </form>
  </div>


  <!-- Add News Section -->
  <div class="card mb-4">
    <h2>Add News</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input type="text" class="form-control" id="category" name="category" required>
      </div>
      <div class="mb-3">
        <label for="url" class="form-label">URL</label>
        <input type="text" class="form-control" id="url" name="url" required>
      </div>
      <div class="mb-3">
        <label for="published_at" class="form-label">Published At</label>
        <input type="datetime-local" class="form-control" id="published_at" name="published_at" required>
      </div>
      <button type="submit" class="btn btn-primary">Add News</button>
    </form>
    <p><?= $message; ?></p>
  </div>

  <!-- Add News Section -->
  <!--<div class="card">
    <h2>Add News</h2>
    <form method="POST" action="">
      <input type="hidden" name="add_news" value="1">
      <div class="mb-3">
        <label for="title" class="form-label">Title:</label>
        <input type="text" class="form-control" name="title" id="title" required>
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content:</label>
        <textarea class="form-control" name="content" id="content" rows="5" required></textarea>
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Category:</label>
        <input type="text" class="form-control" name="category" id="category" required>
      </div>
      <div class="mb-3">
        <label for="url" class="form-label">URL:</label>
        <input type="url" class="form-control" name="url" id="url" required>
      </div>
      <div class="mb-3">
        <label for="published_at" class="form-label">Published At:</label>
        <input type="datetime-local" class="form-control" name="published_at" id="published_at" required>
      </div>
      <button type="submit" class="btn btn-primary">Add News</button>
    </form>
  </div>-->

  <!-- File Upload Section -->
  <!-- File Upload Section -->
  <div class="card">
    <h2>File Upload</h2>
    <a href="vulnerabilities/file_upload.php" class="btn btn-primary">Go to File Upload</a>
  </div>

  <!-- File Delete Section -->
  <div class="card">
    <h2>Uploaded Files</h2>
    <?php
    $files = glob($upload_dir . '*');
    if ($files) {
      echo "<ul>";
      foreach ($files as $file) {
        $file_name = basename($file);
        echo "<li>$file_name 
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='delete_file' value='$file_name'>
                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                      </li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No files uploaded yet.</p>";
    }
    ?>
  </div>

  <!-- News Feed Section -->
  <div class="news-container">
    <h2>Latest Russian News</h2>
    <?php
    if ($feed->error()) {
      echo '<p>Error fetching feed: ' . $feed->error() . '</p>';
    } else {
      foreach ($feed->get_items() as $item) {
        echo '<div class="card">';
        echo '<h3>' . htmlspecialchars($item->get_title()) . '</h3>';
        echo '<p>' . htmlspecialchars($item->get_description()) . '</p>';
        echo '<a href="' . $item->get_link() . '" target="_blank" class="btn btn-primary">Read More</a>';
        echo '</div>';
      }
    }
    ?>
  </div>
</div>
</body>
</html>
