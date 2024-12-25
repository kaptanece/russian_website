<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload SimplePie

// Database connection
$host = 'db_patched';  // Use the alias for the db container
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';

try{
  $conn = new mysqli($host, $user, $pass, $dbname);

  // Check if connection was successful
  if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
  } else {
    echo "Database connected successfully.\n";
  }
} catch (Exception $e) {
  die("Connection error: " . $e->getMessage());
}

// RSS feed URL
$feed_url = 'https://tass.com/rss/v2.xml';

$feed = new SimplePie();  // No need for 'use SimplePie;'
$feed->set_feed_url($feed_url);
$feed->force_feed(true);
$feed->set_timeout(10);
$feed->enable_cache(false);
$feed->init();

if ($feed->error()) {
  die("<strong>Error fetching feed:</strong> " . $feed->error());
} else {
  echo "RSS feed fetched successfully.\n";
}

// Prepare SQL query
$query = "INSERT INTO news (title, content, category, url, published_at, image_url)
          VALUES (?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            content = VALUES(content),
            category = VALUES(category),
            url = VALUES(url),
            published_at = VALUES(published_at),
            image_url = VALUES(image_url)";

$stmt = $conn->prepare($query);
if (!$stmt) {
  die("Error preparing query: " . $conn->error);
}

// Process each RSS feed item
foreach ($feed->get_items() as $item) {
  $title = $item->get_title();
  $content = $item->get_description();
  $url = $item->get_link();
  $published_at = date('Y-m-d H:i:s', strtotime($item->get_date()));

  // Validate fetched data
  if (empty($title) || empty($url) || empty($published_at)) {
    echo "Skipping item due to missing required fields: Title, URL, or Published Date.\n";
    continue;
  }

  // Extract category (first category only)
  $categories = $item->get_categories();
  $category = !empty($categories) ? $categories[0]->get_label() : 'General';

  // Extract image URL from the feed (check if <image> exists)
  $image_url = '';
  $image = $feed->get_image();
  if ($image) {
    $image_url = $image->get_url();
  }

  // Bind parameters for the prepared statement
  $stmt->bind_param('ssssss', $title, $content, $category, $url, $published_at, $image_url);

  // Execute the query
  if ($stmt->execute()) {
    echo "Inserted/Updated: $title\n";
  } else {
    echo "Error inserting/updating $title: " . $stmt->error . "\n";
  }
}

// Close statement and connection
$stmt->close();
$conn->close();

echo "RSS feed successfully processed and stored in the database.\n";
?>
