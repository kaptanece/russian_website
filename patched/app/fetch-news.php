<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload SimplePie

// Database connection
$host = 'db_patched';  // Use the alias for the db container
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

$conn = new mysqli($host, $user, $pass, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
  // Log error and terminate script without exposing details to user
  error_log("Connection failed: " . $conn->connect_error);
  die("Database connection failed. Please try again later.");
}

// RSS feed URL
$feed_url = 'https://tass.com/rss/v2.xml';

$feed = new SimplePie();
$feed->set_feed_url($feed_url);
$feed->force_feed(true);
$feed->set_timeout(10);
$feed->enable_cache(false);  // You can enable caching if desired
$feed->init();

if ($feed->error()) {
  // Log error and terminate script without exposing details to user
  error_log("Error fetching feed: " . $feed->error());
  die("Error fetching feed. Please try again later.");
} else {
  echo "RSS feed fetched successfully.\n";
}

// Prepare the SQL statement for inserting or updating news items
$stmt = $conn->prepare("INSERT INTO news (title, content, category, url, published_at)
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                          title = VALUES(title),
                          content = VALUES(content),
                          category = VALUES(category),
                          url = VALUES(url),
                          published_at = VALUES(published_at)");

if ($stmt === false) {
  error_log("Prepared statement failed: " . $conn->error);
  die("Database query failed. Please try again later.");
}

// Process each RSS feed item
foreach ($feed->get_items() as $item) {
  $title = $conn->real_escape_string($item->get_title());
  $content = $conn->real_escape_string($item->get_description());
  $url = $conn->real_escape_string($item->get_link());
  $published_at = date('Y-m-d H:i:s', strtotime($item->get_date()));

  // Validate fetched data
  if (empty($title) || empty($url) || empty($published_at)) {
    error_log("Skipping item due to missing required fields: Title, URL, or Published Date.");
    continue;
  }

  // Extract category (first category only)
  $categories = $item->get_categories();
  $category = !empty($categories) ? htmlspecialchars($categories[0]->get_label(), ENT_QUOTES, 'UTF-8') : 'General';

  // Bind the parameters to the prepared statement
  $stmt->bind_param('sssss', $title, $content, $category, $url, $published_at);

  // Execute the prepared statement
  if ($stmt->execute()) {
    echo "Inserted/Updated: $title\n";
  } else {
    error_log("Error inserting/updating $title: " . $stmt->error);
    echo "Error inserting/updating $title. Please check the log for details.\n";
  }
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();

echo "RSS feed successfully processed and stored in the database.\n";
?>
