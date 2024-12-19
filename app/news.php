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

// Search functionality
$search_query = '';
if (isset($_GET['search'])) {
  $search_query = $conn->real_escape_string($_GET['search']);
  $query = "SELECT * FROM news WHERE title LIKE '%$search_query%' OR content LIKE '%$search_query%' ORDER BY published_at DESC";
} else {
  $query = "SELECT * FROM news ORDER BY published_at DESC";
}

$result = $conn->query($query);
?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        background-color: #f8f9fa;
      }
      .news-title {
        color: #007bff;
      }
      .card {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
  <div class="container mt-4">
    <!-- Search Bar -->
    <form method="GET" action="news.php" class="mb-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search news..." value="<?= htmlspecialchars($search_query); ?>">
        <div class="input-group-append">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
    </form>

    <h1 class="text-primary mb-4">Latest News</h1>

    <!-- News Grid -->
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="news-title"><?= htmlspecialchars($row['title']); ?></h5>
                <p><?= htmlspecialchars($row['description']); ?></p>
              </div>
              <div class="card-footer bg-white">
                <a href="<?= htmlspecialchars($row['url']); ?>" target="_blank" class="btn btn-sm btn-primary">
                  Read more
                </a>
                <small class="text-muted d-block mt-1"><?= date('F j, Y, g:i a', strtotime($row['published_at'])); ?></small>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-warning">No news found.</div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  </body>
  </html>

<?php $conn->close(); ?>
<?php
