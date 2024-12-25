<?php
$upload_dir = __DIR__ . '/uploads/';

// Ensure the upload directory exists
if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

// Allowed file extensions and MIME types
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$allowed_mime_types = ['image/jpeg', 'image/png'];

// Process file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  // Check if the uploaded file is an array (for multiple files) or a single file
  if (is_array($_FILES['file']['name'])) {
    $files = $_FILES['file'];
  } else {
    // If a single file is uploaded, convert it to an array format for consistency
    $files = [
      'name' => [$_FILES['file']['name']],
      'tmp_name' => [$_FILES['file']['tmp_name']],
      'size' => [$_FILES['file']['size']],
      'error' => [$_FILES['file']['error']]
    ];
  }

  // Create a container for the output messages
  echo '<div class="upload-result-container">';

  foreach ($files['name'] as $index => $file_name) {
    if (isset($file_name) && is_string($file_name) && !empty($file_name)) {
      $file_name = basename($file_name);  // Get the file name without path
      $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));  // Get file extension
      $target_path = $upload_dir . $file_name;  // Path where the file will be uploaded

      // Validate file extension
      if (!in_array($file_extension, $allowed_extensions)) {
        echo "<div class='alert alert-danger'>
                <strong>Restriction:</strong> Invalid file type. Only <strong>JPG, JPEG, PNG</strong> images are allowed for upload. <br>
                <em>File <strong>'$file_name'</strong> is not allowed due to invalid extension.</em>
              </div>";
      } else {
        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name'][$index]);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_mime_types)) {
          echo "<div class='alert alert-danger'>
                  <strong>Restriction:</strong> Invalid MIME type for <strong>'$file_name'</strong>. Expected MIME types: <strong>image/jpeg</strong>, <strong>image/png</strong>.
                </div>";
        } else {
          // Move the uploaded file to the desired directory
          if (move_uploaded_file($files['tmp_name'][$index], $target_path)) {
            echo "<div class='alert alert-success'>
                    <strong>Success!</strong> File '<strong>$file_name</strong>' uploaded successfully! <br>
                    <a href='uploads/$file_name' target='_blank' class='file-link'>Click here to view the file</a>
                  </div>";
          } else {
            echo "<div class='alert alert-danger'>
                    <strong>Error!</strong> Failed to upload file '<strong>$file_name</strong>'.
                  </div>";
          }
        }
      }
    } else {
      echo "<div class='alert alert-warning'>
              <strong>Warning!</strong> No file selected or invalid file name.
            </div>";
    }
  }

  echo '</div>';
}
?>

<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    padding: 20px;
  }

  .upload-result-container {
    margin-top: 20px;
  }

  .alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
  }

  .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
  }

  .alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
  }

  .file-link {
    color: #007bff;
    text-decoration: none;
  }

  .file-link:hover {
    text-decoration: underline;
  }
</style>
