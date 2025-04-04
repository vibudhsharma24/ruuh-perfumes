<?php
session_start();

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart updates from JavaScript
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    $_SESSION['cart'] = json_decode($_POST['cart_data'], true);
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $key = $_POST['update_item'];
    $new_quantity = max(1, (int)$_POST['new_quantity']);
    $_SESSION['cart'][$key]['quantity'] = $new_quantity;
}

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $removeKey = $_POST['remove_item'];
    unset($_SESSION['cart'][$removeKey]);
}

// Calculate totals
$cartItems = $_SESSION['cart'];
$subtotal = 0;
$itemCount = 0;

foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $itemCount += $item['quantity'];
}

// GST Calculation (18%)
$gstRate = 0.18;
$gstAmount = $subtotal * $gstRate;
$totalAmount = $subtotal + $gstAmount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Ruuh Perfumes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo-container {
            text-align: center;
            padding: 20px 0;
            background-color: #000;
            color: white;
        }
        .checkout-form {
            flex: 3;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-right: 20px;
        }
        .order-summary {
            flex: 2;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }
        h1, h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="tel"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row > div {
            flex: 1;
        }
        .product-item {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .product-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            background-color: #f0f0f0;
        }
        .product-details {
            flex: 1;
        }
        .product-price {
            font-weight: bold;
        }
        .subtotal-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-weight: bold;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 15px;
        }
        .discount-code {
            display: flex;
            margin: 15px 0;
        }
        .discount-code input {
            flex: 1;
            margin-right: 10px;
        }
        .discount-code button {
            padding: 10px 15px;
            background-color: #f0f0f0;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #1a2639;
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
        .payment-options {
            margin: 20px 0;
        }
        .payment-option {
            margin-bottom: 10px;
        }
        .login-link {
            text-align: right;
        }
        .back-to-shop {
            display: inline-block;
            margin-bottom: 20px;
            color: #1a2639;
            text-decoration: none;
        }
        .remove-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }
        .remove-button:hover {
            background-color: #ff1a1a;
        }
    </style>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartData = JSON.parse(localStorage.getItem('cart')) || {};
            document.getElementById('cart_data').value = JSON.stringify(cartData);
            document.getElementById('cart_form').submit();
        });

        function updateQuantity(index) {
            let quantityInput = document.getElementById('quantity_' + index);
            let newQuantity = quantityInput.value;

            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'update_item=' + index + '&new_quantity=' + newQuantity
            });
            if (!sessionStorage.getItem("reloaded")) {
             sessionStorage.setItem("reloaded", "true");
    location.reload();
};
        }
    </script>
</head>
<body>
    <div class="logo-container">
        <h1>RUUH PERFUMES</h1>
    </div>

    <div class="container">
        <div class="checkout-form">
            <div class="login-link">
                <a href="login.php">Log in</a>
            </div>
            <a href="view_products.php" class="back-to-shop">← Back to Shop</a>
            <h1>Checkout</h1>

            <form id="cart_form" method="post" action="">
                <input type="hidden" id="cart_data" name="cart_data">
            </form>

            <!-- Checkout Form -->
            <form method="post" action="process_order.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="newsletter" name="newsletter">
                    <label for="newsletter">Email me with news and offers</label>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <select id="state" name="state" required>
                            <option value="">Select State</option>
                            <option value="AP">Andhra Pradesh</option>
                            <option value="AR">Arunachal Pradesh</option>
                            <option value="AS">Assam</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pincode">PIN Code</label>
                        <input type="text" id="pincode" name="pincode" required>
                    </div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="different_billing" name="different_billing">
                    <label for="different_billing">Use different billing address</label>
                </div>

                <h2>Payment</h2>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" id="cashfree" name="payment_method" value="cashfree" checked>
                        <label for="cashfree">Cashfree Payments (UPI, Cards, Wallets, NetBanking)</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="cod" name="payment_method" value="cod">
                        <label for="cod">Cash on Delivery (COD)</label>
                    </div>
                </div>

                <button type="submit" name="complete_order" class="btn-primary">Complete Order</button>
            </form>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>

            <?php if (empty($cartItems)): ?>
                <p>No items in your order. Please add items from the shop.</p>
            <?php else: ?>
                <?php foreach ($cartItems as $key => $item): ?>
                <div class="product-item">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="60">
                    </div>
                    <div class="product-details">
                        <div><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="product-price">₹<?php echo number_format($item['price'], 2); ?> (<?php echo $item['size']; ?> ml)</div>
                        <div>Quantity: <?php echo $item['quantity']; ?></div>
                    </div>
                    <form method="post" action="">
                        <input type="hidden" name="remove_item" value="<?php echo $key; ?>">
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="subtotal-row">
                <div>Subtotal · <?php echo $itemCount; ?> items</div>
                <div>₹<?php echo number_format($subtotal, 2); ?></div>
            </div>

            <div class="subtotal-row">
                <div>GST (18%)</div>
                <div>₹<?php echo number_format($gstAmount, 2); ?></div>
            </div>

            <div class="subtotal-row">
                <div>Shipping</div>
                <div>Enter shipping address</div>
            </div>

            <div class="total-row">
                <div>Total</div>
                <div>INR ₹<?php echo number_format($totalAmount, 2); ?></div>
            </div>
        </div>
    </div>
</body>
</html>