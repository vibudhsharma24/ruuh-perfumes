<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

// Check if product_id is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID not specified. Check your URL.");
}

$product_id = intval($_GET['id']); // Convert to integer for security

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch image data from the database
$query = "SELECT product_image FROM product_details WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($image_data);
$stmt->fetch();
$stmt->close();
$conn->close();

// Check if image data was found
if (!$image_data) {
    die("Image not found.");
}

// Send appropriate content header
header("Content-Type: image/jpeg"); // Adjust to match your image type (e.g., PNG)
echo $image_data;
?>
