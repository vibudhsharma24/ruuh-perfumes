<?php

require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get supplier ID from form
    $supplier_id = $_POST['supplier_id'];

    // Loop through each product item
    $product_ids = $_POST['product_id'];
    $batch_codes = $_POST['batch_code'];
    $general_names = $_POST['general_name'];
    $purchase_prices = $_POST['purchase_price'];
    $selling_prices = $_POST['selling_price'];
    $margins = $_POST['margin'];
    $product_lifes = $_POST['product_life'];
    $stocks = $_POST['stock'];

    // Start transaction
    $conn->begin_transaction();

    try {
        for ($i = 0; $i < count($product_ids); $i++) {
            // Insert product details into products table
            $product_id = $product_ids[$i];
            $batch_code = $batch_codes[$i];
            $general_name = $general_names[$i];
            $purchase_price = $purchase_prices[$i];
            $selling_price = $selling_prices[$i];
            $margin = $margins[$i];
            $product_life = $product_lifes[$i];
            $stock = $stocks[$i];

            // Insert into products table
            $sql = "INSERT INTO product (product_code, general_name, pp, sp, mrgp, product_life, batch_code, supplier_id)
                    VALUES ('$product_id', '$general_name', '$purchase_price', '$selling_price', '$margin', '$product_life', '$batch_code', '$supplier_id')";

            if (!$conn->query($sql)) {
                throw new Exception("Error inserting product: " . $conn->error);
            }

            // Update stock table
            $sql_stock = "INSERT INTO stock (batch_code, quantity, mas)
                          VALUES ('$batch_code', '$stock', 10) 
                          ON DUPLICATE KEY UPDATE quantity = quantity + '$stock'";

            if (!$conn->query($sql_stock)) {
                throw new Exception("Error updating stock: " . $conn->error);
            }
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Product Added successfully!');
         location.replace('../../product.php');
        </script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
