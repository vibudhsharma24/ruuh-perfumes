<?php
//require '../auth.php'; // auth check

require '../config.php';
// Update stock if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $products = $_POST['product'];
    $quantities = $_POST['quantity'];
    // Loop through all the products and update the stock
    foreach ($products as $index => $productData) {
        $product = json_decode($productData, true);  // Decode product JSON
        $quantity = (int) $quantities[$index];  // Get the quantity for the product
        $batch_code = $product['batch_code'];  // Get batch code for the product
        // Prepare the query to update stock (assuming 'quantity' and 'batch_code' exist in 'stock' table)
        $update_query = "UPDATE stock SET quantity = $quantity WHERE batch_code = '$batch_code'";
        if ($conn->query($update_query) === TRUE) {
            // If the update is successful, continue to the next iteration
            continue;
        } else {
            echo "Error: " . $update_query . "<br>" . $conn->error;
        }
    }
    // Redirect or show a success message after updating the stock
    echo "<script>alert('Stock Updated successfully!');
    location.replace('../stock.php');
    </script>";
}
// Fetch products for the dropdown
$products_result = $conn->query("
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
");
$products = [];
while ($row = $products_result->fetch_assoc()) {
    $products[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock</title>
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
        <h2 class="mb-4">Update Stock</h2>
        <form id="addOrderForm" action="updateStock.php" method="post">
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
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity[]" class="form-control quantity to-fill" min="1"
                                required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Row and Submit Buttons -->
            <button type="button" class="btn btn-secondary mb-4 w-25" id="addRow">Add Item</button>
            <button type="submit" class="btn btn-primary mx-2 mb-4 w-25">Save</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Event listener for changes in product selection
            $(document).on('change', '.product-select', function() {
                const productData = $(this).val();
                if (productData) {
                    const product = JSON.parse(productData);
                    const row = $(this).closest('.order-item');
                    row.find('.batch-code').val(product.batch_code);
                }
            });

            // Add a new row
            $('#addRow').click(function() {
                const newOrderItem = `
                    <div class="order-item py-4 px-4 mb-4">
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
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity[]" class="form-control quantity to-fill" min="1" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
                $('#orderItemsContainer').append(newOrderItem);
            });

            // Remove a row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.order-item').remove();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>