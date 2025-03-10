<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['product_id'], $data['name'], $data['image'], $data['price'], $data['original_price'], $data['size'], 
    $data['product_type'], $data['product_version'], $data['product_description'], $data['top_notes'], 
    $data['middle_notes'], $data['base_notes'], $data['product_category'], $data['product_gender'], 
    $data['lot_number'], $data['purchase_date'], $data['quantity'])) {

    $product_id = $data['product_id'];
    $name = $data['name'];
    $image = $data['image'];
    $price = $data['price'];
    $original_price = $data['original_price'];
    $size = $data['size'];
    $product_type = $data['product_type'];
    $product_version = $data['product_version'];
    $product_description = $data['product_description'];
    $top_notes = $data['top_notes'];
    $middle_notes = $data['middle_notes'];
    $base_notes = $data['base_notes'];
    $product_category = $data['product_category'];
    $product_gender = $data['product_gender'];
    $lot_number = isset($data['lot_number']) && $data['lot_number'] !== '' ? $data['lot_number'] : NULL;
    $purchase_date = $data['purchase_date'];
    $quantity = $data['quantity'];

    // Check if the product already exists in the cart
    $check_stmt = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ?");
    $check_stmt->bind_param("s", $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // If product exists, update quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;

        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ?, price = ?, original_price = ?, size = ?, 
            product_type = ?, product_version = ?, product_description = ?, top_notes = ?, middle_notes = ?, 
            base_notes = ?, product_category = ?, product_gender = ?, lot_number = ?, purchase_date = ? WHERE product_id = ?");
        $update_stmt->bind_param("dssssssssssssss", $new_quantity, $price, $original_price, $size, 
            $product_type, $product_version, $product_description, $top_notes, $middle_notes, $base_notes, 
            $product_category, $product_gender, $lot_number, $purchase_date, $product_id);

        if ($update_stmt->execute()) {
            echo "Cart updated successfully!";
        } else {
            echo "Error updating cart.";
        }

        $update_stmt->close();
    } else {
        // Insert new product into cart
        $stmt = $conn->prepare("INSERT INTO cart (product_id, name, image, price, original_price, size, product_type, 
            product_version, product_description, top_notes, middle_notes, base_notes, product_category, product_gender, 
            lot_number, purchase_date, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssssssss", $product_id, $name, $image, $price, $original_price, $size, 
            $product_type, $product_version, $product_description, $top_notes, $middle_notes, $base_notes, 
            $product_category, $product_gender, $lot_number, $purchase_date, $quantity);

        if ($stmt->execute()) {
            echo "Product added to cart!";
        } else {
            echo "Error adding to cart.";
        }

        $stmt->close();
    }

    $check_stmt->close();
} else {
    echo "Invalid data received.";
}

$conn->close();
?>
