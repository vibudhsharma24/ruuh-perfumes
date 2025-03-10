<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Delete the product
    $stmt = $conn->prepare("DELETE FROM product_details WHERE product_id = ?");
    $stmt->bind_param("s", $product_id);

    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Error: Product ID not provided.";
}

$conn->close();
?>
<a href="product_list.php">Go Back</a>
