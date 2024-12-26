<?php
// Reflect the user input directly into HTML, now with proper sanitization.
if (isset($_GET['category'])) {
  $category = $_GET['category'];

  // PATCH: Sanitizing user input to prevent XSS vulnerability.
  // Using htmlspecialchars() to convert special characters into HTML entities to avoid script execution.
  // This will render any tags or special characters as plain text.
  $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');

  // Display the sanitized input in a safe manner
  echo "
    <div class='alert alert-danger' style='color:red;'>
        <strong>You selected: </strong>$category  <!-- PATCH: The user input is now safely displayed -->
    </div>
    
    <!-- PATCH: Removed malicious payload execution -->
    <!-- The onerror event handler is now empty, ensuring no script is executed -->
    <img src='nonexistent_image.jpg' onerror=''>
  ";
}
?>
