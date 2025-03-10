<?php
// Include the database connection (or define it here)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch product data based on the product_id passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the database
    $query = "SELECT * FROM product_details WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the product is found
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found.";
        exit;
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Product ID is missing.";
    exit;
}

// Close the database connection after the query
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Paradise Perfumes - Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sticky {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-bottom: 1px solid #e5e7eb;

        }
    </style>
</head>
<body class="bg-white text-gray-800">
    <header class="border-b">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">RUUH PERFUMES</div>
            <nav class="space-x-6">
                <a class="text-gray-600 hover:text-black" href="#">HOME</a>
                <a class="text-gray-600 hover:text-black" href="#">COLLECTIONS</a>
                <a class="text-gray-600 hover:text-black" href="#">ORDER TESTERS</a>
                <a class="text-gray-600 hover:text-black" href="#">OUD COLLECTION</a>
                <a class="text-gray-600 hover:text-black" href="#">COMBO</a>
                <a class="text-gray-600 hover:text-black" href="#">WINTER SALE</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto py-8 px-6">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-1/3 flex flex-col items-center">
                <!-- Dynamic Product Images -->
                <img alt="Product Image" class="h-80 w-80 mb-4"  id="mainImage" src="uploads/<?php echo $product['product_image']; ?>" />
                
                
                <div class="flex space-x-2">
                <?php 
for ($i = 1; $i <= 10; $i++) {
    $imageKey = "additional_image" . $i;
    
    if (!empty($product[$imageKey])) {
        // Ensure correct image path
        $imagePath = $product[$imageKey];
        if (!str_starts_with($imagePath, "uploads/")) {
            $imagePath = "uploads/" . $imagePath;
        }

        echo '<img alt="Thumbnail" class="size-16 border border-gray-300 cursor-pointer" 
                onclick="changeImage(\'' . htmlspecialchars($imagePath) . '\')" 
                src="' . htmlspecialchars($imagePath) . '"/>';
    }
}
?>
                </div>

            </div>
            <div class="lg:w-2/3 lg:pl-12 mt-8 lg:mt-0">
                <div class="sticky py-4">
                    <!-- Dynamic Product Name and Price -->
                    <h1 class="text-3xl font-bold" id="productName"><?php echo $product['product_name']; ?></h1>
                    <div class="text-xl text-black-600 mt-2" id="productPrice"><?php echo "Rs. " . $product['selling_price']; ?></div>
                    <div class="text-xl text-red-600 mt-2 text-decoration: line-through" id="crossedPrice"><?php echo "Rs. " . $product['crossed_price']; ?></div>
                    <div class="mt-4 flex items-center">
                        <span class="mr-4">Quantity:</span>
                        <div class="flex items-center border border-gray-300 rounded">
                            <button class="px-3 py-1" onclick="updateQuantity(false)">-</button>
                            <input class="w-12 text-center border-l border-r border-gray-300" id="quantityInput" type="text" value="1"/>
                            <button class="px-3 py-1" onclick="updateQuantity(true)">+</button>
                        </div>
                    </div>
                    <button class="mt-6 bg-black text-white px-6 py-3 rounded" onclick="addToCart()">ADD TO CART</button>
                </div>
                <!-- Dynamic Product Description -->
                <p class="mt-4" id="productDescription"><?php echo $product['product_description']; ?></p>
                <!-- Product Details, Shipping, Satisfaction Guarantee, etc. -->
                <!-- Add additional product details sections here if necessary -->
            </div>
        </div>
    </main>

    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        function updateQuantity(increment) {
            const quantityInput = document.getElementById('quantityInput');
            let currentQuantity = parseInt(quantityInput.value);
            if (increment) {
                currentQuantity += 1;
            } else {
                if (currentQuantity > 1) {
                    currentQuantity -= 1;
                }
            }
            quantityInput.value = currentQuantity;
        }

        function addToCart() {
            const quantity = document.getElementById('quantityInput').value;
            alert(`Added ${quantity} item(s) to cart`);

        }
      
    </script>
</body>
</html>
