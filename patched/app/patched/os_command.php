<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['ip'])) {
  // Retrieve and sanitize input
  $ip = $_GET['ip'];

  // Validate IP address
  if (filter_var($ip, FILTER_VALIDATE_IP)) {
    // If the input is a valid IP address, execute the command
    $output = shell_exec("ping -c 1 " . escapeshellarg($ip) . " 2>&1");
    echo "<h3>Ping Results:</h3>";
    echo "<pre>$output</pre>";
  } else {
    // If input validation fails, deny access
    echo "<h3>Access Denied</h3>";
    echo "<p>The provided input is not a valid IP address.</p>";
  }
} else {
  echo "<h3>Instruction:</h3>";
  echo "<p>Provide an IP to ping using ?ip= in the URL.</p>";
}
?>
