<?php
$upload_dir = __DIR__ . '/uploads/';

// Ensure the upload directory exists
if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

// File upload validation and handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $file_name = basename($_FILES['file']['name']);
  $file_tmp_name = $_FILES['file']['tmp_name'];
  $file_size = $_FILES['file']['size'];
  $file_error = $_FILES['file']['error'];

  // Set allowed file types (e.g., only images)
  $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
  $file_type = mime_content_type($file_tmp_name);

  // Check if file type is allowed
  if (!in_array($file_type, $allowed_types)) {
    echo "<p>Invalid file type. Only JPG, PNG, GIF, and PDF files are allowed.</p>";
    exit;
  }

  // Check file size (e.g., limit to 5MB)
  $max_file_size = 5 * 1024 * 1024; // 5MB
  if ($file_size > $max_file_size) {
    echo "<p>File is too large. Maximum file size is 5MB.</p>";
    exit;
  }

  // Check for upload errors
  if ($file_error !== UPLOAD_ERR_OK) {
    echo "<p>File upload error. Please try again.</p>";
    exit;
  }

  // Prevent directory traversal by removing any potential special characters from the filename
  $safe_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $file_name);
  $target_path = $upload_dir . $safe_file_name;

  // Move the uploaded file to the target directory
  if (move_uploaded_file($file_tmp_name, $target_path)) {
    echo "<p>File uploaded successfully! File Path: <a href='uploads/$safe_file_name'>uploads/$safe_file_name</a></p>";
  } else {
    echo "<p>File upload failed. Please try again.</p>";
  }
} else {
  echo '<form method="POST" enctype="multipart/form-data">
          <input type="file" name="file" required>
          <button type="submit">Upload</button>
        </form>';
}
?>
