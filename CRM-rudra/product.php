<?php
//require 'auth.php'; // auth check

require 'config.php'; // database connection

// Fetch product details
$sql = "
    SELECT 
        product_code,
        general_name,
        pp,
        sp,
        mrgp,
        product_life,
        batch_code
    FROM 
        product
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Product</title>
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

        /* Modal Styles */
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
            <h2 class="mb-4 mt-4">Product Details</h2>
            <a href="./addForms/product/addProduct.php"><button type="button" class="btn btn-primary mb-4">Add New
                    Product</button></a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Actions</th>
                            <th>Product Code</th>
                            <th>Batch Code</th>
                            <th>General Name</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Margin</th>
                            <th>Product Life (Months)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and output them
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>
                                        <a href='./updateForms/product/updateProduct.php?batch_code=" . urlencode($row['batch_code']) . "'><button type='button' class='btn btn-primary mb-2'>Update</button></a>
                                        <button type='button' class='btn btn-danger delete-btn' data-batch-code='" . htmlspecialchars($row['batch_code']) . "'>Delete</button>
                                      </td>";
                                echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['batch_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['general_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['pp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['sp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['mrgp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['product_life']) . "</td>";
                            }
                        } else {
                            echo "<tr><td colspan='14'>No Products found.</td></tr>";
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password"
                        required>
                    <input type="hidden" id="deleteBatchCode">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            let batchCodeToDelete = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    batchCodeToDelete = this.dataset.batchCode;
                    document.getElementById('deleteBatchCode').value = batchCodeToDelete;
                    deleteModal.show();
                });
            });

            confirmDeleteBtn.addEventListener('click', function() {
                const enteredPassword = document.getElementById('password').value;
                const batchCode = document.getElementById('deleteBatchCode').value;

                // AJAX request to server-side script for password verification and deletion
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'deleteProduct.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert('Product deleted successfully.');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert('An error occurred.');
                    }
                    deleteModal.hide();
                };
                xhr.send('batch_code=' + encodeURIComponent(batchCode) + '&password=' + encodeURIComponent(enteredPassword));
            });
        });
    </script>

</body>

</html>