<?php
//require '../../auth.php'; // auth check

require '../../config.php';
// Get the batch code from the URL
$batch_code = isset($_GET['batch_code']) ? $_GET['batch_code'] : '';

// Query the product details based on the batch code
$sql = "SELECT * FROM product WHERE batch_code = '$batch_code'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if the product exists
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Product not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Update Product</h2>
        <form id="updateProductForm" action="./saveProduct.php" method="post">
            <input type="hidden" name="batch_code" value="<?php echo $batch_code; ?>">
            <input type="hidden" name="supplier_id" value="<?php echo $product['supplier_id']; ?>">

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="product_code" class="form-label">Product Code</label>
                    <input type="text" name="product_code" class="form-control to-fill"
                        value="<?php echo $product['product_code']; ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="general_name" class="form-label">General Name</label>
                    <input type="text" name="general_name" class="form-control to-fill"
                        value="<?php echo $product['general_name']; ?>" required>
                </div>

                <div class="col-md-3">
                    <label for="purchase_price" class="form-label">Purchase Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="purchase_price" class="form-control to-fill"
                            value="<?php echo $product['pp']; ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="selling_price" class="form-label">Selling Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="selling_price" class="form-control to-fill"
                            value="<?php echo $product['sp']; ?>" required>
                    </div>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-3">
                    <label for="margin_price" class="form-label">Margin Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="margin_price" class="form-control to-fill read-only"
                            value="<?php echo $product['mrgp']; ?>" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="product_life" class="form-label">Product Life (Months)</label>
                    <input type="number" name="product_life" class="form-control to-fill"
                        value="<?php echo $product['product_life']; ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-4">Update Product</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Calculate margin dynamically
            $(document).on('input', 'input[name="purchase_price"], input[name="selling_price"]', function() {
                const purchasePrice = parseFloat($('input[name="purchase_price"]').val()) || 0;
                const sellingPrice = parseFloat($('input[name="selling_price"]').val()) || 0;
                const margin = sellingPrice - purchasePrice;
                $('input[name="margin_price"]').val(margin.toFixed(2));
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>