<?php
// Include SimplePie for fetching RSS feeds
require_once __DIR__ . '/../../vendor/autoload.php';

$feed_url = 'https://tass.com/rss/v2.xml';
$feed = new SimplePie();
$feed->set_feed_url($feed_url);
$feed->init();
$feed->handle_content_type();

// File upload logic (as previously implemented)
$upload_dir = __DIR__ . '/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// File Upload Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file_name = basename($_FILES['file']['name']);
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        echo "<p style='color: green;'>File uploaded successfully!</p>";
    } else {
        echo "<p style='color: red;'>File upload failed.</p>";
    }
}

// File Delete Logic
if (isset($_POST['delete_file'])) {
    $file_to_delete = $upload_dir . $_POST['delete_file'];
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        echo "<p style='color: green;'>File deleted successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body { background-color: #e3f2fd; font-family: Arial, sans-serif; }
        .container { margin-top: 20px; }
        .card { margin: 20px 0; padding: 20px; background: white; border-radius: 8px; }
        .news-container { margin-top: 40px; }
        h2 { color: #004085; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Admin Page</h1>

    <!-- File Upload Section -->
    <div class="card">
        <h2>Upload a File</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- File Delete Section -->
    <div class="card">
        <h2>Uploaded Files</h2>
        <?php
        $files = glob($upload_dir . '*');
        if ($files) {
            echo "<ul>";
            foreach ($files as $file) {
                $file_name = basename($file);
                echo "<li>$file_name 
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='delete_file' value='$file_name'>
                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                      </li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No files uploaded yet.</p>";
        }
        ?>
    </div>

    <!-- News Feed Section -->
    <div class="news-container">
        <h2>Latest Russian News</h2>
        <?php
        if ($feed->error()) {
            echo '<p>Error fetching feed: ' . $feed->error() . '</p>';
        } else {
            foreach ($feed->get_items() as $item) {
                echo '<div class="card">';
                echo '<h3>' . htmlspecialchars($item->get_title()) . '</h3>';
                echo '<p>' . htmlspecialchars($item->get_description()) . '</p>';
                echo '<a href="' . $item->get_link() . '" target="_blank" class="btn btn-primary">Read More</a>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
</body>
</html>
