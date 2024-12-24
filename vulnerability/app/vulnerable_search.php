<?php
/*// Ensure the database connection is available
if (!isset($conn)) {
  die("Database connection is missing.");
}*/
/*// Check if the search variable is defined
if (!isset($search)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}
// Build the base query (intentionally vulnerable)
$query = "SELECT * FROM news WHERE 1=1";

// Debugging: Output the search parameter
echo "<pre>Search Parameter in vulnerable_search.php: $search</pre>";


// Add search filter (intentionally vulnerable)
/*if (!empty($search)) {
  // Use only one column for simplicity to ensure injection works
  $query .= " AND '$search";
}*/

/*if (!empty($search)) {
  // Simplify the condition to avoid unmatched quotes
  $query .= " AND title = '$search";
}*/
/*// Debugging: Output the generated query
echo "<pre>Generated Query: $query</pre>";*/

/*
// Add category filter (intentionally vulnerable)
if (!empty($category)) {
  $query .= " AND category = '$category'";
}*/




// Debugging: Output the generated query
//echo "<pre>Generated Query: $query</pre>";

/*// Execute the query (intentionally without prepared statements)
$result = $conn->query($query);

// Check if the query execution was successful
if (!$result) {
  die("SQL Error: " . $conn->error);
}

// Return the result to the caller
return $result;
*/

// Ensure the database connection is available
if (!isset($conn)) {
  die("Database connection is missing.");
}

if (!isset($search)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}
if (!isset($order_by)) {
  die("Error: Undefined variable 'order'. Ensure it is defined before including this file.");
}
if (!isset($order_dir)) {
  die("Error: Undefined variable 'search'. Ensure it is defined before including this file.");
}

// Debugging: Output the search parameter to demonstrate Reflected XSS
echo "<p>Search Parameter in vulnerable_search.php: $search</p>";

// Build the base query (intentionally vulnerable)
$query = "SELECT * FROM news WHERE 1=1";

// Add search filter (intentionally vulnerable)
/*if (!empty($search)) {
  // Intentionally vulnerable to SQL Injection
  $query .= " AND title LIKE '$search'";
}*/
// Add search filter
// Add search filter
// Add search filter
// Add search filter
/*if (!empty($search)) {
  // Vulnerable to SQL Injection: directly inject user input
  $query .= " AND title LIKE '%$search%'";
}*/
// Add search filter
/*if (!empty($search)) {
  // Vulnerable to SQL Injection: directly inject user input
  $query .= " AND title LIKE '%" . $search . "'";
}*/

// Add search filter
if (!empty($search)) {
  // Vulnerable to SQL Injection: directly inject user input
  $query .= " AND CONCAT(title, ' ', content) LIKE '%" . $search . "%'";
}


// Add category filter
if (!empty($category)) {
  $query .= " AND category = '$category'";
}

// Add ordering
$query .= " ORDER BY $order_by $order_dir";



// Debugging: Output the generated query to check injection success
echo "<pre>Generated Query: $query</pre>";

// Execute the query (intentionally without prepared statements)
$result = $conn->query($query);

// Check if the query execution was successful
if (!$result) {
  die("SQL Error: " . $conn->error);
}

// Return the result to the caller
return $result;
?>


?>
