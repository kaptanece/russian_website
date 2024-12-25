<?php
if (isset($_GET['url'])) {
  // Retrieve the URL parameter from the user input
  $url = $_GET['url'];

  // VULNERABILITY MITIGATION: Ensure the URL uses only HTTP or HTTPS
  $allowed_protocols = ['http', 'https'];
  $parsed_url = parse_url($url);

  // Check if the URL has a valid protocol and is allowed
  if (!isset($parsed_url['scheme']) || !in_array($parsed_url['scheme'], $allowed_protocols)) {
    die("Error: Only HTTP and HTTPS protocols are allowed.");
  }

  // VULNERABILITY MITIGATION: Prevent SSRF to internal resources (localhost, 127.0.0.1, etc.)
  $blocked_ips = ['127.0.0.1', 'localhost', '0.0.0.0'];
  if (isset($parsed_url['host']) && in_array($parsed_url['host'], $blocked_ips)) {
    die("Error: Access to internal resources is blocked.");
  }

  // Optional: You could allowlist specific domains if necessary
  $allowed_domains = ['example.com', 'trustedsite.com'];
  if (isset($parsed_url['host']) && !in_array($parsed_url['host'], $allowed_domains)) {
    die("Error: This domain is not allowed.");
  }

  // Attempt to fetch the content from the provided URL
  $response = @file_get_contents($url);  // @ to suppress any warnings

  // Check if the request was successful
  if ($response === false) {
    die("Error: Unable to fetch content from the provided URL.");
  }

  // Display the content
  echo "<pre>$response</pre>";
} else {
  // If no URL is provided, show an instruction message
  echo "Provide a URL to fetch content using ?url= in the URL.";
}
?>
