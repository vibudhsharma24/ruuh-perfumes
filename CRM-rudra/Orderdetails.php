<?php
// require 'auth.php'; // auth check

require 'config.php'; // database connection

// Get the order_id from the query string
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// First, fetch the order details from the ordermaster table
$sql_order = "SELECT 
                order_id, 
                date AS order_date, 
                total_amount, 
                client_id 
              FROM 
                ordermaster 
              WHERE 
                order_id = $order_id";

// Execute the query to fetch order details
$order_result = $conn->query($sql_order);

// Check if order details are found
if ($order_result && $order_row = $order_result->fetch_assoc()) {
    $client_id = $order_row['client_id'];  // Get the client_id from the order

    // Now, fetch the customer details from the customer_master table based on client_id
    $sql_customer = "SELECT 
                        customer_name, 
                        address 
                    FROM 
                        customer_master 
                    WHERE 
                        customer_id = '$client_id'";

    // Execute the query to fetch customer details
    $customer_result = $conn->query($sql_customer);

    if ($customer_result && $customer_row = $customer_result->fetch_assoc()) {
        // Order and customer details are found
        $customer_name = $customer_row['customer_name'];
        $customer_address = $customer_row['address'];

        // Prepare the data for display
        $order_date = $order_row['order_date'];
        $total_amount = $order_row['total_amount'];
    } else {
        // No customer details found
        $customer_name = $customer_address = "Not Available";
    }

} else {
    // If no order found, show an error
    $order_date = $total_amount = $customer_name = $customer_address = "Not Available";
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Order Details</title>
    <style>
        body {
            background-color: #f7f8fa;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        h1 {
            color: #0284c7;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #0284c7;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .card-text strong {
            color: #333;
        }

        .alert {
            font-size: 1.2rem;
            font-weight: bold;
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            border-radius: 8px;
        }
    </style>

</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Order Details</h1>

        <?php if ($order_date != "Not Available"): ?>
            <div class="row">
                <!-- Order Details Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Information</h5>
                            <p class="card-text"><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
                            <p class="card-text"><strong>Order Date:</strong> <?= htmlspecialchars($order_date) ?></p>
                            <p class="card-text"><strong>Total Amount:</strong> <?= htmlspecialchars($total_amount) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Customer Details Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Customer Information</h5>
                            <p class="card-text"><strong>Customer Name:</strong> <?= htmlspecialchars($customer_name) ?></p>
                            <p class="card-text"><strong>Customer Address:</strong> <?= htmlspecialchars($customer_address) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                Order details or customer details not found.
            </div>
        <?php endif; ?>

    </div>
</body>

</html>
