<?php
// reflectedXSS.php - Page demonstrating Reflected XSS

// Get user input from the query parameter 'input'
$user_input = isset($_GET['input']) ? $_GET['input'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reflected XSS Demonstration</title>
</head>
<body>
<h1>Reflected XSS Vulnerability Example</h1>

<!-- Input Form -->
<form method="GET" action="reflectedXSS.php">
  <label for="input">Enter something:</label>
  <input type="text" name="input" id="input" placeholder="Type here..." value="<?php echo $user_input; ?>">
  <button type="submit">Submit</button>
</form>

<!-- Reflected Output -->
<?php if (!empty($user_input)): ?>
  <p>You entered: <strong><?php echo $user_input; ?></strong></p> <!-- Vulnerable: No sanitization -->
<?php endif; ?>

<p>
  <strong>Test Example:</strong><br>
  Try injecting a script, such as:<br>
  <code>?input=&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
</p>

<!-- Back to Home -->
<a href="index.php">Go Back to Home</a>
</body>
</html>
