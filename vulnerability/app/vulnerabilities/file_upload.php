<?php
$upload_dir = __DIR__ . '/uploads/';

if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $file_name = basename($_FILES['file']['name']);
  $target_path = $upload_dir . $file_name;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    echo "<p>File uploaded successfully! File Path: <a href='uploads/$file_name'>uploads/$file_name</a></p>";
  } else {
    echo "<p>File upload failed.</p>";
  }
} else {
  echo '<form method="POST" enctype="multipart/form-data">
          <input type="file" name="file" required>
          <button type="submit">Upload</button>
        </form>';
}
?>
