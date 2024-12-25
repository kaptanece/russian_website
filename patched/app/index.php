<?php
// Database connection
$host = 'db_patched';  // Use the alias for the db container
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Start session securely if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_lifetime' => 1800, // Session cookie lasts for 30 minutes
    'use_strict_mode' => true, // Use strict session mode
    'cookie_secure' => true,   // Only send cookies over HTTPS
    'cookie_httponly' => true, // Prevent JavaScript access to session cookies
  ]);
  session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
}

// Check login status
$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && $_SESSION['user']['role'] === 'admin'; // Check if user is an admin

// Capture and sanitize input parameters
$search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
$category = isset($_GET['category']) ? htmlspecialchars(trim($_GET['category'])) : '';
$order_by = isset($_GET['order_by']) ? htmlspecialchars(trim($_GET['order_by'])) : 'published_at';
$order_dir = isset($_GET['order_dir']) ? htmlspecialchars(trim($_GET['order_dir'])) : 'DESC';

// Validate the order_by and order_dir parameters
$valid_order_columns = ['published_at', 'title', 'category'];  // Allowed columns for ordering
$order_by = in_array($order_by, $valid_order_columns) ? $order_by : 'published_at';
$order_dir = ($order_dir === 'ASC' || $order_dir === 'DESC') ? $order_dir : 'DESC';  // Default to DESC

// SQL query to fetch news articles based on search and category
$query = "SELECT * FROM news WHERE 1";  // Add conditions for search or category if needed
if ($category) {
  $query .= " AND category = '$category'";
}
if ($search) {
  $query .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}
$query .= " ORDER BY $order_by $order_dir";  // Apply ordering

// Execute the query
$result = $conn->query($query);

// Validate the result
if (!$result instanceof mysqli_result) {
  die("Error: Invalid query result.");
}

$categories_result = $conn->query("SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");

$categories = [];
if ($categories_result) {
  while ($row = $categories_result->fetch_assoc()) {
    // Decode HTML entities (e.g., '&amp;' to '&')
    $category = html_entity_decode($row['category'], ENT_QUOTES | ENT_HTML5);

    // Remove the ampersand (&) if it still exists after decoding
    $category = str_replace("amp;", "", $category);

    // Add the sanitized category to the list
    $categories[] = $category;
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News Feed</title>
  <link href="css/style.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <nav class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-primary">News Feed</h1>
    <div>
      <?php if ($isLoggedIn): ?>
        <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?></span>
        <a href="logout.php" class="btn btn-danger btn-sm ml-3">Logout</a>
        <?php if ($isAdmin): ?>
          <a href="admin_dashboard.php" class="btn btn-secondary btn-sm ml-3">Admin Dashboard</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
        <a href="register.php" class="btn btn-secondary btn-sm ml-3">Register</a>
      <?php endif; ?>
    </div>
  </nav>

  <form method="GET" action="index.php" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search News..." value="<?= $search; ?>">
      <div class="input-group-append">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>

  <div class="mb-4">
    <h5>Filter by Category</h5>
    <a href="?category=" class="btn btn-secondary btn-sm <?= empty($category) ? 'active' : ''; ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <a href="?category=<?= htmlspecialchars($cat); ?>" class="btn btn-secondary btn-sm <?= $category === $cat ? 'active' : ''; ?>">
        <?= htmlspecialchars($cat); ?>
      </a>

    <?php endforeach; ?>

  </div>

  <div class="row">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <img src="<?= htmlspecialchars($row['image_url']) ?: 'placeholder.jpg'; ?>" class="card-img-top" alt="News Image">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
              <p class="card-text"><?= htmlspecialchars(isset($row['content']) ? $row['content'] : 'No content available'); ?></p>
              <p class="text-muted"><small>Category: <?= htmlspecialchars($row['category']); ?></small></p>
              <p class="text-muted"><small>Published: <?= htmlspecialchars($row['published_at']); ?></small></p>
            </div>
            <div class="card-footer bg-white border-0">
              <a href="<?= htmlspecialchars($row['url']); ?>" target="_blank" class="btn btn-primary btn-sm btn-read-more">
                Read more
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <p class="text-muted">No news found.</p>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
