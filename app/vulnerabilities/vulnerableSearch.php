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

// Check if the search parameter is provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM news WHERE title LIKE '%$search%' OR content LIKE '%$search%'"; // Vulnerable SQL query

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vulnerable Search</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h1 class="text-danger">Vulnerable Search</h1>
  <p>This page is intentionally vulnerable to SQL Injection for testing purposes.</p>

  <!-- Search Form -->
  <form method="GET" action="">
    <div class="input-group mb-4">
      <input type="text" name="search" class="form-control" placeholder="Search News..." value="<?= htmlspecialchars($search); ?>">
      <div class="input-group-append">
        <button type="submit" class="btn btn-danger">Search</button>
      </div>
    </div>
  </form>

  <!-- Display Results -->
  <div>
    <?php if ($result && $result->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Content</th>
          <th>Published At</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['content']); ?></td>
            <td><?= $row['published_at']; ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">No results found.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
