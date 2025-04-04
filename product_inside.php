<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
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

// Calculate dynamic dates
$currentDate = new DateTime(); // Current date
$orderedDate = $currentDate->format('M d'); // Format: Feb 20
$orderReadyDate = $currentDate->modify('+1 day')->format('M d'); // Order ready in 1 day
$deliveredDate = $currentDate->modify('+2 days')->format('M d'); // Delivered in 2 days


$selling_price = $product['selling_price'];
$crossed_price = $product['crossed_price'];

// Calculate savings percentage
$savings_percentage = 0;
if ($crossed_price > 0) { // Prevent division by zero
    $savings_percentage = (($crossed_price - $selling_price) / $crossed_price) * 100;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Product Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <div class="header-container">
    <a href="index.php" class="ruuh-button"> <h1 class="header-title">RUUH</h1></a>
</div>
   <style>
    .header-container {
        text-align: center; /* Center the text */
        margin: 2px 0; /* Add some vertical spacing */
        position: relative; /* Position relative for pseudo-elements */
    }

    .header-title {
        font-size: 3rem; /* Large font size */
        font-weight: bold; /* Bold text */
        color: #2c3e50; /* Dark color for the text */
        text-transform: uppercase; /* Uppercase letters */
        letter-spacing: 2px; /* Spacing between letters */
        position: relative; /* Position relative for the underline effect */
        display: inline-block; /* Make the heading inline-block for centering */
    }

    .header-title::before,
    .header-title::after {
        content: ""; /* Empty content for pseudo-elements */
        position: absolute; /* Position absolute for the lines */
        width: 50%; /* Width of the lines */
        height: 4px; /* Height of the lines */
        background-color: #e74c3c; /* Color of the lines */
        top: 50%; /* Center vertically */
        transform: translateY(-50%); /* Adjust for vertical centering */
    }

    .header-title::before {
        left: 0; /* Position the left line */
    }

    .header-title::after {
        right: 0; /* Position the right line */
    }

    
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }
        .slider {
        position: relative;
        overflow: hidden; /* Prevents overflow */
    }
    .slides {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }
    .slide {
        min-width: 100%; /* Each slide takes full width */
        box-sizing: border-box;
    }
       
        .slide img {
            height: 500px;
            object-fit: cover; 
        }
 
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .prev {
            left: 10px;
        }
        .next {
            right: 10px;
        }
        
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu .dropdown-content {
            top: 0;
            left: 100%;
            margin-left: 0.5rem;
        }
        .hidden {
            display: none;
        }
        .hello{
            width: 8vw;
            color: red;
            font-weight: bold;
        }
/* Updated Product Card */
        
.product-card {
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s ease;
    /* background: white; */
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 120%;
    margin: 10px auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1 1 calc(33.33% - 20px); /* 33.33% width for 3 items per row (increased size) */
    max-width: 350px; /* Increased max-width */
}

.product-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Ensures 4 items per row */
    gap: 40px; /* Adjust spacing */
    justify-content: start; /* Align items to the left */
}


.product-card img {
    margin-top: 10px;
    width: 150vw; /* Makes image take up the full width of the card */
    height: 48vh; /* Increased height */
    object-fit:cover;
    /* border-radius: 8px; */
    transition: transform 0.3s ease;
    cursor: pointer;
}
/* Hover Effect */
.product-card:hover img {
    transform: scale(1.05);
}

/* .product-card:hover {
    
    background-color: rgba(240, 240, 240, 0.5);
} */

/* Price Styling */
.product-price {
    font-size: 18px;
    font-weight: bold;
    margin-top: 10px;
}

.crossed-price {
    text-decoration: line-through;
    color: red;
    font-size: 16px;
    margin-left: 5px;
}
.product-size{
    margin-left: 20px;
    font-weight:normal;
    font-size: 1rem;
}



/* Add to Cart Button */
.add-to-cart {
    width: 90%;
    background-color: black; /* Bold black button */
    color: white;
    border: none;
    padding: 15px; /* Increased padding */
    font-size: 18px; /* Increased font size */
    font-weight: bold;
    border-radius: 0; /* Rectangular button */
    cursor: pointer;
    transition: background 0.3s ease-in-out, box-shadow 0.3s ease-in-out, opacity 0.3s ease-in-out;
    margin-top: 12px;
    position: relative; /* To position the pseudo-element */
    overflow: hidden; /* Ensures no overflow outside the button */
}

