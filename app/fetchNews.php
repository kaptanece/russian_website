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
}

foreach ($feed->get_items() as $item) {
  $title = $conn->real_escape_string($item->get_title());
  $content = $conn->real_escape_string($item->get_description());
  $url = $conn->real_escape_string($item->get_link());
  $published_at = date('Y-m-d H:i:s', strtotime($item->get_date()));

  // Extract category (first category only)
  $categories = $item->get_categories();
  $category = !empty($categories) ? $conn->real_escape_string($categories[0]->get_label()) : 'General';

  // Extract image URL if available
  $image_url = '';
  if ($enclosure = $item->get_enclosure()) {
    $image_url = $conn->real_escape_string($enclosure->get_link());
  }

  // Insert the news into the database
  $query = "INSERT IGNORE INTO news (title, content, category, url, published_at, image_url)
              VALUES ('$title', '$content', '$category', '$url', '$published_at', '$image_url')";
  $conn->query($query);
}

echo "RSS feed successfully processed and stored in the database.";
$conn->close();
?>
