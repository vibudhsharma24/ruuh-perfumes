<?php
require '../../config.php';

if (isset($_GET['order_id'])) {
    $order_id = $conn->real_escape_string($_GET['order_id']);

    // Fetch order details, including client and supplier names
    $orderDetails = $conn->query("
        SELECT 
            orders.date AS order_date,
            CONCAT(client.comp_first_name, ' ', client.comp_middle_name, ' ', client.comp_last_name) AS client_name,
            CONCAT(supplier.comp_first_name, ' ', supplier.comp_middle_name, ' ', supplier.comp_last_name) AS supplier_name,
            orders.client_id,
            orders.supplier_id
        FROM orders
        LEFT JOIN client ON orders.client_id = client.id
        LEFT JOIN supplier ON orders.supplier_id = supplier.id
        WHERE orders.order_id = '$order_id'
    ")->fetch_assoc();

    // Fetch products grouped by order ID
    $products = $conn->query("
        SELECT 
            product.general_name,
            order_items.batch_code,
            order_items.quantity
        FROM order_items
        JOIN product ON order_items.batch_code = product.batch_code
        WHERE order_items.order_id = '$order_id'
    ");

    $productList = [];
    while ($row = $products->fetch_assoc()) {
        $productList[] = $row;
    }


    // Determine which name to use
    $partyName = '';
    if (!empty($orderDetails['client_id'])) {
        $partyName = $orderDetails['client_name'];
    } elseif (!empty($orderDetails['supplier_id'])) {
        $partyName = $orderDetails['supplier_name'];
    } else {
        $partyName = 'N/A'; // Default if neither client nor supplier is found
    }


    // Response
    echo json_encode([
        'order_date' => $orderDetails['order_date'] ?? 'N/A',
        'party_name' => $partyName,
        'products' => $productList
    ]);
}
