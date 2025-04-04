<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
// Check if customer_id is set
if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
} else {
    // Handle the case where customer_id is not set
    $customer_id = null; // or set it to a default value
}?>

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
            color: [#e74c3c];
            font-weight: bold;
        }

        
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
    grid-template-columns: repeat(4, 1fr); /* Ensures 4 items per row */
    gap: 40px; /* Adjust spacing */
    justify-content: start; /* Align items to the left */
}


.product-card img {
    margin-top: 10px;
    width: 122vw; /* Makes image take up the full width of the card */
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
    height: auto;
    transition: opacity 0.6s ease-in-out; /* Slower and smoother transition */
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


/* Additional styles for the Why Ruuh section */
.why-ruuh-section {
            background-color: white;
            text-align: center;
            padding: 3rem 0; /* Adjust padding as needed */
        }

        .why-ruuh-section h1 {
            font-size: 2rem; /* Adjust font size */
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .why-ruuh-section .feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem; /* Space between features */
        }

        .why-ruuh-section img {
            margin-bottom: 0.5rem; /* Space between image and text */
        }

        .why-ruuh-section h2 {
            font-size: 1.5rem; /* Adjust font size */
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .why-ruuh-section p {
            font-size: 1rem; /* Adjust font size */
        }

    </style>
</head>
<body>

<!-- Main Navigation -->
<div id="navbar" class="flex items-center w-full bg-white shadow-md p-4">
    <div class="flex items-center mr-auto"> <!-- Logo on the left -->
        <div class="mr-8">
            <img src="IMG/logo.png" alt="RUDRA Logo" class="h-14 w-14">
        </div>
    </div>
    <nav class="flex space-x-4 justify-center flex-grow"> <!-- Centered navigation items -->
        <a class="text-gray-800 hover:text-cyan-500 font-semibold transition duration-300" href="index.php">HOME</a>
        <!-- Collections Navigation -->
        <div class="dropdown relative">
            <a class="nav-link font-semibold text-gray-800 hover:text-cyan-500 cursor-pointer" onclick="toggleDropdown(event)">COLLECTIONS</a>
            <div class="dropdown-content absolute left-0 mt-2 bg-white border border-gray-300 shadow-lg hidden">
                <div class="dropdown-submenu relative">
                    <a class="cursor-pointer block px-4 py-2 hover:bg-gray-100" href="perfumes.php">Perfumes</a>
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
        <a class="text-gray-800 hover:text-cyan-500 font-semibold transition duration-300" href="view_products.php">ORDER TESTERS</a>
        <a class="text-gray-800 hover:text-cyan-500 font-semibold transition duration-300" href="oud_collection.php">OUD COLLECTION</a>
        <a class="text-gray-800 hover:text-cyan-500 font-semibold transition duration-300" href="view_products.php">COMBO</a>
        <a class="text-gray-800 hover:text-cyan-500 font-semibold transition duration-300" href="sale.php">SALE</a>
    </nav>


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


    <div id="secondnavbar" class="flex space-x-4 items-center">
            <div class="flex items-center space-x-1">
                <p class="text-sm font-medium text-gray-800 hover:text-pink-500 transition duration-300">Hello, <?php echo htmlspecialchars($user_name); ?>!</p>
            </div>
        </a>
        <a href="search.html" class="relative group">
            <i class="fas fa-search text-xl text-gray-800 hover:text-cyan-500 transition duration-300"></i>
            <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                SEARCH
            </div>
        </a>
        
        <a href="cartfinal.php" class="relative group">
            <i class="fas fa-shopping-cart text-xl text-gray-800 hover:text-cyan-500 transition duration-300"></i>
            <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
                CART
            </div>
        </a>
        <a href="login.php" class="relative group flex items-center space-x-2">
    <i class="fas fa-user text-xl text-gray-800 hover:text-cyan-500 transition duration-300"></i>
    <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
        ACCOUNT
    </div>
</a>        

        

    <!--    <a href="logout.php" class="relative group flex items-center space-x-2">
    <i class="fas fa-sign-out-alt text-xl text-gray-800 hover:text-cyan-500 transition duration-300"></i>
    <div class="absolute top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-white text-gray-800 px-2 py-1 rounded shadow-md text-sm">
        LOGOUT
    </div>
</a> -->
    </div>
</div>

<!-- Main Banner -->
<section class="slider relative">
    <div class="slides">
        <div class="slide">
            <img alt="Banner with perfume bottles and hearts" class="w-full" src="https://thumbs.dreamstime.com/b/elegant-red-perfume-bottle-bow-350296402.jpg"/>
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                <h1 class="text-4xl font-bold">LOVE AT FIRST SNIFF</h1>
                <p class="text-2xl mt-2">UPTO 50% OFF SITEWIDE</p>
            </div>
        </div>
    
        <div class="slide">
            <img alt="Banner with perfume bottles and flowers" class="w-full" src="https://bellavitaluxury.co.in/cdn/shop/files/764-310_1_2cb85ee2-b15f-4b49-b235-50dc30e7c0c1.jpg?v=1723228757&width=800"/>
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                
            </div>
        </div>
        <div class="slide">
            <img alt="Banner with perfume bottles" class="w-full" src="https://smellzone.in/wp-content/uploads/2023/05/Smellzone-Slider-2-min.png"/>
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
                
            </div>
        </div>
    </div>
    <button class="prev" onclick="moveSlide(-1)">❮</button>
    <button class="next" onclick="moveSlide(1)">❯</button>
</section>
 <!-- Features Section -->
 <div class=" py-8 mt-14 "> <!-- Light pink background -->
    <div class="max-w-7xl mx-auto py-4">
        <h1 class="text-2xl font-bold text-center mb-6 text-black">Our Commitment to Quality</h1> <!-- Black title -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 text-center">
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]"> <!-- Pink accent on the left -->
                <i class="fas fa-ban text-4xl mb-2 text-[#00bcd4] icon"></i> <!-- Cyan icon -->
                <p class="font-bold">NON-TOXIC</p>
                <p>FORMULATION</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]">
                <i class="fas fa-hand-holding-heart text-4xl mb-2 text-[#00bcd4] icon"></i>
                <p class="font-bold">ETHICALLY</p>
                <p>SOURCED</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]">
                <i class="fas fa-paw text-4xl mb-2 text-[#00bcd4] icon"></i>
                <p class="font-bold">CRUELTY</p>
                <p>FREE</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]">
                <i class="fas fa-leaf text-4xl mb-2 text-[#00bcd4] icon"></i>
                <p class="font-bold">NATURAL</p>
                <p>EXTRACTS</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]">
                <i class="fas fa-flask text-4xl mb-2 text-[#00bcd4] icon"></i>
                <p class="font-bold">PURE</p>
                <p>ESSENCE</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#ff69b4]">
                <i class="fas fa-vial text-4xl mb-2 text-[#00bcd4] icon"></i>
                <p class="font-bold">SILICONE</p>
                <p>FREE</p>
            </div>
        </div>
    </div>


