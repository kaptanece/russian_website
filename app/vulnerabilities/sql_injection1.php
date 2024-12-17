<?php
// Connect to the database
$servername = "db";
$username = "root";
$password = "root";
$dbname = "russian_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input_username = $_POST['username'];
  $input_password = $_POST['password'];

  // Vulnerable SQL Query
  $sql = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";

  echo "<p>Generated SQL Query:</p>";
  echo "<code>$sql</code><br>";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h3>Login Successful!</h3>";
    while ($row = $result->fetch_assoc()) {
      echo "User ID: " . $row["id"] . " - Username: " . $row["username"] . "<br>";
    }
  } else {
    echo "<h3>Login Failed</h3>";
  }
}
$conn->close();
?>

<!-- HTML Form -->
<form method="POST">
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit">Login</button>
</form>