/* Glossy white shine effect */
.add-to-cart::before {
    content: '';
    position: absolute;
    top: 50%; /* Initially center the gloss */
    left: 50%;
    width: 150%; /* Make the shine larger than the button */
    height: 150%; /* Make the shine larger than the button */
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0)); /* Gradient for shiny effect */
    transform: rotate(-30deg); /* Angle the gloss for a realistic look */
    pointer-events: none; /* Ensure the gloss doesn't block clicks */
    transition: opacity 0.2s ease-in-out;
    opacity: 0; /* Start with the gloss invisible */
    z-index: 1;
}

/* Hover effect for button */
.add-to-cart:hover {
    background-color: black; /* Keep the black background on hover */
    opacity: 0.9; /* Slight opacity effect on hover */
}

/* Optional: To enhance the glossy effect, add a subtle shadow */
.add-to-cart:hover {
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2); /* Subtle white shadow for a glowing effect */
}




/* Filter & Product Sections */
.filter-section {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding: 1rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    height: 100vh;
    overflow-y: auto;
}

.product-section {
    height: 100vh;
    overflow-y: auto;
}

/* Hidden Elements */
.hidden {
    display: none;
}

/* Cart */
.cart {
    width: 400px; /* Increased width of the cart */
}
.product-image-wrapper {
    position: relative;
    display: inline-block;
}


.product-image {
    width: 100%;
    height: 100%; /* Ensures it fills the block */
    object-fit: cover; /* Ensures the image covers without distortion */
}


.additional-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: auto;
    opacity: 0; /* Hide the additional image initially */
    transition: opacity 0.6s ease-in-out; /* Slower and smoother transition */
    z-index: 1; /* Ensure it appears above the main image */
}

/* Show the additional image on hover */
.product-image-wrapper:hover .product-image {
    opacity: 0; /* Hide the main image */
}

.product-image-wrapper:hover .additional-image {
    opacity: 1; /* Show the additional image */
}




    </style>
</head>
<body>

<!-- Main Navigation -->
<div id="navbar" class="flex justify-between items-center w-full bg-white shadow-md p-4">
    <div class="flex items-center">
        <div class="mr-8">
            <img src="IMG/logo.png" alt="RUDRA Logo" class="h-14 w-14">
        </div>
        <nav class="space-x-6 flex">
            <a class="text-gray-800 hover:text-red-500 font-semibold transition duration-300" href="index.php">HOME</a>
<!-- Collections Navigation -->
<div class="dropdown relative">
    <a class="nav-link font-semibold text-gray-800 hover:text-red-500 cursor-pointer" onclick="toggleDropdown(event)">COLLECTIONS</a>
    <div class="dropdown-content absolute left-0 mt-2 bg-white border border-gray-300 shadow-lg hidden">
        <div class="dropdown-submenu relative">
            <a class="cursor-pointer block px-4 py-2 hover:bg-gray-100"  href="perfumes.php">Perfumes</a>
            <div class="dropdown-content hidden absolute left-full top-0 bg-white border border-gray-300 shadow-lg" id="perfumes-submenu">
                            <a href="perfumes_him.php" class="block px-4 py-2 hover:bg-gray-100">Him</a>
                            <a href="perfumes_her.php" class="block px-4 py-2 hover:bg-gray-100">Her</a>
                            <a href="perfumes_unisex.php" class="block px-4 py-2 hover:bg-gray-100">Unisex</a>
                        </div>
        </div>
        <a class="block px-4 py-2 hover:bg-gray-100" href="attars.php">Attar</a>
        <a class="block px-4 py-2 hover:bg-gray-100" href="deodrants.php">Deodrants</a>
        <a class="block px-4 py-2 hover:bg-gray-100" href="essence_oils.php">Essence Oil</a>
    </div>
</div>

<style>
    /* Dropdown Styles */
    .dropdown {
        position: relative; 
    }

    .dropdown-content {
        display: none; 
        position: absolute; 
        background-color: white; 
        border: 1px solid #ccc; 
        z-index: 1000; 
        width: 200px; 
    }

    .dropdown:hover .dropdown-content {
        display: block; 
    }

    .dropdown-submenu {
        position: relative; 
    }

    .dropdown-submenu .dropdown-content {
        display: none; 
        position: absolute; 
        left: 100%; 
        top: 0; 
        background-color: white; 
        border: 1px solid #ccc; 
        z-index: 1000; 
    }

    .dropdown-submenu:hover .dropdown-content {
        display: block; 
    }

    /* Additional styles for links */
    .dropdown-content a {
        display: block; 
        padding: 10px; 
        color: black; 
        text-decoration: none; 
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1; 
    }
    .custom-font {
            font-weight: bold; /* Make text bold */
            text-transform: uppercase; /* Make text uppercase for a stylish look */
            letter-spacing: 0.5px; /* Add some spacing between letters */
            color: #333; /* Dark gray color for text */
        }
        .image-container {
    padding-right: 40px; /* Increases space on the right */
}
.product-image{
    height: 500px;
    width: 500px;
}

