<?php
//require 'auth.php'; // Make sure this includes necessary session checks
require 'config.php'; // Ensure this file uses MySQLi for the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $enteredPassword = $_POST['password'];

    // Fetch user's hashed password from the database (using MySQLi)
    $stmt = $conn->prepare("SELECT password_hash FROM user WHERE username = 'amba'");
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set
    $user = $result->fetch_assoc(); // Fetch the row as an associative array
    $stmt->close();

    if ($user && password_verify($enteredPassword, $user['password_hash'])) {
        // Password is correct, proceed with deletion and stock update

        // Start a transaction to ensure data consistency
        $conn->begin_transaction();

        try {
            // 1. Fetch order type and items for the given order_id
            $fetchOrderStmt = $conn->prepare("SELECT o.type, oi.batch_code, oi.quantity FROM orders o JOIN order_items oi ON o.order_id = oi.order_id WHERE o.order_id = ?");
            $fetchOrderStmt->bind_param("s", $order_id);
            $fetchOrderStmt->execute();
            $orderResult = $fetchOrderStmt->get_result();

            // Check if order exists
            if ($orderResult->num_rows === 0) {
                throw new Exception("No order found with the given order ID.");
            }

            $orderType = null;
            $itemsToUpdate = [];
            while ($row = $orderResult->fetch_assoc()) {
                if ($orderType === null) {
                    $orderType = $row['type']; // Get the order type from the first row
                }
                $itemsToUpdate[] = ['batch_code' => $row['batch_code'], 'quantity' => $row['quantity']];
            }
            $fetchOrderStmt->close();

            // 2. Update stock quantities based on order type and fetched items
            foreach ($itemsToUpdate as $item) {
                if ($orderType == "Sale") {
                    // Add stock for Sale type
                    $updateStockStmt = $conn->prepare("UPDATE stock SET quantity = quantity + ? WHERE batch_code = ?");
                } elseif ($orderType == "Purchase") {
                    // Subtract stock for Purchase type
                    $updateStockStmt = $conn->prepare("UPDATE stock SET quantity = quantity - ? WHERE batch_code = ?");
                } else {
                    throw new Exception("Invalid order type: " . $orderType);
                }

                $updateStockStmt->bind_param("is", $item['quantity'], $item['batch_code']);
                $updateStockStmt->execute();

                if ($updateStockStmt->affected_rows === 0) {
                    throw new Exception("Failed to update stock for batch code: " . $item['batch_code']);
                }

                $updateStockStmt->close();
            }

            // 3. Delete the order from the orders table
            $deleteStmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
            $deleteStmt->bind_param("s", $order_id);
            $deleteStmt->execute();

            if ($deleteStmt->affected_rows > 0) {
                // Commit the transaction if everything was successful
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Order deleted and stock updated successfully.']);
            } else {
                throw new Exception("Failed to delete order. No order found with the given order ID.");
            }
            $deleteStmt->close();
        } catch (Exception $e) {
            // Rollback the transaction if any error occurred
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        // Invalid password
        echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
