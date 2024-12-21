<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload SimplePie

// Database connection
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'php_project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo "Database connected successfully.\n";
}

// RSS feed URL
$feed_url = 'https://tass.com/rss/v2.xml';

$feed = new SimplePie();
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

// Process each RSS feed item
foreach ($feed->get_items() as $item) {
  $title = $conn->real_escape_string($item->get_title());
  $content = $conn->real_escape_string($item->get_description());
  $url = $conn->real_escape_string($item->get_link());
  $published_at = date('Y-m-d H:i:s', strtotime($item->get_date()));

  // Validate fetched data
  if (empty($title) || empty($url) || empty($published_at)) {
    echo "Skipping item due to missing required fields: Title, URL, or Published Date.\n";
    continue;
  }

  // Extract category (first category only)
  $categories = $item->get_categories();
  $category = !empty($categories) ? $conn->real_escape_string($categories[0]->get_label()) : 'General';

  // Insert the news into the database
  $query = "INSERT INTO news (title, content, category, url, published_at)
              VALUES ('$title', '$content', '$category', '$url', '$published_at')
              ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                content = VALUES(content),
                category = VALUES(category),
                url = VALUES(url),
                published_at = VALUES(published_at)";

  if ($conn->query($query)) {
    echo "Inserted/Updated: $title\n";
  } else {
    echo "Error inserting/updating $title: " . $conn->error . "\n";
  }
}

echo "RSS feed successfully processed and stored in the database.\n";
$conn->close();
?>
