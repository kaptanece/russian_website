<?php

// Database connection
$host = 'db_patched';
$user = 'root';
$pass = 'root_password';
$dbname = 'patched_db';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT id, url FROM news WHERE image_url IS NULL OR image_url = ''";
$result = $conn->query($query);
function fetch_article_image($url)
{
  // Initialize cURL session
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
  $html = curl_exec($ch);
  curl_close($ch);
  if ($html) {
    // Use DOMDocument to parse the HTML
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    // Use XPath to extract Open Graph image or main article image
    $xpath = new DOMXPath($doc);
    // Look for Open Graph meta tag
    $image = $xpath->query('//meta[@property="og:image"]/@content');
    if ($image->length > 0) {
      return $image->item(0)->nodeValue;
    }
    // Alternatively, look for the first <img> tag in the article content
    $article_img = $xpath->query('//article//img/@src'); // Adjust XPath to the site's structure
    if ($article_img->length > 0) {
      return $article_img->item(0)->nodeValue;
    }
  }
  // Return a placeholder image if no image is found
  return 'placeholder.jpg';
}

while ($row = $result->fetch_assoc()) {
  $id = $row['id'];
  $url = $row['url'];
  $image_url = fetch_article_image($url); // Fetch the article image
  // Update the image URL in the database
  $update_query = "UPDATE news SET image_url = '$image_url' WHERE id = $id";
  $conn->query($update_query);
}
echo "Image fetching completed.";
