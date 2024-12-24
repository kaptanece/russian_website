<?php
// Database connection
$host = 'db_patched';
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$message = '';
$users = [];

// Handle user search securely with prepared statements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
  $search = $_POST['search'];
  $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username LIKE ?");
  $search_term = "%" . $search . "%";
  $stmt->bind_param("s", $search_term);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  $stmt->close();
}

// Fetch RSS feed securely
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
    <p><?= htmlspecialchars($message); ?></p>

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

  <!-- Ping a Host Section -->
  <div class="card mb-4">
    <h2>Ping a Host</h2>
    <form method="GET" action="patched/os_command.php">
      <div class="mb-3">
        <label for="ip" class="form-label">Enter IP/Hostname</label>
        <input type="text" class="form-control" id="ip" name="ip" placeholder="e.g., 8.8.8.8" required>
      </div>
      <button type="submit" class="btn btn-primary">Ping</button>
    </form>
  </div>

  <!-- File Upload Section -->
  <div class="card">
    <h2>File Upload</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <?php include 'patched/file_upload.php'; ?> <!-- Including the file upload logic -->
  </div>

  <!-- Uploaded Files Section -->
  <div class="card">
    <h2>Uploaded Files</h2>
    <?php
    $files = glob(__DIR__ . '/uploads/*');
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
