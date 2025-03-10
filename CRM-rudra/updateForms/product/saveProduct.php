<?php
//require '../../auth.php'; // auth check

require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $supplier_id = $_POST['supplier_id'];

    $product_code = $_POST['product_code'];
    $batch_code = $_POST['batch_code'];
    $general_name = $_POST['general_name'];
    $purchase_price = $_POST['purchase_price'];
    $selling_price = $_POST['selling_price'];
    $margin_price = $_POST['margin_price'];
    $product_life = $_POST['product_life'];


    $conn->begin_transaction();

    try {
        // Insert into products table
        $sql = "UPDATE product SET product_code='$product_code', general_name='$general_name', pp=$purchase_price, sp=$selling_price, mrgp=$margin_price, product_life=$product_life, batch_code='$batch_code', supplier_id=$supplier_id WHERE batch_code='$batch_code'";

        if (!$conn->query($sql)) {
            throw new Exception("Error inserting product: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Product Details Updated successfully!');
    location.replace('../../product.php');
    </script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
