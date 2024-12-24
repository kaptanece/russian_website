<?php
if (isset($_GET['ip'])) {
  // Retrieve and sanitize the input
  $ip = $_GET['ip'];

  // Validate the IP address or hostname
  if (filter_var($ip, FILTER_VALIDATE_IP) || preg_match('/^[a-zA-Z0-9.-]+$/', $ip)) {
    // Escape the IP address to prevent command injection
    $safe_ip = escapeshellarg($ip);

    // Execute the ping command securely
    $output = shell_exec("ping -c 1 $safe_ip");

    // Display the output
    echo "<pre>$output</pre>";
  } else {
    echo "Invalid IP address or hostname provided.";
  }
} else {
  echo "Provide an IP to ping using ?ip= in the URL.";
}
?>