</style>

<script>
    function togglePerfumesSubmenu(event) {
        event.preventDefault(); 
        const submenu = document.getElementById('perfumes-submenu');
        submenu.classList.toggle('hidden'); 
    }

    function toggleDropdown(event) {
        event.preventDefault(); 
        const dropdownContent = event.target.nextElementSibling;
        dropdownContent.classList.toggle('hidden'); 
    }
</script>
<a class="text-gray-800 hover:text-red-500 font-semibold transition duration-300" href="20ml_tester.php">ORDER TESTERS</a>
            <a class="text-gray-800 hover:text-red-500 font-semibold transition duration-300" href="oud_collection.php">OUD COLLECTION</a>
            <a class="text-gray-800 hover:text-red-500 font-semibold transition duration-300" href="view_products.php">COMBO</a>
            <a class="text-gray-800 hover:text-red-500 font-semibold transition duration-300" href="sale.php">SALE</a>
        </nav>
    </div>
    <!-- Second Navigation Bar -->
    <div id="secondnavbar" class="flex space-x-6 items-center">
    <div class="flex items-center space-x-1">
        <p class="text-sm font-medium text-gray-800">Hello, <?php echo htmlspecialchars($user_name); ?>!</p>
    </div>
        <a href="login.php" class="relative group flex items-center space-x-2">
    <i class="fas fa-user text-xl text-gray-800 hover:text-red-500 transition duration-300"></i>
    <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
        ACCOUNT
    </div>
</a>        <a href="search.html" class="relative group">
            <i class="fas fa-search text-xl text-gray-800 hover:text-red-500 transition duration-300"></i>
            <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                SEARCH
            </div>
        </a>

        

        <a href="cart.html" class="relative group">
            <i class="fas fa-shopping-cart text-xl text-gray-800 hover:text-red-500 transition duration-300"></i>
            <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                CART
            </div>
        </a>
        <a href="logout.php" class="relative group flex items-center space-x-2">
    <i class="fas fa-sign-out-alt text-xl text-gray-800 hover:text-red-500 transition duration-300"></i>
    <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
        LOGOUT
    </div>
</a>
    </div>
</div>



<main class="container mx-auto"> <!-- Increased left padding -->
    <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/2 flex flex-col items-center">
            <!-- Dynamic Product Images -->
            <div style="width: 400px; height: 400px; overflow: hidden; margin-top:40px">
    <img alt="Product Image" class="product-image" id="mainImage" 
         src="uploads/<?php echo $product['product_image']; ?>" />
