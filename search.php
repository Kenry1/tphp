<?php
// Database connection (update with your own credentials)
$host = 'localhost'; // Database host
$db = 'onlineshop'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the URL
$query = isset($_GET['q']) ? $_GET['q'] : '';

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$searchTerm = '%' . $query . '%';
$stmt->bind_param('ss', $searchTerm, $searchTerm);

// Execute the statement
if (!$stmt->execute()) {
    echo json_encode(['html' => '<p>Error executing query.</p>']);
    exit;
}

// Get the result
$result = $stmt->get_result();

// Initialize an empty string to hold the HTML output
$html = '';

// Check if there are results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Create HTML for each product (customize as needed)
        $html .= '<div class="product">';
        $html .= '<h3>' . htmlspecialchars($row['name']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($row['description']) . '</p>';
        $html .= '<p>Price: $' . htmlspecialchars($row['price']) . '</p>';
        $html .= '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" />';
        $html .= '</div>';
    }
} else {
    $html = '<p>No products found matching your search.</p>';
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the HTML as JSON
echo json_encode(['html' => $html]);
?>
