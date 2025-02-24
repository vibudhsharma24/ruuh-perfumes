<?php
//require '../../auth.php'; // auth check

require '../../config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bs-primary-rgb: 2, 132, 199;
            /* Define the primary color variable */
        }

        body {
            background-color: #f8f9fa;
            /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Professional font */
        }

        .container {
            max-width: 1200px;
        }

        h1 {
            color: #0284c7;
            /* Primary color for headings */
            font-weight: 600;
            margin-bottom: 30px !important;
            /* More space below the title */
            text-align: center;
        }

        .form-label {
            font-weight: 600;
            color: #343a40;
            /* Dark gray for labels */
        }

        .form-select,
        .form-control {
            border-radius: 0.375rem;
            /* Rounded corners for inputs */
            border: 1px solid #ced4da;
            /* Subtle border color */
            padding: 0.5rem 0.75rem;
            /* Comfortable padding */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            /* Smooth transition for focus */
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #86b7fe;
            /* Lighter blue border on focus */
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.25);
            /* Primary color shadow on focus */
        }

        .input-group-text {
            background-color: #e9ecef;
            /* Light gray background for input group text */
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            /* Rounded corners */
            font-weight: 500;
        }

        .order-item {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* More pronounced shadow */
            background-color: white;
            border: none;
            /* Remove border */
            border-radius: 0.5rem;
            /* Larger rounded corners */
            padding: 20px;
            /* More padding */
            margin-bottom: 20px !important;
            /* More space between items */
        }

        .read-only {
            background-color: #e9ecef;
            /* Light gray background for read-only fields */
        }

        .to-fill {
            border: 1.75px solid #0284c7;
            /* Primary color for fields to be filled */
        }

        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }

        .btn-primary:hover {
            background-color: #025ea1;
            /* Darker shade on hover */
            border-color: #025ea1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }

        .btn-secondary {
            background-color: #6c757d;
            /* Gray secondary color */
            border-color: #6c757d;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            /* Darker shade of gray on hover */
            border-color: #5a6268;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }

        .btn-danger {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }

        .btn-danger:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add Product</h1>
        <form id="addOrderForm" action="saveProduct.php" method="post">
            <!-- Supplier Selection -->
            <div class="row mb-4">
                <div class="mb-3 col-md-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select id="supplier_id" name="supplier_id" class="form-select to-fill" required>
                        <option value="">Select Supplier</option>
                        <?php
                        $suppliers_result = $conn->query("SELECT id, CONCAT(comp_first_name, ' ', comp_middle_name, ' ', comp_last_name) AS company_name FROM supplier");
                        while ($row = $suppliers_result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['company_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Product Items Container -->
            <div id="itemsContainer">
                <div class="order-item py-4 px-4 mb-4">
                    <!-- Product Row 1 -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="product_id" class="form-label">Product Id</label>
                            <input type="text" name="product_id[]" class="form-control product-id to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="batch_code" class="form-label">Batch Code</label>
                            <input type="text" name="batch_code[]" class="form-control to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="general_name" class="form-label">General Name</label>
                            <input type="text" name="general_name[]" class="form-control to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="purchase_price[]"
                                    class="form-control purchase-price to-fill" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="selling_price[]" class="form-control selling-price to-fill"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="margin" class="form-label">Margin</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="margin[]" class="form-control margin to-fill" min="0"
                                    readonly>
                            </div>
                        </div>

                    </div>

                    <!-- Product Row 2 -->
                    <div class="row mb-3">

                        <div class="col-md-2">
                            <label for="product_life" class="form-label">Shelf Life (in months)</label>
                            <input type="number" name="product_life[]" class="form-control to-fill" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" name="stock[]" class="form-control to-fill" min="0" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Row and Submit Buttons -->
            <button type="button" class="btn btn-secondary mb-4" id="addRow">Add Item</button>
            <button type="submit" class="btn btn-primary mx-2 mb-4">Save Products</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Add a new row
            $('#addRow').click(function() {
                const newOrderItem = `
                <div class="order-item py-4 px-4 mb-4">
                    <!-- Product Row 1 -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="product_id" class="form-label">Product Id</label>
                            <input type="text" name="product_id[]" class="form-control product-id to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="batch_code" class="form-label">Batch Code</label>
                            <input type="text" name="batch_code[]" class="form-control to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="general_name" class="form-label">General Name</label>
                            <input type="text" name="general_name[]" class="form-control to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="chemical_name" class="form-label">Chemical Name</label>
                            <input type="text" name="chemical_name[]" class="form-control to-fill" required>
                        </div>
                        <div class="col-md-2">
                            <label for="size" class="form-label">Chemical Size</label>
                            <input type="text" name="size[]" class="form-control to-fill" required>
                        </div>
                    </div>
                    <!-- Product Row 2 -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="purchase_price[]" class="form-control purchase-price to-fill" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="selling_price[]" class="form-control selling-price to-fill" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="margin" class="form-label">Margin</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="margin[]" class="form-control margin to-fill" min="0" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="product_life" class="form-label">Shelf Life (in months)</label>
                            <input type="number" name="product_life[]" class="form-control to-fill" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" name="stock[]" class="form-control to-fill" min="0" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </div>
                    </div>
                </div>`;
                $('#itemsContainer').append(newOrderItem);
            });

            // Remove a row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.order-item').remove();
            });

            // Calculate margin dynamically
            $(document).on('input', '.purchase-price, .selling-price', function() {
                const row = $(this).closest('.order-item');
                const purchasePrice = parseFloat(row.find('.purchase-price').val()) || 0;
                const sellingPrice = parseFloat(row.find('.selling-price').val()) || 0;
                const margin = sellingPrice - purchasePrice;
                row.find('.margin').val(margin.toFixed(2));
            });

            // Validate stock quantity
            $(document).on('input', 'input[name="stock[]"]', function() {
                const stockValue = $(this).val();
                if (stockValue < 10) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                    $(this).after('<div class="invalid-feedback">Stock quantity must be at least 10.</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            });

            // Prevent form submission if validation fails
            $('#addOrderForm').submit(function(e) {
                let valid = true;
                $('input[name="stock[]"]').each(function() {
                    if ($(this).val() < 10) {
                        valid = false;
                    }
                });
                if (!valid) {
                    e.preventDefault();
                    alert("Please ensure all stock quantities are at least 10.");
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>