<div class="text-center py-12 px-4">
    <h1 class="text-2xl md:text-3xl font-bold mb-4 text-black">Discover Elegance with Ruuh Perfume</h1> <!-- Black heading -->
    <p class="text-lg md:text-xl max-w-6xl mx-auto">
        Step into a world of sophistication with Ruuh Perfume. Our exclusive range of fragrances for men and women is inspired by timeless classics yet crafted with a unique blend of luxury and affordability. Immerse yourself in captivating aromas that elevate every moment with grace and charm.
    </p>
    
</div>
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

    // Fetch 4 random products where product_category = 'newarrival'
    $query = "SELECT product_id, product_name, product_image, additional_image1, selling_price, crossed_price, product_size 
              FROM product_details 
              WHERE product_category = 'bestseller' 
              ORDER BY RAND() 
              LIMIT 4";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div class='product-container'>";

        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $image_name = $row['product_image'];
            $additional_image1 = $row['additional_image1'];  // Fetch additional image
            $selling_price = $row['selling_price'];
            $crossed_price = $row['crossed_price'];
            $product_size = $row['product_size']; 

            // Make sure the main image exists
            $image_path = !empty($image_name) && file_exists("uploads/" . $image_name) ? "uploads/$image_name" : 'path/to/default/image.jpg';

            // Make sure the additional image exists
            $additional_image_path = !empty($additional_image1) && file_exists("uploads/" . $additional_image1) ? "uploads/$additional_image1" : 'path/to/default/image.jpg';

            echo "<div class='product-card'>";
            echo "<h3 class='product-name'>$product_name</h3>";

            // Display product image and additional image with hover effect
            echo "<div class='product-image-wrapper'>";
            echo "<a href='product_inside.php?product_id=$product_id'>
                    <img src='$image_path' alt='$product_name' class='product-image' />
                    <img src='$additional_image1' alt='Additional Image' class='additional-image' />
                </a>";
            echo "</div>";

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
    </section>
        <div class="container mx-auto py-8">
            <h1 class="text-center text-2xl font-bold mb-8">LUXURY CATEGORIES</h1>
            <div class="flex justify-center gap-4">
                <div class="relative text-center group">
                    <a href="https://example.com/luxury-perfumes">
                        <img alt="Luxury Perfumes with a bottle and fruits" class="mx-auto mb-4" height="235" width="235" src="https://storage.googleapis.com/a1aa/image/fKnZCbYVraKFuYATFq5Kt14XEA-zniA323G0tSSlt34.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-orange-500 font-bold">LUXURY</h2>
                            <p class="text-gray-200">PERFUMES</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/non-alcoholic-attars">
                        <img alt="Non-Alcoholic Attars with a bottle and flowers" class="mx-auto mb-4" height="235" width="235" src="https://storage.googleapis.com/a1aa/image/AxlL_SKRPAZjGv812G50kxTe9gJ3SEFwlw1fRmZ7i14.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-green-500 font-bold">NON-ALCOHOLIC</h2>
                            <p class="text-gray-200">ATTARS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/moisturizing-fragrance-mists">
                        <img alt="Moisturizing Fragrance Mists with a bottle and flowers" class="mx-auto mb-4" height="235" width="235" src="https://storage.googleapis.com/a1aa/image/9T5hhKpg7cdr4ex6btP7_b_KkP7z5XabiTkx43zjLIE.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-pink-500 font-bold">LONG-LASTING</h2>
                            <p class="text-gray-200">DEODRANTS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/premium-gift-sets">
                        <img alt="Premium Gift Sets with multiple bottles" class="mx-auto mb-4" height="235" width="235" src="https://storage.googleapis.com/a1aa/image/Z8e4-Noq5N_9VCXMQQurG2ur7ziuHHUwbvtsyXBYN00.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-purple-500 font-bold">PREMIUM</h2>
                            <p class="text-gray-200">GIFT SETS</p>
                        </div>
                    </a>
                </div>
                <div class="relative text-center group">
                    <a href="https://example.com/perfume-attar-combo">
                        <img alt="Perfume and Attar Combo with bottles and fruits" class="mx-auto mb-4" height="235" width="235" src="https://storage.googleapis.com/a1aa/image/p_lqrYLuquzZWY8EsK2PT-CAVUy31zZJ1IjUwBemH-U.jpg" width="190"/>
                        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-orange-500 font-bold">PERFUME + ATTAR</h2>
                            <p class="text-gray-200">COMBO</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <!-- NEW ARRIVAL  Section -->
    <section class="container mx-auto py-12 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">NEW ARRIVALS</h1>
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

    // Fetch 4 random products where product_category = 'newarrival'
    $query = "SELECT product_id, product_name, product_image, additional_image1, selling_price, crossed_price, product_size 
              FROM product_details 
              WHERE product_category = 'newarrival' 
              ORDER BY RAND() 
              LIMIT 4";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div class='product-container'>";

        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $image_name = $row['product_image'];
            $additional_image1 = $row['additional_image1'];  // Fetch additional image
            $selling_price = $row['selling_price'];
            $crossed_price = $row['crossed_price'];
            $product_size = $row['product_size']; 

            // Make sure the main image exists
            $image_path = !empty($image_name) && file_exists("uploads/" . $image_name) ? "uploads/$image_name" : 'path/to/default/image.jpg';

            // Make sure the additional image exists
            $additional_image_path = !empty($additional_image1) && file_exists("uploads/" . $additional_image1) ? "uploads/$additional_image1" : 'path/to/default/image.jpg';

            echo "<div class='product-card'>";
            echo "<h3 class='product-name'>$product_name</h3>";

            // Display product image and additional image with hover effect
            echo "<div class='product-image-wrapper'>";
            echo "<a href='product_inside.php?product_id=$product_id'>
                    <img src='$image_path' alt='$product_name' class='product-image' />
                    <img src='$additional_image1' alt='Additional Image' class='additional-image' />
                </a>";
            echo "</div>";

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
    

    <div class="max-w-screen-lg mx-auto space-y-6">
    <!-- Perfumes Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row ">
        <div class="p-6 flex flex-col justify-between md:w-full mx-auto">
            <div class="text-center gap-4">
                <h2 class="text-6xl font-bold text-black mb-2 mt-12">
                    Best deals
                    <span class="text-pink-500">Perfumes</span>
                </h2>
                <p class="text-lg text-magenta-500 mt-8">
                    Discover our exclusive range of perfumes. Complete your collection with the perfect scent.
                </p>
            </div>
            <button class="mt-4 bg-cyan-500 text-white py-2 px-4 rounded hover:bg-cyan-600">
                Buy Now
            </button>
        </div>
        <div class="md:w-full">
            <img alt="Collection of elegant perfume bottles" class="w-full h-full object-cover" height="400" src="https://faunwalk.com/cdn/shop/files/d08851a6-95c2-4e49-9a57-4ee520b40808.jpg" width="600"/>
        </div>
    </div>
    
    <!-- Attar Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row-reverse">
        <div class="p-6 flex flex-col justify-between md:w-full mx-auto">
            <div class="text-center">
                <h2 class="text-6xl font-bold text-black mb-2 mt-12">
                    Celebrate with
                    <span class="text-pink-500">Attar</span>
                </h2>
                <p class="text-lg -500 mt-8">
                    Experience the rich and traditional scents of attar. Check out our exclusive deals.
                </p>
            </div>
            <button class="mt-4 bg-cyan-500 text-white py-2 px-4 rounded hover:bg-magenta-600">
                Buy Now
            </button>
        </div>
        <div class="md:w-full">
            <img alt="Traditional attar bottles with intricate designs" class="w-full h-full object-cover" height="400" src="https://allarabic.in/cdn/shop/articles/how-to-apply-attars.webp?v=1716542129" width="600"/>
        </div>
    </div>
    
    <!-- Deodorants Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row">
        <div class="p-6 flex flex-col justify-between md:w-full mx-auto">
            <div class="text-center">
                <h2 class="text-6xl font-bold text-black mb-2 mt-12">
                    Stay fresh with
                    <span class="text-pink-500">Deodorants</span>
                </h2>
                <p class="text-lg text-magenta-500 mt-2">
                    Keep yourself fresh all day with our range of deodorants. Explore the best deals now.
                </p>
            </div>
            <button class="mt-4 bg-cyan-500 text-white py-2 px-4 rounded hover:bg-cyan-600">
                Buy Now
            </button>
        </div>
        <div class="md:w-full">
            <img alt="Various deodorant bottles in different colors" class="w-full h-full object-cover" height="400" src="https://5daysdeo.com/wp-content/uploads/2021/11/slider-img.png" width="600"/>
        </div>
    </div>
    
    <!-- Essence Oils Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row-reverse">
        <div class="p-6 flex flex-col justify-between md:w-full mx-auto">
            <div class="text-center">
                <h2 class="text-6xl font-bold text-black mb-2 mt-12">
                    Relax with
                    <span class="text-pink-500">Essence Oils</span>
                </h2>
                <p class="text-lg text-magenta-500 mt-2 mt-8">
                    Indulge in the calming effects of our essence oils. Find the perfect scent for relaxation.
                </p>
            </div>
            <button class="mt-4 bg-cyan-500 text-white py-2 px-4 rounded hover:bg-magenta-600">
                Buy Now
            </button>
        </div>
        <div class="md:w-full">
            <img alt="Bottles of essence oils with aromatic herbs" class="w-full h-full object-cover" height="400" src="https://latafa.net/wp-content/uploads/2019/07/essential-oils.jpg" width="600"/>
        </div>
    </div>
