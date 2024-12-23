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

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';


// Capture input parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'published_at';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

echo "<pre>Search Parameter in index.php: $search</pre>";


// Include vulnerable search logic
$result = include 'vulnerable_search.php';

// Validate the result
if (!$result instanceof mysqli_result) {
  die("Error: Invalid query result.");
}
// Debugging: Check the number of rows fetched
echo "Number of rows fetched: " . $result->num_rows . "<br>";

// Fetch unique categories from the database
$categories_result = $conn->query("SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$categories = [];
if ($categories_result) {
  while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['category'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News Feed</title>
  <!-- Link to External CSS -->
  <link href="css/style.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <nav class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-primary">News Feed</h1>
    <div>
      <?php if ($isLoggedIn): ?>
        <span>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></span>
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

  <!-- Search Bar -->
  <!-- Search Bar -->
  <form method="GET" action="index.php" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search News..." value="<?= $search; ?>">
      <div class="input-group-append">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>

  <!-- Include Advanced Reflected XSS Vulnerability -->
  <?php /*include 'vulnerabilities/stored_xss.php'; */?>

  <!-- Categories -->
  <div class="mb-4">
    <h5>Filter by Category</h5>
    <a href="?category=" class="btn btn-secondary btn-sm <?= empty($category) ? 'active' : ''; ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <a href="?category=<?= htmlspecialchars($cat); ?>" class="btn btn-secondary btn-sm <?= $category === $cat ? 'active' : ''; ?>">
        <?= htmlspecialchars($cat); ?>
      </a>
    <?php endforeach; ?>
  </div>

  <

  <div class="row">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <img src="placeholder.jpg" class="card-img-top" alt="News Image">
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



  <!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
