<?php
$upload_dir = __DIR__ . '/uploads/';

// Ensure the upload directory exists with secure permissions
if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0755, true); // Use secure permissions
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  // Validate uploaded file
  $allowed_types = ['image/jpeg', 'image/png', 'application/pdf']; // Allow only specific file types
  $file_type = $_FILES['file']['type'];
  $file_name = basename($_FILES['file']['name']);
  $target_path = $upload_dir . $file_name;

  // Check file type
  if (!in_array($file_type, $allowed_types)) {
    echo "<p style='color: red;'>Invalid file type. Only JPEG, PNG, and PDF files are allowed.</p>";
    exit;
  }

  // Sanitize file name to prevent directory traversal attacks
  $file_name = preg_replace("/[^a-zA-Z0-9._-]/", "_", $file_name);
  $target_path = $upload_dir . $file_name;

  // Move the uploaded file securely
  if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    echo "<p>File uploaded successfully! File Path: <a href='uploads/$file_name'>uploads/$file_name</a></p>";
  } else {
    echo "<p style='color: red;'>File upload failed.</p>";
  }
}

// File Delete Logic - Securely implemented
if (isset($_POST['delete_file'])) {
  $file_to_delete = $upload_dir . basename($_POST['delete_file']); // Sanitize input

  // Check if the file exists and is within the allowed directory
  if (file_exists($file_to_delete) && strpos(realpath($file_to_delete), realpath($upload_dir)) === 0) {
    unlink($file_to_delete); // Delete the file securely
    echo "<p style='color: green;'>File deleted successfully!</p>";
  } else {
    echo "<p style='color: red;'>File does not exist or cannot be deleted.</p>";
  }
}
?>
