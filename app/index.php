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

session_start(); // Start the session

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';

// Handle search and category filtering
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Fetch unique categories from the database
$categories_result = $conn->query("SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$categories = [];
if ($categories_result) {
  while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['category'];
  }
}

// Base query
$query = "SELECT * FROM news WHERE 1=1";

// Apply search filter
if (!empty($search)) {
  $query .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

// Apply category filter
if (!empty($category)) {
  $query .= " AND category = '$category'";
}

// Order by published date
$query .= " ORDER BY published_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News Feed</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .header-link {
      margin-bottom: 20px;
    }
    .card {
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card-title {
      color: #007bff;
    }
    .btn-read-more {
      text-transform: uppercase;
      font-weight: bold;
    }
    .nav-link {
      font-weight: bold;
    }
  </style>
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
  <form method="GET" action="" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search News..." value="<?= htmlspecialchars($search); ?>">
      <div class="input-group-append">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>

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

  <!-- Display News -->
  <div class="row">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <?php if (!empty($row['image_url'])): ?>
              <img src="<?= htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="News Image">
            <?php else: ?>
              <img src="placeholder.jpg" class="card-img-top" alt="Placeholder Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
              <p class="card-text"><?= htmlspecialchars($row['content']); ?></p>
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
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
