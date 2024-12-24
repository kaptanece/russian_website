<?php
if (isset($_GET['ip'])) {
  $ip = $_GET['ip']; // Vulnerable input
  $output = shell_exec("ping -c 1 $ip");
  echo "<pre>$output</pre>";
} else {
  echo "Provide an IP to ping using ?ip= in the URL.";
}
?>
