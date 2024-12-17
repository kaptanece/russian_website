<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust path to autoload

$feed_url = 'https://tass.com/rss/v2.xml';

$feed = new SimplePie();
$feed->set_feed_url($feed_url);
$feed->force_feed(true);                // Force SimplePie to treat it as a feed
$feed->set_input_encoding('UTF-8');     // Explicitly set the encoding
$feed->enable_cache(false);             // Disable caching temporarily for debugging
$feed->set_timeout(10);                 // Set timeout to avoid long delays

$feed->init();
$feed->handle_content_type();

if ($feed->error()) {
  echo '<b>Error fetching feed:</b> ' . $feed->error();
} else {
  echo '<h1>News Feed</h1>';
  echo '<a href="vulnerabilities/vulnerableLogin.php">Go to Vulnerable Login Page</a><hr>'; // Link added here
  foreach ($feed->get_items() as $item) {
    echo '<h3>' . $item->get_title() . '</h3>';
    echo '<p>' . $item->get_description() . '</p>';
    echo '<a href="' . $item->get_link() . '">Read more</a><hr>';
  }
}
?>
