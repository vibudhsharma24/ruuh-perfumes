<?php
require 'config.php'; // Database connection

// Generate a unique order_id
$order_id = "ORD" . time();
$order_type = "Purchase";

// Fetch suppliers
$supplier_query = "
    SELECT 
        supplier_id, 
        CONCAT(IFNULL(comp_first_name, ''), ' ', IFNULL(comp_middle_name, ''), ' ', IFNULL(comp_last_name, '')) AS company_name 
    FROM supplier
";

$supplier_result = $conn->query($supplier_query);  // Execute the query correctly

if (!$supplier_result) {
    die("Supplier Query Failed: " . $conn->error);
}

// Fetch products
$product_query = "SELECT product_code, general_name, pp, sp, mrgp, product_life, batch_code FROM product";
$product_result = $conn->query($product_query);

if (!$product_result) {
    die("Product Query Failed: " . $conn->error);
}

$products = [];
while ($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
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

        h2 {
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

        .due {
            color: #dc3545;
            /* Red color for due amount */
            font-weight: 600;
        }

        .profit {
            color: #28a745;
            /* Green color for profit */
            font-weight: 600;
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

        .btn-success {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }

        .btn-success:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .order-item {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Reorder Products</h2>
        <form id="addOrderForm" action="saveSupplierOrder.php" method="post">
            <!-- Hidden fields for order_id and order_type -->
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="order_type" value="<?php echo $order_type; ?>">

            <!-- Supplier and Payment Method Selection -->
            <div class="row mb-4" style="z-index: 2; position: sticky; top: 0;background: var(--bs-gray-100);">
                <div class="row mb-4">
                    <div class="mb-3 col-md-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-select to-fill" required>
                        <option value="">Select Supplier</option>
                        <?php
                        // Loop through and render options for suppliers
                        while ($row = $supplier_result->fetch_assoc()) {
                            echo "<option value='{$row['supplier_id']}'>{$row['company_name']}</option>";
                        }
                        ?>
                    </select>


                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="order_date" class="form-label">Order Date</label>
                        <input type="date" id="order_date" name="order_date" class="form-control to-fill" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" class="form-control to-fill" required>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select payment-method to-fill" required>
                            <option value="">Select</option>
                            <option value="upi">UPI</option>
                            <option value="cash">Cash</option>
                            <option value="debit-card">Debit Card</option>
                            <option value="credit-card">Credit Card</option>
                            <option value="net-banking">Net Banking</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="mb-3 col-md-2">
                        <label for="advance" class="form-label">Advance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" id="advance" name="advance" class="form-control to-fill" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="due" class="form-label">Due</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" id="due" name="due" class="form-control due read-only" readonly>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Order Items Container -->
            <div id="orderItemsContainer">
                <div class="order-item py-4 px-4 mb-4">
                    <!-- Product Row 1 -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="product" class="form-label">Product</label>
                            <select name="product[]" class="form-select product-select to-fill" required>
                                <option value="">Select Product</option>
                                <?php foreach ($products as $product) { ?>
                                    <option value='<?php echo json_encode($product); ?>'>
                                        <?php echo htmlspecialchars($product['general_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="batch_code" class="form-label">Batch Code</label>
                            <input type="text" name="batch_code[]" class="form-control batch-code read-only" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="cost_price_per_unit" class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="cost_price_per_unit[]" class="form-control cost-price-per-unit read-only" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="selling_price_per_unit" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="selling_price_per_unit[]" class="form-control selling-price-per-unit read-only" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" name="quantity[]" class="form-control quantity to-fill" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label for="discount" class="form-label">Discount</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="discount[]" class="form-control discount to-fill" min="0" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                    </div>

                    <!-- Product Row 2 -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="freight" class="form-label">Freight Charges</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="freight[]" class="form-control freight to-fill" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="tax_type" class="form-label">Tax Type</label>
                            <select name="tax_type[]" class="form-select tax-type to-fill" required>
                                <option value="">Select</option>
                                <option value="in_state">In State</option>
                                <option value="out_of_state">Out of State</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tax_percent" class="form-label">Tax Amount (%)</label>
                            <input type="number" step="0.01" name="tax_percent[]" class="form-control tax-percent to-fill" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label for="cgst" class="form-label">CGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="cgst[]" class="form-control cgst" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sgst" class="form-label">SGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="sgst[]" class="form-control sgst" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="igst" class="form-label">IGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="igst[]" class="form-control igst" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Product Row 3 -->
                    <div class="row mb-5">
                        <div class="col-md-2">
                            <label for="billing_amount" class="form-label">Billing Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="billing_amount[]" class="form-control billing-amount" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-row">Remove Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Row and Submit Buttons -->
            <button type="button" class="btn btn-secondary mb-4" id="addRow">Add Item</button>
            <button type="submit" class="btn btn-primary mx-2 mb-4">Save Order</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Function to calculate the total due
            function calculateDue() {
                let totalBillingAmount = 0;

                // Sum up all billing_amount inputs
                $('.billing-amount').each(function() {
                    const value = parseFloat($(this).val()) || 0;
                    totalBillingAmount += value;
                });

                // Get the advance value
                const advance = parseFloat($('#advance').val()) || 0;

                // Calculate the due amount
                const due = totalBillingAmount - advance;

                // Update the due input
                $('#due').val(due.toFixed(2));
            }

            // Function to calculate billing amount and other fields
            function calculateBilling(row) {
                const pricePerUnit = parseFloat(row.find('.selling-price-per-unit').val()) || 0;
                const costPerUnit = parseFloat(row.find('.cost-price-per-unit').val()) || 0;
                const quantity = parseFloat(row.find('.quantity').val()) || 0;
                const discount = parseFloat(row.find('.discount').val()) || 0;
                const freight = parseFloat(row.find('.freight').val()) || 0;
                const taxType = row.find('.tax-type').val();
                const taxAmount = parseFloat(row.find('.tax-percent').val()) || 0;

                // Calculate the item total
                const itemTotal = pricePerUnit * quantity;
                const totalDiscount = (discount / 100) * itemTotal;
                const itemTotalAfterDiscount = itemTotal - totalDiscount;

                // Calculate total after freight (for tax calculation)
                const totalAfterFreight = itemTotalAfterDiscount + freight;

                let cgst = 0,
                    sgst = 0,
                    igst = 0;
                if (taxType === 'in_state') {
                    cgst = sgst = (taxAmount / 2) * totalAfterFreight / 100;
                } else if (taxType === 'out_of_state') {
                    igst = (taxAmount * totalAfterFreight) / 100;
                }

                // Calculate the billing amount (AFTER TAX)
                const taxTotal = cgst + sgst + igst;
                const billingAmount = totalAfterFreight + taxTotal;

                // Update the fields
                row.find('.cgst').val(cgst.toFixed(2));
                row.find('.sgst').val(sgst.toFixed(2));
                row.find('.igst').val(igst.toFixed(2));
                row.find('.billing-amount').val(billingAmount.toFixed(2));

                calculateDue();
            }

            // Event listener for changes in product selection
            $(document).on('change', '.product-select', function() {
                const productData = $(this).val();
                if (productData) {
                    const product = JSON.parse(productData);
                    const row = $(this).closest('.order-item');
                    row.find('.batch-code').val(product.batch_code);
                    row.find('.cost-price-per-unit').val(product.pp);
                    row.find('.selling-price-per-unit').val(product.sp);

                    // After updating selling price, recalculate billing
                    calculateBilling(row);
                }
            });

            // Event listener for changes in quantity, selling price, discount, freight, tax type, tax amount
            $(document).on('input change', '.quantity, .selling-price-per-unit, .discount, .freight, .tax-type, .tax-percent', function() {
                const row = $(this).closest('.order-item');
                calculateBilling(row);
            });

            // Listen for changes in billing_amount and advance inputs to calculate due
            $(document).on('input', '.billing-amount, #advance', calculateDue);

            // Add a new row
            $('#addRow').click(function() {
                const newOrderItem = `
                   <div id="orderItemsContainer">
                <div class="order-item py-4 px-4 mb-4">
                    <!-- Product Row 1 -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="product" class="form-label">Product</label>
                            <select name="product[]" class="form-select product-select to-fill" required>
                                <option value="">Select Product</option>
                                <?php foreach ($products as $product) { ?>
                                    <option value='<?php echo json_encode($product); ?>'>
                                        <?php echo htmlspecialchars($product['general_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="batch_code" class="form-label">Batch Code</label>
                            <input type="text" name="batch_code[]" class="form-control batch-code read-only" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="cost_price_per_unit" class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="cost_price_per_unit[]" class="form-control cost-price-per-unit read-only" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="selling_price_per_unit" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="selling_price_per_unit[]" class="form-control selling-price-per-unit read-only" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" name="quantity[]" class="form-control quantity to-fill" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label for="discount" class="form-label">Discount</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="discount[]" class="form-control discount to-fill" min="0" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                    </div>

                    <!-- Product Row 2 -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="freight" class="form-label">Freight Charges</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="freight[]" class="form-control freight to-fill" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="tax_type" class="form-label">Tax Type</label>
                            <select name="tax_type[]" class="form-select tax-type to-fill" required>
                                <option value="">Select</option>
                                <option value="in_state">In State</option>
                                <option value="out_of_state">Out of State</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tax_percent" class="form-label">Tax Amount (%)</label>
                            <input type="number" step="0.01" name="tax_percent[]" class="form-control tax-percent to-fill" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label for="cgst" class="form-label">CGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="cgst[]" class="form-control cgst" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sgst" class="form-label">SGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="sgst[]" class="form-control sgst" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="igst" class="form-label">IGST</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="igst[]" class="form-control igst" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Product Row 3 -->
                    <div class="row mb-5">
                        <div class="col-md-2">
                            <label for="billing_amount" class="form-label">Billing Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="billing_amount[]" class="form-control billing-amount" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-row">Remove Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                `;
                $('#orderItemsContainer').append(newOrderItem);
            });

            // Remove a row and recalculate due
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.order-item').remove();
                calculateDue();
            });

            // Initial calculation in case there are pre-filled billing amounts
            calculateDue();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>