<?php
//require 'auth.php'; // auth check

require 'config.php'; // database connection

// Fetch order details
$sql = "
SELECT 
    ordermaster.order_id,
    ordermaster.date,
    ordermaster.type,
    ordermaster.client_id,
    ordermaster.supplier_id,
    ordermaster.payment_method,
    ordermaster.total_amount,
    ordermaster.advance,
    ordermaster.due,
    CASE 
        WHEN supplier.supplier_id IS NOT NULL THEN CONCAT_WS(' ', supplier.comp_first_name, supplier.comp_middle_name, supplier.comp_last_name)
        ELSE 'Unknown'
    END AS party_name,
    -- Assuming there is a way to identify the product for the order, we use a subquery to get the general_name
    (SELECT product.general_name 
     FROM product 
     LIMIT 1) AS general_name -- Adjust the subquery based on your product-to-order relation
FROM 
    ordermaster
LEFT JOIN supplier ON ordermaster.supplier_id = supplier.supplier_id
ORDER BY ordermaster.date;
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

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
        /* General table styling */
        .table {
            border-collapse: collapse;
            background-color: #ffffff;
            /* White background for the table */
        }

        /* Header Styling (lighter color) */
        .table thead {
            background-color: #f1f3f5;
            /* Light grey background */
            color: #495057;
            /* Dark grey text for contrast */
            text-transform: uppercase;
            font-weight: bold;
        }

        .table thead th {
            text-align: center;
            padding: 12px;
            background-color: #0284c7;
        }

        /* Table body styling */
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
            color: #495057;
            /* Dark grey text */
        }

        /* Zebra striping for rows */
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
            /* Light grey for even rows */
        }

        /* Hover effect for rows */
        .table tbody tr:hover {
            background-color: #e2e6ea;
            /* Slightly darker hover color */
            cursor: pointer;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }

        /* Improve scrollbar appearance */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #343a40;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #495057;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Add a subtle shadow for the table */
        .table {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.27);
            /* Soft shadow */
        }

        /* Add professional button styles */
        .btn {
            border-radius: 5px;
            padding: 8px 15px;
        }

        .btn-primary {
            background-color: #0284c7;
            /* Soft blue for primary button */
            border-color: #0284c7;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red for danger button */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #0284c7;
            color: white;
            border-bottom: none;
        }

        .modal-title {
            font-weight: bold;
        }

        .modal-footer {
            border-top: none;
        }
    </style>

</head>

<body>
    <div class="row mw-100 mh-100">
        <!-- Sidebar -->
        <div class="col-3">
            <?php include("sidebar.php") ?>
        </div>
        <!-- Main Content -->
        <div id="main" class="col-9">
            <h2 class="mt-4 mb-4">Order Details</h2>
            <a href="./addForms/supplier/addSupplier.php"><button type="button" class="btn btn-primary mb-4">Add New supplier</button></a>
            <a href="./addForms/orders/addSupplierOrder.php"><button type="button" class="btn btn-secondary mb-4">Add New Supplier Order</button></a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Actions</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Product Ordered</th>
                            <th>Customer Name</th>
                            <th>Payment Method</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and output them
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>";

                                // Update button with conditional link based on order type
                                if ($row['type'] == "Sale") {
                                    echo "<a href='./updateForms/orders/updatesupplierOrder.php?order_id=" . urlencode($row['order_id']) . "'><button type='button' class='btn btn-primary mb-1'>Update</button></a>";
                                    // Show invoice button only for "Sale" type
                                    echo "<a href='./generateOrderInvoice.php?id=" . urlencode($row['order_id']) . "'><button type='button' class='btn btn-success mb-1'>Invoice</button></a>";
                                } else { // Assuming "Purchase" is the only other option
                                    echo "<a style='display:flex' href='./updateForms/orders/updateSupplierOrder.php?order_id=" . urlencode($row['order_id']) . "'><button type='button' class='btn btn-primary mb-1'>Update</button></a>";
                                    // Do not show invoice button for "Purchase" type
                                }

                                echo "<button type='button' class='btn btn-danger delete-btn' data-order-id='" . htmlspecialchars($row['order_id']) . "'>Delete</button>
        </td>";
                                echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['party_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                            }
                        } else {
                            echo "<tr><td colspan='22'>No orders found.</td></tr>";
                        }

                        // Close the connection
                        $conn->close();
                        ?>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <!-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this order?</p>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                    <input type="hidden" id="deleteOrderId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            // const confirmDeleteBtn = document.getElementById('confirmDelete');
            let orderIdToDelete = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    orderIdToDelete = this.dataset.orderId;
                    document.getElementById('   OrderId').value = orderIdToDelete;
                    deleteModal.show();
                });
            });

        //     confirmDeleteBtn.addEventListener('click', function() {
        //         const enteredPassword = document.getElementById('password').value;
        //         const orderId = document.getElementById('deleteOrderId').value;

        //         // AJAX request to server-side script for password verification and deletion
        //         const xhr = new XMLHttpRequest();
        //         xhr.open('POST', 'deleteOrder.php', true);
        //         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //         xhr.onload = function() {
        //             if (xhr.status === 200) {
        //                 const response = JSON.parse(xhr.responseText);
        //                 if (response.success) {
        //                     alert('Order deleted successfully.');
        //                     location.reload(); // Reload the page to reflect changes
        //                 } else {
        //                     alert(response.message);
        //                 }
        //             } else {
        //                 alert('An error occurred.');
        //             }
        //             deleteModal.hide();
        //         };
        //         xhr.send('order_id=' + encodeURIComponent(orderId) + '&password=' + encodeURIComponent(enteredPassword));
            });
        // });
    </script>
</body>

</html>