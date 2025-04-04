<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Fetch product images to delete them from the server
    $sql = "SELECT product_image, additional_image1, additional_image2, additional_image3, additional_image4, additional_image5, additional_image6, additional_image7, additional_image8, additional_image9, additional_image10 FROM product_details WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Delete images from the server
        foreach ($row as $image) {
            if (!empty($image) && file_exists("uploads/" . $image)) {
                unlink("uploads/" . $image);
            }
        }
    }
    $stmt->close();
    
    // Delete product from database
    $delete_sql = "DELETE FROM product_details WHERE product_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='product_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting product.'); window.location.href='product_list.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>
