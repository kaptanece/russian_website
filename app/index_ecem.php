<?php
/*// Database connection
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'php_project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all uploaded files
$result = $conn->query("SELECT file_path FROM news");
$images = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['file_path'];
    }
}
*/?><!--
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Russian News</title>
    <style>
        body { background-color: #e3f2fd; font-family: Arial, sans-serif; text-align: center; }
        .carousel { position: relative; width: 500px; margin: 20px auto; }
        .carousel img { width: 100%; border: 2px solid #333; border-radius: 10px; }
        button { margin-top: 10px; padding: 10px; font-size: 16px; cursor: pointer; }
    </style>
    <script>
        let images = <?php /*echo json_encode($images); */?>;
        let currentIndex = 0;

        function showImage(index) {
            document.getElementById('carousel-img').src = 'uploads/' + images[index];
        }

        function prevImage() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            showImage(currentIndex);
        }

        function nextImage() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            showImage(currentIndex);
        }

        window.onload = function () {
            if (images.length > 0) {
                showImage(currentIndex);
            }
        }
    </script>
</head>
<body>
    <h1>Latest Russian News</h1>

    <?php /*if (!empty($images)): */?>
        <div class="carousel">
            <img id="carousel-img" src="" alt="Uploaded Image">
            <div>
                <button onclick="prevImage()">⟨ Previous</button>
                <button onclick="nextImage()">Next ⟩</button>
            </div>
        </div>
    <?php /*else: */?>
        <p>No uploaded images yet.</p>
    <?php /*endif; */?>
</body>
</html>
-->
