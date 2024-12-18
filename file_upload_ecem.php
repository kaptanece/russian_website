<?php
// CWE-434: Vulnerable File Upload - Blacklisting Bad Prevention

$uploadDirectory = __DIR__ . "/uploads/";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_FILES['file']['name'];
    $tempName = $_FILES['file']['tmp_name'];
    $fileType = pathinfo($filename, PATHINFO_EXTENSION);

    // BAD PRACTICE: Blacklisting certain file types
    $blacklist = ['exe', 'sh', 'bat', 'cmd'];
    if (in_array($fileType, $blacklist)) {
        $error = "File type not allowed!";
    } else {
        // Save the uploaded file
        if (move_uploaded_file($tempName, $uploadDirectory . $filename)) {
            echo "<p style='color:green;'>File uploaded successfully: <a href='uploads/$filename'>View File</a></p>";
        } else {
            $error = "File upload failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload (CWE-434)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        form { background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 8px; max-width: 400px; margin: auto; }
        h1 { text-align: center; }
        input, button { margin-bottom: 10px; width: 100%; }
    </style>
</head>
<body>
    <h1>Vulnerable File Upload</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="file">Choose a file:</label>
        <input type="file" name="file" id="file" required><br>
        <button type="submit">Upload</button>
    </form>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>