</div>

<!-- Why Ruuh Section -->
<div class="why-ruuh-section">
    <h1>WHY RUUH?</h1>
    <div class="flex flex-col md:flex-row justify-center items-center space-y-8 md:space-y-0 md:space-x-16">
        <div class="feature">
            <img alt="Illustration of a bunny representing cruelty-free products" class="mb-4" src="https://static.vecteezy.com/system/resources/previews/030/187/310/non_2x/cruelty-free-icon-not-tested-on-animals-with-rabbit-silhouette-label-illustration-free-vector.jpg" width="200"/>
            <h2>CRUELTY FREE</h2>
            <p>Compassion in every bottle: Our Promise to cruelty-free Products.</p>
        </div>
        <div class="feature">
            <img alt="Illustration of a perfume bottle representing fragrance forward products" class="mb-4" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTm39JVebzFpYE-RJIrk2LktO8P-qqVbo1ZsHzjnlrrUKufrDVjw__lr7KRKmq78KE5q0&usqp=CAU" width="200"/>
            <h2>FRAGRANCE FORWARD</h2>
            <p>Delight in every scent: Our Commitment to premium fragrances.</p>
        </div>
        <div class="feature">
            <img alt="Illustration of a wallet representing affordable luxury" class="mb-4" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWEiP7hGZhYPBJOc0bBEuhcjbuOzI3WLMRG5S1ydGCFrC8Jh8p7ggR9PQZOUp5rcowhFo&usqp=CAU" width="200"/>
            <h2>AFFORDABLE LUXURY</h2>
            <p>Offering Premium Quality and Elegance at an Accessible Price.</p>
        </div>
        <div class="feature">
            <img alt="Illustration of gender symbols representing gender neutral products" class="mb-4" src="https://miro.medium.com/v2/resize:fit:1400/1*ERyDI1Kuwx8OOuBP4d_OAQ.png" width="400"/>
            <h2>GENDER NEUTRAL</h2>
            <p>Elevate your self-care routine with inclusive bath, body, and personal care for all.</p>
        </div>
    </div>
            </div>

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

    <aside class="fixed right-0 top-0 cart h-full bg-white shadow-lg p-4 overflow-y-auto hidden" id="cart">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold" id="cart-item-count">0 items</h2>
        <button class="text-gray-500" id="close-cart"><i class="fas fa-times"></i></button>
    </div>
    <div class="mb-4 text-center text-green-500 font-semibold">We are accepting orders above ₹595/-</div>
    <div class="mb-4 text-center text-green-500 font-semibold hidden" id="free-shipping-message">You are eligible for free shipping!</div>
    <div class="border-b mb-4"></div>
    <div id="cart-items"></div>
    <div class="fixed bottom-0 left-0 w-full bg-white p-4 shadow-lg">
        <!-- <div class="text-right text-gray-500 mb-2 ">
            Super Fast & Free Delivery + Additional up to 10% OFF on all Prepaid Orders
        </div> -->
        <div class="flex justify-end">
        <a href="checkoutdetails.html"  class="w-auto bg-black-500 text-white  rounded" id="checkout-button">
                CHECKOUT • <span id="total-price">Rs. 0.00</span>
            </div>
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
        checkoutButton.classList.remove('bg-green-500');
        checkoutButton.classList.add('bg-black');
        document.getElementById('free-shipping-message').classList.remove('hidden');
    } else {
        checkoutButton.classList.remove('bg-black');
        checkoutButton.classList.add('bg-green-500');
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



        const button = document.querySelector('.add-to-cart');

button.addEventListener('mousemove', function (e) {
    const gloss = button.querySelector('::before'); // Pseudo-element can't be directly accessed, so use a custom element.
    
    const rect = button.getBoundingClientRect(); // Get button's position
    const mouseX = e.clientX - rect.left; // Mouse X position relative to button
    const mouseY = e.clientY - rect.top;  // Mouse Y position relative to button
    
    // Position the gloss based on the mouse's position
    const glossEffect = button.querySelector('::before');
    glossEffect.style.left = `${mouseX - (glossEffect.offsetWidth / 2)}px`; // Center the gloss on the cursor
    glossEffect.style.top = `${mouseY - (glossEffect.offsetHeight / 2)}px`;

    // Show the gloss when hovering
    glossEffect.style.opacity = 1;
});

button.addEventListener('mouseleave', function () {
    const glossEffect = button.querySelector('::before');
    glossEffect.style.opacity = 0; // Hide the gloss when the mouse leaves
});

    </script>
</body>

</html>
