<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Rudra Perfumes - Luxury Redefined</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .slider {
            position: relative;
            overflow: hidden;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 80%;
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
        nav a {
            font-size: 1.25rem; 
            margin: 0 15px; 
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

        
.product-card {
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s ease;
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 100%;
    margin: 10px auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1 1 calc(25% - 20px); /* 25% width for 4 items per row */
    max-width: 250px;
}



.product-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Ensures 4 items per row */
    gap: 20px; /* Adjust spacing */
    justify-content: start; /* Align items to the left */
}



.product-card img {
    margin-top: 10px;
    width: 100vw;
    height: 30vh;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease;
    cursor: pointer;
}

/* Hover Effect */
.product-card:hover img {
    transform: scale(1.05);
}

.product-card:hover {
    background-color: rgba(240, 240, 240, 0.5);
}

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
    background-color: #ff5f5f;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease-in-out, opacity 0.3s ease-in-out;
    margin-top: 12px;
    opacity: 1;
}

/* Hover effect for button */
.add-to-cart:hover {
    background-color: #e04e4e;
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

    </style>
</head>
<body class="bg-white text-black">
    <div class="text-3xl font-bold text-center py-2 px-4">RUDRA</div>

    <!-- Header -->
    <header class="border-b border-gray-200">
    <div class="container flex justify-between items-center py-4 px-6">
        <!-- Logo -->
        <div>
            <img src="IMG/logo.png" alt="RUDRA Logo" class="h-14 w-14"> 
        </div>

        <!-- Main Navigation -->
        <nav class="space-x-4 flex">
            <a class="text-black hover:underline" href="#">HOME</a>
            <div class="dropdown relative">
                <a class="nav-link font-semibold text-gray-800 hover:text-gray-600 cursor-pointer">COLLECTIONS</a>
                <div class="dropdown-content absolute left-0 mt-2 bg-white border border-gray-300 shadow-lg hidden">
                    <div class="dropdown-submenu relative">
                        <a class="cursor-pointer block px-4 py-2 hover:bg-gray-100" onmouseover="showPerfumesSubmenu()" onmouseout="hidePerfumesSubmenu()">Perfumes</a>
                        <div class="dropdown-content hidden absolute left-full top-0 bg-white border border-gray-300 shadow-lg" id="perfumes-submenu">
                            <a href="#him" class="block px-4 py-2 hover:bg-gray-100">Him</a>
                            <a href="#her" class="block px-4 py-2 hover:bg-gray-100">Her</a>
                            <a href="#unisex" class="block px-4 py-2 hover:bg-gray-100">Unisex</a>
                        </div>
                    </div>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Attar</a>
                </div>
            </div>
            <a class="text-black hover:underline" href="#">ORDER TESTERS</a>
            <a class="text-black hover:underline" href="#">OUD COLLECTION</a>
            <a class="text-black hover:underline" href="#">COMBO</a>
            <a class="text-black hover:underline" href="#">WINTER SALE</a>
        </nav>

        <!-- Right-side Nav (Search, User, Cart) - FIXED -->
        <nav class="flex items-center space-x-6 ml-auto">
            <!-- Search -->
            <a href="search.html" class="relative group">
                <i class="fas fa-search text-xl"></i>
                <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                    SEARCH
                </div>
            </a>

            <!-- User Login Section -->
            <a href="login.php" class="relative group flex items-center space-x-2">
                <i class="fas fa-user text-xl cursor-pointer"></i>
                <div class="flex items-center space-x-2">
                    <p class="text-sm font-medium">Hello, <?php echo htmlspecialchars($user_name); ?>!</p>
                    <a href="logout.php" class="text-sm text-blue-600 hover:underline">Log Out</a>
                </div>
            </a>

            <!-- Cart -->
            <a href="cart.html" class="relative group">
                <i class="fas fa-shopping-bag text-2xl cursor-pointer"></i>
                <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                    Cart
                </div>
            </a>
        </nav>
    </div>
</header>

</body>

    <!-- Main Banner -->
    <section class="slider relative">
        <div class="slides">
            <div class="slide">
                <img alt="Banner with perfume bottles and hearts" class="w-full" src="https://storage.googleapis.com/a1aa/image/DCztYU3niDGPcKW59kyBfcI04Xh2Q2vqy-WgHCQlDvA.jpg"/>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                    <h1 class="text-4xl font-bold">LOVE AT FIRST SNIFF</h1>
                    <p class="text-2xl mt-2">UPTO 50% OFF SITEWIDE</p>
                </div>
            </div>
            <div class="slide">
                <img alt="Banner with perfume bottles and hearts" class="w-full" src="https://storage.googleapis.com/a1aa/image/DCztYU3niDGPcKW59kyBfcI04Xh2Q2vqy-WgHCQlDvA.jpg"/>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                    <h1 class="text-4xl font-bold">LOVE AT FIRST SNIFF</h1>
                    <p class="text-2xl mt-2">UPTO 50% OFF SITEWIDE</p>
                </div>
            </div>
            <div class="slide">
                <img alt="Banner with perfume bottles and hearts" class="w-full" src="https://storage.googleapis.com/a1aa/image/DCztYU3niDGPcKW59kyBfcI04Xh2Q2vqy-WgHCQlDvA.jpg"/>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                    <h1 class="text-4xl font-bold">LOVE AT FIRST SNIFF</h1>
                    <p class="text-2xl mt-2">UPTO 50% OFF SITEWIDE</p>
                </div>
            </div>
        </div>
        <button class="prev" onclick="moveSlide(-1)">❮</button>
        <button class="next" onclick="moveSlide(1)">❯</button>
    </section>
    <!-- Products Section --
    <section class="container mx-auto py-12 px-6">
        <div class="flex justify-center space-x-6">
            <img alt="Perfume bottle 1" class="h-48" src="https://storage.googleapis.com/a1aa/image/WslZgrTd1jip--1Yp1sAskQJSqR0cfDKNdIZ8qKYT38.jpg"/>
            <img alt="Perfume bottle 2" class="h-48" src="https://storage.googleapis.com/a1aa/image/YcN97h-EXntUqPyzeUCrams3D1BSYnQRMQg0Wpsi-y8.jpg"/>
            <img alt="Perfume bottle 3" class="h-48" src="https://storage.googleapis.com/a1aa/image/AlcqBjFCbx-5eFuwvg-ClXi0VI9f0gXUpUax5Uxv6HQ.jpg"/>
            <img alt="Perfume bottle 4" class="h-48" src="https://storage.googleapis.com/a1aa/image/YBNOGVVrvy1Ub7_vtHlXIfZ6sYAryaSXOyZN4PhyVLM.jpg"/>
        </div>
    </section>
    <!-- Features Section -->
    <div class="bg-gray-100 py-8 mt-14 rounded-lg shadow-lg"> <!-- Added rounded and shadow -->
        <div class="max-w-7xl mx-auto py-4"> <!-- Added padding -->
            <h2 class="text-2xl font-bold text-center mb-6">Our Commitment to Quality</h2> <!-- Added title -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 text-center">
                <div>
                    <i class="fas fa-ban text-4xl mb-2"></i>
                    <p class="font-bold">NON-TOXIC</p>
                    <p>FORMULATION</p>
                </div>
                <div>
                    <i class="fas fa-hand-holding-heart text-4xl mb-2"></i>
                    <p class="font-bold">ETHICALLY</p>
                    <p>SOURCED</p>
                </div>
                <div>
                    <i class="fas fa-paw text-4xl mb-2"></i>
                    <p class="font-bold">CRUELTY</p>
                    <p>FREE</p>
                </div>
                <div>
                    <i class="fas fa-leaf text-4xl mb-2"></i>
                    <p class="font-bold">NATURAL</p>
                    <p>EXTRACTS</p>
                </div>
                <div>
                    <i class="fas fa-flask text-4xl mb-2"></i>
                    <p class="font-bold">PURE</p>
                    <p>ESSENCE</p>
                </div>
                <div>
                    <i class="fas fa-vial text-4xl mb-2"></i>
                    <p class="font-bold">SILICONE</p>
                    <p>FREE</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center py-12 px-4">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Discover Elegance with Rudra Perfume</h1>
        <p class="text-lg md:text-xl max-w-6xl mx-auto">
            Step into a world of sophistication with Rudra Perfume. Our exclusive range of fragrances for men and women is inspired by timeless classics yet crafted with a unique blend of luxury and affordability. Immerse yourself in captivating aromas that elevate every moment with grace and charm.
        </p>
    </div>
     <!-- Best Selling Section -->
     <section class="container mx-auto py-12 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">BEST SELLING</h1>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "perfumes";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch 4 random products where product_category = 'bestsellers'
            $query = "SELECT product_id, product_name, product_image, selling_price, crossed_price, product_size 
                    FROM product_details 
                    WHERE product_category = 'bestseller' 
                    ORDER BY RAND() 
                    LIMIT 4";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // echo "<h1 style='text-align: center;'>Bestsellers</h1>";
                echo "<div class='product-container'>";

                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $image_name = $row['product_image'];
                    $selling_price = $row['selling_price'];
                    $crossed_price = $row['crossed_price'];
                    $product_size = $row['product_size']; 

                    // Make sure image exists
                    $image_path = !empty($image_name) && file_exists("uploads/" . $image_name) ? "uploads/$image_name" : 'path/to/default/image.jpg';

                    echo "<div class='product-card'>";
                    echo "<h3 class='product-name'>$product_name</h3>";

                    // Display product image
                    echo "<a href='product_inside.php?product_id=$product_id'>
                            <img src='$image_path' alt='$product_name' class='product-image' />
                        </a>";

                    // Display prices with size
                    echo "<p class='product-price'>
                            <strong>₹$selling_price</strong> 
                            <span class='crossed-price'>₹$crossed_price</span>
                            <span class='product-size'> ($product_size ml)</span>
                        </p>";

                    // Add to Cart button
                    echo "<button class='add-to-cart' 
                                onclick='addToCart(this, \"$product_name\", \"$image_path\", $selling_price, $crossed_price, \"$product_size\", $product_id)'>
                                + Add to cart
                        </button>";

                    echo "</div>";
                }

                echo "</div>";
            } else {
                echo "<p style='text-align: center;'>No bestsellers found.</p>";
            }

            $conn->close();
            ?>
    </section>
    <body class="bg-white">
        <div class="container mx-auto py-8">
            <h1 class="text-center text-2xl font-bold mb-8">LUXURY CATEGORIES</h1>
            <div class="flex justify-center gap-4">
                <div class="relative text-center group">
                    <a href="https://example.com/luxury-perfumes">
                        <img alt="Luxury Perfumes with a bottle and fruits" class="mx-auto mb-4" height="250" src="https://storage.googleapis.com/a1aa/image/fKnZCbYVraKFuYATFq5Kt14XEA-zniA323G0tSSlt34.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-orange-500 font-bold">LUXURY</h2>
                            <p class="text-gray-200">PERFUMES</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/non-alcoholic-attars">
                        <img alt="Non-Alcoholic Attars with a bottle and flowers" class="mx-auto mb-4" height="250" src="https://storage.googleapis.com/a1aa/image/AxlL_SKRPAZjGv812G50kxTe9gJ3SEFwlw1fRmZ7i14.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-green-500 font-bold">NON-ALCOHOLIC</h2>
                            <p class="text-gray-200">ATTARS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/moisturizing-fragrance-mists">
                        <img alt="Moisturizing Fragrance Mists with a bottle and flowers" class="mx-auto mb-4" height="250" src="https://storage.googleapis.com/a1aa/image/9T5hhKpg7cdr4ex6btP7_b_KkP7z5XabiTkx43zjLIE.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-pink-500 font-bold">MOISTURIZING</h2>
                            <p class="text-gray-200">FRAGRANCE MISTS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/premium-gift-sets">
                        <img alt="Premium Gift Sets with multiple bottles" class="mx-auto mb-4" height="250" src="https://storage.googleapis.com/a1aa/image/Z8e4-Noq5N_9VCXMQQurG2ur7ziuHHUwbvtsyXBYN00.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-purple-500 font-bold">PREMIUM</h2>
                            <p class="text-gray-200">GIFT SETS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/perfume-attar-combo">
                        <img alt="Perfume and Attar Combo with bottles and fruits" class="mx-auto mb-4" height="250" src="https://storage.googleapis.com/a1aa/image/p_lqrYLuquzZWY8EsK2PT-CAVUy31zZJ1IjUwBemH-U.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-orange-500 font-bold">PERFUME + ATTAR</h2>
                            <p class="text-gray-200">COMBO</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        </body>
    <!-- NEW ARRIVAL  Section -->
    <section class="container mx-auto py-12 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">NEW ARRIVAL</h1>
        <div class="flex justify-center space-x-4">
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "perfumes";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch 4 random products where product_category = 'bestsellers'
            $query = "SELECT product_id, product_name, product_image, selling_price, crossed_price, product_size 
                    FROM product_details 
                    WHERE product_category = 'newarrival' 
                    ORDER BY RAND() 
                    LIMIT 4";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // echo "<h1 style='text-align: center;'>Bestsellers</h1>";
                echo "<div class='product-container'>";

                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $image_name = $row['product_image'];
                    $selling_price = $row['selling_price'];
                    $crossed_price = $row['crossed_price'];
                    $product_size = $row['product_size']; 

                    // Make sure image exists
                    $image_path = !empty($image_name) && file_exists("uploads/" . $image_name) ? "uploads/$image_name" : 'path/to/default/image.jpg';

                    echo "<div class='product-card'>";
                    echo "<h3 class='product-name'>$product_name</h3>";

                    // Display product image
                    echo "<a href='product_inside.php?product_id=$product_id'>
                            <img src='$image_path' alt='$product_name' class='product-image' />
                        </a>";

                    // Display prices with size
                    echo "<p class='product-price'>
                            <strong>₹$selling_price</strong> 
                            <span class='crossed-price'>₹$crossed_price</span>
                            <span class='product-size'> ($product_size ml)</span>
                        </p>";

                    // Add to Cart button
                    echo "<button class='add-to-cart' 
                                onclick='addToCart(this, \"$product_name\", \"$image_path\", $selling_price, $crossed_price, \"$product_size\", $product_id)'>
                                + Add to cart
                        </button>";

                    echo "</div>";
                }

                echo "</div>";
            } else {
                echo "<p style='text-align: center;'>No newarrival found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>


    <aside class="fixed right-0 top-0 cart h-full bg-white shadow-lg p-4 overflow-y-auto hidden" id="cart">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold" id="cart-item-count">0 items</h2>
        <button class="text-gray-500" id="close-cart"><i class="fas fa-times"></i></button>
    </div>
    <div class="mb-4 text-center text-green-500 font-semibold">We are accepting orders above ₹595/-</div>
    <div class="mb-4 text-center text-red-500 font-semibold hidden" id="free-shipping-message">You are eligible for free shipping!</div>
    <div class="border-b mb-4"></div>
    <div id="cart-items"></div>
    <div class="fixed bottom-0 left-0 w-full bg-white p-4 shadow-lg">
        <!-- <div class="text-right text-gray-500 mb-2 ">
            Super Fast & Free Delivery + Additional up to 10% OFF on all Prepaid Orders
        </div> -->
        <div class="flex justify-end">
            <button class="w-auto bg-red-500 text-white py-2 px-4 rounded" id="checkout-button">
                CHECKOUT • <span id="total-price">Rs. 0.00</span>
            </button>
        </div>
    </div>
</aside>


    <script>

const cartItems = {};

function addToCart(button, name, image, price, originalPrice, size, productId) {
    // If item doesn't exist in the cart, initialize it with 1 quantity
    if (!cartItems[productId]) {
        cartItems[productId] = { name, image, price, originalPrice, size, quantity: 1 };
    } else {
        // If the item already exists, increase its quantity
        cartItems[productId].quantity += 1;
    }

    // Update the cart display
    updateCart();

    // Show the cart if it's hidden
    document.getElementById('cart').classList.remove('hidden');
}

document.getElementById('close-cart').addEventListener('click', () => {
    document.getElementById('cart').classList.add('hidden');
});

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = ''; // Clear the cart before updating
    let totalPrice = 0;
    let itemCount = 0;

    // Loop over each item in the cart
    for (const productId in cartItems) {
        const { name, image, price, originalPrice, size, quantity } = cartItems[productId];
        totalPrice += price * quantity;
        itemCount += quantity;

        // Create the cart item HTML
        const cartItem = document.createElement('div');
        cartItem.classList.add('flex', 'items-center', 'mb-2');
        cartItem.innerHTML = `
            <img alt="${name}" height="50" src="${image}" width="50"/>
            <div class="ml-4">
                <h3 class="font-semibold">${name}</h3>
                <div class="text-gray-500">₹${price.toFixed(2)} <span class="line-through">₹${originalPrice}</span></div>
                <div class="text-gray-500">(${size} ml)</div>
                <div class="flex items-center mt-2">
                    <button class="border px-2 py-1" onclick="updateQuantity(${productId}, -1)">-</button>
                    <span class="mx-2">${quantity}</span>
                    <button class="border px-2 py-1" onclick="updateQuantity(${productId}, 1)">+</button>
                    <a class="ml-4 text-blue-500" href="#" onclick="removeItem(${productId})">Remove</a>
                </div>
            </div>
        `;
        cartItemsContainer.appendChild(cartItem);
    }

    // Update the total price and item count in the cart header
    document.getElementById('total-price').textContent = `₹${totalPrice.toFixed(2)}`;
    document.getElementById('cart-item-count').textContent = `${itemCount} item${itemCount > 1 ? 's' : ''}`;

    // Show or hide the free shipping message based on the total price
    const checkoutButton = document.getElementById('checkout-button');
    if (totalPrice >= 595) {
        checkoutButton.classList.remove('bg-red-500');
        checkoutButton.classList.add('bg-black');
        document.getElementById('free-shipping-message').classList.remove('hidden');
    } else {
        checkoutButton.classList.remove('bg-black');
        checkoutButton.classList.add('bg-red-500');
        document.getElementById('free-shipping-message').classList.add('hidden');
    }
}

function updateQuantity(productId, change) {
    if (cartItems[productId]) {
        cartItems[productId].quantity += change;
        
        // If quantity is less than or equal to zero, remove the item
        if (cartItems[productId].quantity <= 0) {
            delete cartItems[productId];
        }
        
        // Update the cart after changing the quantity
        updateCart();
    }
}

function removeItem(productId) {
    delete cartItems[productId];
    updateCart();
}

function toggleCart() {
    const cart = document.getElementById('cart');
    cart.classList.toggle('hidden');
}



        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(index) {
            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }
            const offset = -currentSlide * 100;
            document.querySelector('.slides').style.transform = `translateX(${offset}%)`;
        }

        function moveSlide(step) {
            showSlide(currentSlide + step);
        }

        function autoSlide() {
            moveSlide(1);
        }

        setInterval(autoSlide, 3000); // Change slide every 3 seconds

        showSlide(currentSlide);
    </script>
</body>
</html>


-->