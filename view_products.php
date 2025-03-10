<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Product Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
body {
    font-family: 'Roboto', sans-serif;
}

/* Slider */
.slider {
    position: relative;
    overflow: hidden;
}

.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.slide {
    min-width: 100%;
    box-sizing: border-box;
}

.slide img {
    height: 500px;
    object-fit: cover;
}

/* Navigation */
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

/* Updated Product Card */

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
<body class="bg-white text-gray-800">
    <div class="container mx-auto px-4 lg:px-10">
        <nav class="flex items-center justify-between py-2.5">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-bold tracking-widest">RUUH</span>
                <span class="text-xs tracking-widest">PERFUMES</span>
            </div>
            <div class="flex space-x-8">
                <a href="index.html" class="text-lg font-bold">HOME</a>
                <a href="#" class="text-lg font-bold">COLLECTIONS</a>
                <a href="ORDER.html" class="text-lg font-bold">ORDER TESTERS</a>
                <a href="ooud.html" class="text-lg font-bold">OUD COLLECTION</a>
                <a href="#" class="text-lg font-bold">COMBO</a>
                <a href="" class="text-lg font-bold">SALE</a>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-search text-lg"></i>
                <div class="relative">
                    <i class="fas fa-shopping-cart text-lg" onclick="toggleCart()"></i>
                    <span class="absolute top-0 right-0 bg-black text-white text-xs rounded-full w-4 h-4 flex items-center justify-center" id="cart-item-count">0</span>
                </div>
            </div>
        </nav>
    </div>

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

    <div class="container mx-auto p-4 flex">
        <!-- Filters Section -->
        <div class="w-full lg:w-1/6 p-4 filter-section">
            <h2 class="text-2xl font-bold mb-4">Filters</h2>
            <div class="border-b border-gray-300 pb-4 mb-4">
                <div class="flex justify-between items-center mb-2 toggle-section cursor-pointer">
                    <h3 class="text-md font-semibold">Availability</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="ml-4 hidden">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="in-stock" class="mr-2">
                        <label for="in-stock" class="text-sm">In stock (30)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="out-of-stock" class="mr-2">
                        <label for="out-of-stock" class="text-sm">Out of stock (0)</label>
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-300 pb-4 mb-4">
                <div class="flex justify-between items-center mb-2 toggle-section cursor-pointer">
                    <h3 class="text-md font-semibold">Price</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="ml-4 hidden">
                    <div class="flex items-center mb-2">
                        <input type="range" id="price-range" min="0" max="3000" value="0" class="w-full">
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center border border-gray-300 p-2">
                            <span class="text-sm">₹</span>
                            <input type="number" id="price-min" min="0" max="3000" value="0" class="w-16 text-sm text-center outline-none" readonly>
                        </div>
                        <span class="mx-2 text-sm">to</span>
                        <div class="flex items-center border border-gray-300 p-2">
                            <span class="text-sm">₹</span>
                            <input type="number" id="price-max" min="0" max="3000" value="3000" class="w-16 text-sm text-center outline-none">
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-300 pb-4 mb-4">
                <div class="flex justify-between items-center mb-2 toggle-section cursor-pointer">
                    <h3 class="text-md font-semibold">Volume</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="ml-4 hidden">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="volume-50ml" class="mr-2">
                        <label for="volume-50ml" class="text-sm">50ml</label>
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="volume-100ml" class="mr-2">
                        <label for="volume-100ml" class="text-sm">100ml</label>
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="volume-120ml" class="mr-2">
                        <label for="volume-120ml" class="text-sm">120ml</label>
                    </div>
                </div>
            </div>
            <button id="reset-filters" class="w-full bg-red-500 text-white py-2 mt-4">Remove All Filters</button>
        </div>

        
<script>
    document.querySelectorAll(".toggle-section").forEach(section => {
        section.addEventListener("click", function () {
            const content = this.nextElementSibling;
            content.classList.toggle("hidden");
            this.querySelector("i").classList.toggle("fa-chevron-down");
            this.querySelector("i").classList.toggle("fa-chevron-up");
        });
    });
    
    document.getElementById("reset-filters").addEventListener("click", function () {
        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => checkbox.checked = false);
        document.getElementById("price-range").value = 0;
        document.getElementById("price-min").value = 0;
        document.getElementById("price-max").value = 3000;
    });
</script>


        <!-- Products Section -->
        <div class="w-full lg:w-5/6 p-4 product-section">
            <div class="flex justify-between items-center mb-4">
                <!-- <span class="text-lg" id="product-count">42 products</span> -->
                <div class="flex items-center">
                    <span class="mr-2">Sort by</span>
                    <select id="sort-by" class="border border-gray-300 rounded p-2" onchange="sortProducts()">
                        <option value="name-asc">Alphabetically, A-Z</option>
                        <option value="name-desc">Alphabetically, Z-A</option>
                        <option value="price-asc">Price, low to high</option>
                        <option value="price-desc">Price, high to low</option>
                    </select>
                </div>
            </div>
            <div class="products_card" id="product-list">
                <!-- fetching products -->
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "perfumes";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch product details along with image file name, selling price, crossed price, and product size
            $query = "SELECT product_id, product_name, product_image, selling_price, crossed_price, product_size FROM product_details";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo "<h1 style='text-align: center;'>Product Listings</h1>";
                echo "<div class='product-container'>";

                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $image_name = $row['product_image'];
                    $selling_price = $row['selling_price'];
                    $crossed_price = $row['crossed_price'];
                    $product_size = $row['product_size']; // Fetching product size

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

                    // Add to Cart button with necessary data attributes
                    echo "<button class='add-to-cart' 
                                onclick='addToCart(this, \"$product_name\", \"$image_path\", $selling_price, $crossed_price, \"$product_size\", $product_id)'>
                                + Add to cart
                        </button>";

                    echo "</div>";
                }

                echo "</div>";
            } else {
                echo "<p style='text-align: center;'>No products found.</p>";
            }

            $conn->close();
            ?>


<!-- 
<script>
function addToCart(button, productId, productName) {
    alert(productName + " added to cart!");
    // Here you can add AJAX to send the product ID to the backend for cart processing
}
</script> -->



    </div>

    <!-- Cart Section -->
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
</script>
</body>
</html>