</div>

            
            <div class="flex space-x-2">
            <?php 
            for ($i = 2; $i <= 10; $i++) {
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
        <div class="lg:w-2/3 lg:pl-26 mt-8 lg:mt-0 product-details"> 
<!-- Dynamic Product Name and Price -->
<h1 class="text-3xl font-bold" style="margin-top: 40px; margin-left:60px" id="productName"><?php echo $product['product_name']; ?></h1>
<div class="flex items-center text-xl text-black-600 mt-2" style="margin-left:60px">
    <div id="productPrice" class="mr-2"><?php echo "Rs. " . $selling_price; ?></div>
    <div class="text-red-600 text-decoration: line-through mr-2" id="crossedPrice">
        <?php echo "Rs. " . $crossed_price; ?>
    </div>
    <span class="bg-black text-white px-2 py-1 rounded text-sm" >Save <?php echo round($savings_percentage); ?>%</span> <!-- Smaller font size for savings percentage -->
</div>

<!-- Additional Product Features -->
<div class="mt-4 space-y-2 " style="margin-left:60px">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-2"></i>
        <p class="text-sm custom-font">Long-lasting</p>
    </div>
    <div class="flex items-center">
        <i class="fas fa-gem text-blue-500 mr-2"></i>
        <p class="text-sm custom-font">Affordable Luxury</p>
    </div>
    <div class="flex items-center">
        <i class="fas fa-users text-purple-500 mr-2"></i>
        <p class="text-sm custom-font">Unisex Appeal</p>
    </div>
    <div class="flex items-center">
        <i class="fas fa-leaf text-green-600 mr-2"></i>
        <p class="text-sm custom-font">Cruelty-Free and Ethical</p>
    </div>
</div>

<div class="mt-4 flex items-center" style="margin-left:60px" >
    <span class="mr-4">Quantity:</span>
    <div class="flex items-center border border-gray-300 rounded">
        <button class="px-3 py-1" onclick="updateQuantity(false)">-</button>
        <input class="w-12 text-center border-l border-r border-gray-300" id="quantityInput" type="text" value="1"/>
        <button class="px-3 py-1" onclick="updateQuantity(true)">+</button>
    </div>
</div>
<button class="mt-6 bg-black text-white px-6 py-3 " style="margin-left:60px" onclick="addToCart()">ADD TO CART</button>
<!-- ordered, order ready, delivered -->
<div class="mt-8 flex space-x-8" style="margin-left:60px">
    <div class="text-center">
        <i class="fas fa-box-open text-2xl text-gray-600"></i>
        <div class="mt-2">Ordered</div>
        <div class="text-gray-500"><?php echo $orderedDate; ?></div> <!-- Dynamic Ordered Date -->
    </div>
    <div class="text-center">
        <i class="fas fa-truck text-2xl text-gray-600"></i>
        <div class="mt-2">Order ready</div>
        <div class="text-gray-500"><?php echo $orderReadyDate . ' - ' . $deliveredDate; ?></div> <!-- Dynamic Order Ready Date -->
    </div>
    <div class="text-center">
        <i class="fas fa-map-marker-alt text-2xl text-gray-600"></i>
        <div class="mt-2">Delivered</div>
        <div class="text-gray-500"><?php echo $deliveredDate; ?></div> <!-- Dynamic Delivered Date -->
    </div>
</div>

<!-- Product Details, Shipping, Satisfaction Guarantee, etc. -->
<div class="mt-8 flex flex-col space-y-4">
            <div class="pl-16">
                <div class="border-t py-4">
                    <button class="w-full text-left flex justify-between items-center" onclick="toggleDetails('productDetails')">
                        <span class="font-bold">Product Details</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="productDetails" class="hidden mt-2 text-black-600">
                        <p class="mt-4" id="productDescription"><?php echo nl2br(htmlspecialchars($product['product_description'])); ?></p>
                    </div>
                </div>
                <div class="border-t py-4">
                    <button class="w-full text-left flex justify-between items-center" onclick="toggleDetails('shippingReturnDetails')">
                        <span class="font-bold">Shipping &amp; Return</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="shippingReturnDetails" class="hidden mt-2 text-black-600">
                        <div class="shipping-details">
                            <h2 class="font-bold">Shipping Details</h2>
                            <p>It takes between 2-5 business days once it'll dispatch (not including weekends and national holidays). We need 1-2 days to prepare & dispatch your package. If your order has not arrived by the expected date, please contact us so that we can open an investigation with our shipping partner. The investigation to locate your package may take up to two weeks.</p>
                        </div>
                        <div class="return-details mt-4">
                            <h2 class="font-bold">Return Policy</h2>
                            <p>We have a 3 days return policy, which means you have 3 days after receiving your item to request a return. We will notify you once we’ve received and inspected your return, and let you know if the refund was approved or not. If approved, you’ll be automatically refunded on your original payment method within 10 business days. Please remember it can take some time for your bank or credit card company to process and post the refund too. If more than 15 business days have passed since we’ve approved your return, please contact us at contact@paradyseperfumes.com.</p>
                        </div>
                    </div>
                </div>
                <div class="border-t py-4">
                    <button class="w-full text-left flex justify-between items-center" onclick="toggleDetails('satisfactionGuarantee')">
                        <span class="font-bold">100% Satisfaction Guarantee</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="satisfactionGuarantee" class="hidden mt-2 text-black-600">
                        <h2 class="font-bold">Introducing our 100% Satisfaction Guarantee</h2>
                        <ul class="list-disc list-inside mt-2">
                            <li>Premium Quality: We source only the finest ingredients and materials for our perfumes.</li>
                            <li>Exquisite Craftsmanship: Each fragrance is meticulously crafted by master perfumers.</li>
                            <li>Long-Lasting Scents: Our perfumes are designed to linger throughout the day.</li>
                            <li>Cruelty-Free: We never test our products on animals, ensuring ethical practices.</li>
                            <li>100% Satisfaction: If you're not delighted with your purchase, we offer hassle-free returns.</li>
                            <li>Customized Perfumes: Explore our bespoke options for a personalized scent experience.</li>
                            <li>Secure Shopping: Shop with confidence knowing your data is protected.</li>
                            <li>Fast Shipping: Receive your order promptly with our efficient delivery service.</li>
                            <li>Friendly Support: Our customer service team is here to assist you, 24/7.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="max-w-6xl mx-auto grid grid-cols-[1fr_auto_1fr] gap-8 mt-14">
    <!-- Left Card (Other Brands) -->
    <div class="bg-white text-black p-6 rounded-lg border border-gray-300 pr-10"> 
        <h2 class="text-xl font-bold mb-4 bg-black text-white p-3 rounded">Other Brands</h2>
        <ul class="space-y-6">
            <li class="flex items-start">
                <i class="fas fa-times-circle text-red-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">High Cost</h3>
                    <p class="text-md">Often Come With High Price Tags That Strain Your Budget.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-times-circle text-red-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Matching Excellence</h3>
                    <p class="text-md">Struggle To Capture The Intricate Notes Of The Original, Resulting In A Resemblance Of 40-50%.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-times-circle text-red-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Oil Concentration</h3>
                    <p class="text-md">Often Present Their Scents As Eau De Toilette With Only 15% Oil Concentration.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-times-circle text-red-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Local Vs. Global</h3>
                    <p class="text-md">Utilize Locally Produced Oils, Limiting The Variety And Depth Of Scent Profiles.</p>
                </div>
            </li>
        </ul>
    </div>

    <!-- Center Image -->
    <div class="flex justify-center items-center">
        <img alt="Product Image" class="w-[350px] h-[350px] object-cover" id="mainImage" src="uploads/<?php echo $product['product_image']; ?>" />
    </div>

    <!-- Right Card (Ruuh Perfumes) -->
    <div class="bg-white text-black p-6 rounded-lg border border-gray-300 pl-10">
        <h2 class="text-xl font-bold mb-4 bg-yellow-100 text-black p-3 rounded">Ruuh Perfumes</h2>
        <ul class="space-y-6">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Affordable</h3>
                    <p class="text-md">Offers The Allure Of Luxury At A Fraction Of The Cost, Making Opulence Accessible To All.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Matching Excellence</h3>
                    <p class="text-md">Masterfully Matches The Parent Perfumes With A Remarkable Precision Of 95-100%, Ensuring An Authentic And Recognizable Scent.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i>
                <div>
                    <h3 class="font-bold text-md">Oil Concentration</h3>
                    <p class="text-md">Elevates The Experience With A 40% Concentration Of Extrait De Parfum, Promising A Longer-Lasting And More Intense Fragrance.</p>
                </div>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                <div>
                    <h3 class="font-bold text-sm">Local Vs. Global</h3>
                    <p class="text-sm">Sources The Finest Oils From Around The World, Infusing A Global Richness Into Every Bottle.</p>
                </div>
            </li>
        </ul>
    </div>
</div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="bg-white p-8 rounded-lg shadow-md border border-gray-300"> <!-- Increased padding -->
            <h2 class="text-xl font-bold mb-4">How to use?</h2> <!-- Increased font size -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full mb-2"> <!-- Increased circle size -->
                        <i class="fas fa-hand-sparkles text-4xl"></i> <!-- Increased icon size -->
                    </div>
                    <div class="w-full border-t border-gray-300 mb-2"></div> <!-- Line below the icon -->
                    <span class="text-lg font-bold mt-1">1</span> <!-- Step number -->
                    <p class="text-md">Hold the bottle 5-8 inches away from your skin</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full mb-2"> <!-- Increased circle size -->
                        <i class="fas fa-spray-can text-4xl"></i> <!-- Increased icon size -->
                    </div>
                    <div class="w-full border-t border-gray-300 mb-2"></div> <!-- Line below the icon -->
                    <span class="text-lg font-bold mt-1">2</span> <!-- Step number -->
                    <p class="text-md">Spray onto your pulse points, like neck and behind your ears</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full mb-2"> <!-- Increased circle size -->
                        <i class="fas fa-clock text-4xl"></i> <!-- Increased icon size -->
                    </div>
                    <div class="w-full border-t border-gray-300 mb-2"></div> <!-- Line below the icon -->          
                    <span class="text-lg font-bold mt-1">3</span> <!-- Step number -->
                    <p class="text-md">For a long-lasting fragrance avoid rubbing the perfume</p> <!-- Increased font size -->
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-300 mt-4">
            <h2 class="text-lg font-bold mb-4">When to use?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div>
                    <img src="https://storage.googleapis.com/a1aa/image/uAQuEK3FMcEF506TLx7f-Fg8MkN0v4Br9n81BQyFFxQ.jpg" alt="Image of a person using perfume after shower" class="mx-auto mb-2 w-24 h-24"> <!-- Decreased size -->
                    <p class="text-sm">After Shower</p>
                </div>
                <div>
                    <img src="https://storage.googleapis.com/a1aa/image/voRQRBeDXlQ7Wrq8oZALsvjRCzLBPqGKGu0kzOKv12M.jpg" alt="Image of a person using perfume for date night" class="mx-auto mb-2 w-24 h-24"> <!-- Decreased size -->
                    <p class="text-sm">Date Night</p>
                </div>
                <div>
                    <img src="https://media.istockphoto.com/id/611628796/photo/successful-businessman-likes-perfume-scent.jpg?s=612x612&w=0&k=20&c=Clsd4JvXgoOtUBR93SEzK2U875FOsXJH3wmRE9eLCTk=" alt="Image of a person using perfume for work" class="mx-auto mb-2 w-24 h-24"> <!-- Decreased size -->
                    <p class="text-sm">Office Events</p>
 </div>
                <div>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZsA8MXxIL_H_FEhLP7houu6tPl0bvI1GwZw&s" alt="Image of a person using perfume for festivals" class="mx-auto mb-2 w-24 h-24"> <!-- Decreased size -->
                    <p class="text-sm">For Workout</p>
                </div>
                <div>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQreu7pOoOZXwAXDO8JC2uz8dPBPb5q1ILY4Q&s" alt="Image of a person using perfume for workout" class="mx-auto mb-2 w-24 h-24"> <!-- New image -->
                    <p class="text-sm">Party Wingman</p>
                </div>
                <div>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQreu7pOoOZXwAXDO8JC2uz8dPBPb5q1ILY4Q&s" alt="Image of a person using perfume for workout" class="mx-auto mb-2 w-24 h-24"> <!-- New image -->
                    <p class="text-sm">Perfect For Festivals</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300"> <!-- Increased padding -->
            <h2 class="text-xl font-bold mb-4 text-center">Fragrance Storage Tips</h2> <!-- Increased font size -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-sun text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Keep out of heat</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-lightbulb text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Avoid making your perfume bottle transparent</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-box text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Keep the perfume in a dark box</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-spray-can text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Avoid shaking your perfume bottle</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-water text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Store away from moisture</p> <!-- Increased font size -->
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-2"> <!-- Circle container -->
                        <i class="fas fa-temperature-low text-3xl"></i> <!-- Increased icon size -->
                    </div>
                    <p class="text-md">Store in a place free of temperature fluctuation</p> <!-- Increased font size -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="gap-8 mt-14">
<footer class="py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <!-- Subscribe Section -->
                <div class="mb-8 md:mb-0">
                    <h2 class="font-bold mb-2">SUBSCRIBE</h2>
                    <p class="mb-4">Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your e-mail" class="border border-gray-300 p-2 rounded-l-md w-full">
                        <button type="submit" class="bg-gray-300 p-2 rounded-r-md">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                <!-- Perfume Section -->
                <div class="mb-8 md:mb-0">
                    <h2 class="font-bold mb-2">PERFUME</h2>
                    <ul>
                        <li class="mb-2"><a href="#" class="hover:underline">Acqua</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Savage</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Blue Rush</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Aventus</a></li>
                    </ul>
                </div>
                <!-- Support Section -->
                <div>
                    <h2 class="font-bold mb-2">SUPPORT</h2>
                    <ul>
                        <li class="mb-2"><a href="shipping.php" class="hover:underline">Shipping Policy</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Refund Policy</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Terms and Condition</a></li>
                        <li class="mb-2"><a href="#" class="hover:underline">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-500">
                <p>Ruuh Perfumes &nbsp; &bull; &nbsp;</p>
            </div>
        </div>
    </footer>
</div>
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

    function toggleDetails(detailId) {
        const detailElement = document.getElementById(detailId);
        detailElement.classList.toggle('hidden');
    }
</script>
</body>
</html>