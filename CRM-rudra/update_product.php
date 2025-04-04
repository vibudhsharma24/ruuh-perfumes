<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product = []; // Initialize empty product array

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details including all images
    $stmt = $conn->prepare("SELECT * FROM product_details WHERE product_id = ?");
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
    $stmt->close();
}

// Handle deletion of images
if (isset($_GET['delete_image']) && isset($_GET['image_index'])) {
    $image_index = $_GET['image_index'];
    $image_column = "additional_image" . $image_index;

    if (!empty($product[$image_column])) {
        $image_path = "uploads/" . $product[$image_column];

        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Remove image from database
        $stmt = $conn->prepare("UPDATE product_details SET $image_column = NULL WHERE product_id = ?");
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF'] . "?product_id=" . $product_id);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $product_name = $_POST['product_name'];
    $product_size = $_POST['product_size'];
    $product_type = $_POST['product_type'];
    $product_version = $_POST['product_version'];
    $lot_number = $_POST['lot_number'];
    $crossed_price = $_POST['crossed_price'];
    $selling_price = $_POST['selling_price'];
    $product_description = $_POST['product_description'];
    $top_notes = $_POST['top_notes'];
    $middle_notes = $_POST['middle_notes'];
    $base_notes = $_POST['base_notes'];
    $additional_information = $_POST['additional_information'];
    $product_category = $_POST['product_category'];
    $product_gender = $_POST['product_gender'];
    $purchase_date = $_POST['purchase_date'];

    // Handle main product image upload
    $image_name = $product['product_image'] ?? ""; // Keep the existing image by default
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = uniqid() . "_" . basename($_FILES['product_image']['name']);
        $target_file = "uploads/" . $image_name;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            echo "Main image uploaded successfully!<br>";
        }
    }

    // Handle additional image uploads (retain old images if no new one is uploaded)
    $additional_images = [];
    for ($i = 1; $i <= 10; $i++) {
        $image_column = "additional_image" . $i;
        if (!empty($_FILES[$image_column]['name'])) {
            $additional_image_name = uniqid() . "_" . basename($_FILES[$image_column]['name']);
            $target_file = "uploads/" . $additional_image_name;
            if (move_uploaded_file($_FILES[$image_column]['tmp_name'], $target_file)) {
                $additional_images[$image_column] = $additional_image_name;
            }
        } else {
            $additional_images[$image_column] = $product[$image_column] ?? null; // Keep old image if none uploaded
        }
    }

    // Update the product details
    $stmt = $conn->prepare("UPDATE product_details SET 
        product_name=?, product_size=?, product_type=?, product_version=?, crossed_price=?, 
        selling_price=?, product_description=?, lot_number=?, product_image=?, additional_image1=?, 
        additional_image2=?, additional_image3=?, additional_image4=?, additional_image5=?, 
        additional_image6=?, additional_image7=?, additional_image8=?, additional_image9=?, 
        additional_image10=?, top_notes=?, middle_notes=?, base_notes=?, additional_information=?, 
        product_category=?, product_gender=?, purchase_date=? WHERE product_id=?");

    $bind_values = [
        $product_name, $product_size, $product_type, $product_version, $crossed_price, $selling_price,
        $product_description, $lot_number, $image_name
    ];

    // Add additional images to bind values
    for ($i = 1; $i <= 10; $i++) {
        $bind_values[] = $additional_images["additional_image" . $i];
    }

    // Add other form values
    $bind_values[] = $top_notes;
    $bind_values[] = $middle_notes;
    $bind_values[] = $base_notes;
    $bind_values[] = $additional_information;
    $bind_values[] = $product_category;
    $bind_values[] = $product_gender;
    $bind_values[] = $purchase_date;
    $bind_values[] = $product_id;

    $types = str_repeat("s", count($bind_values));
    $stmt->bind_param($types, ...$bind_values);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='product_list.php';</script>";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        form { width: 50%; margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 10px; padding: 8px 15px; cursor: pointer; }
        .image-preview { display: flex; gap: 10px; }
        .image-preview img { width: 100px; height: 100px; object-fit: cover; }
        .image-preview button { padding: 5px 10px; background-color: red; color: white; border: none; cursor: pointer; }
    </style>

</head>
<body>

<style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://images.unsplash.com/photo-1517841905240-4729888e0c0d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMTc3M3wwfDF8c2VhcmNofDF8fGJhY2tncm91bmR8ZW58MHx8fHwxNjYyMjY1NzY0&ixlib=rb-1.2.1&q=80&w=1080');
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745;
        }
        .form-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Responsive layout */
            gap: 15px; /* General gap between fields */
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fff;
            color: #333;
            transition: border 0.3s;
            min-width: 150px; /* Set a minimum width for inputs */
        }
        input:focus, select:focus, textarea:focus {
            border-color: #28a745;
            padding: 8px;
            outline: none;
        }
        .notes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Increased gap between notes */
        }
        .note {
            display: flex;
            align-items: center;
            background: #28a745;
            color: white;
            padding: 3px 5px;
            border-radius: 5px;
        }
        .note span {
            cursor: pointer;
            margin-left: 5px;
            font-weight: bold;
        }
        button {
            font-size: 16px;
            background-color: #eb7521;  /* Default button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            display: block;
            margin: 10px auto;  /* Center the button */
        }
        .notes-button {
            background-color: #ff9800;  /* Change button color for notes */
        }
        button:hover {
            background-color: #218838; /* Darker shade on hover */
        }
    </style>


<h2>Update Product</h2>

<form method="post" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

    <label>Product Size:</label>
    <select name="product_size" required>
        <option value="50 ml" <?php if ($product['product_size'] == '50 ml') echo 'selected'; ?>>50 ML</option>
        <option value="100 ml" <?php if ($product['product_size'] == '100 ml') echo 'selected'; ?>>100 ML</option>
        <option value="120 ml" <?php if ($product['product_size'] == '120 ml') echo 'selected'; ?>>120 ML</option>
    </select>

    <label>Product Type:</label>
    <select name="product_type" required>
        <option value="perfume" <?php if ($product['product_type'] == 'perfume') echo 'selected'; ?>>Perfume</option>
        <option value="attar" <?php if ($product['product_type'] == 'attar') echo 'selected'; ?>>Attar</option>
        <option value="deodorants" <?php if ($product['product_type'] == 'deodorants') echo 'selected'; ?>>Deodorants</option>
        <option value="essence oil" <?php if ($product['product_type'] == 'essence oil') echo 'selected'; ?>>Essence Oil</option>
    </select>

    <label>Product Version:</label>
    <select name="product_version" required>
        <option value="inspired" <?php if ($product['product_version'] == 'inspired') echo 'selected'; ?>>Inspired</option>
        <option value="original" <?php if ($product['product_version'] == 'original') echo 'selected'; ?>>Original</option>
    </select>

    <label>Gender:</label>
    <select name="product_gender" required>
        <option value="mens" <?php if ($product['product_gender'] == 'mens') echo 'selected'; ?>>For Men's</option>
        <option value="womens" <?php if ($product['product_gender'] == 'womens') echo 'selected'; ?>>For Women's</option>
    </select>

    <label>Product Category:</label>
    <select name="product_category">
        <option value="bestseller" <?php if ($product['product_category'] == 'bestseller') echo 'selected'; ?>>Best seller</option>
        <option value="newarrival" <?php if ($product['product_category'] == 'newarrival') echo 'selected'; ?>>New arrival</option>
        <option value="original" <?php if ($product['product_category'] == 'original') echo 'selected'; ?>>None</option>
    </select>

    <label>Crossed Price:</label>
    <input type="text" name="crossed_price" value="<?php echo htmlspecialchars($product['crossed_price']); ?>">

    <label>Selling Price:</label>
    <input type="text" name="selling_price" value="<?php echo htmlspecialchars($product['selling_price']); ?>">

    <label>Product Description:</label>
    <textarea name="product_description"><?php echo htmlspecialchars($product['product_description']); ?></textarea>

    <label>Lot Number:</label>
    <textarea name="lot_number"><?php echo htmlspecialchars($product['lot_number']); ?></textarea>

    <label>Top Notes:</label>
    <input type="text" name="top_notes" value="<?php echo htmlspecialchars($product['top_notes']); ?>">

    <label>Middle Notes:</label>
    <input type="text" name="middle_notes" value="<?php echo htmlspecialchars($product['middle_notes']); ?>">

    <label>Base Notes:</label>
    <input type="text" name="base_notes" value="<?php echo htmlspecialchars($product['base_notes']); ?>">

    <label>Additional Information:</label>
    <textarea name="additional_information"><?php echo htmlspecialchars($product['additional_information']); ?></textarea>

    <label>Purchase Date:</label>
    <input type="date" name="purchase_date" value="<?php echo htmlspecialchars($product['purchase_date']); ?>">

    <label>Product Image:</label>
    <input type="file" name="product_image">

    <?php if (!empty($product['product_image'])): ?>
        <div class="image-preview">
            <img src="uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product Image">
        </div>
    <?php endif; ?>
    
    
    
    <!-- Additional Image Uploads -->
    <?php for ($i = 1; $i <= 10; $i++): ?>
    <label>Additional Image <?php echo $i; ?>:</label>

    <?php 
    $image_column = 'additional_image' . $i;
    $image_path = $product[$image_column];

    // Ensure the image path contains 'uploads/'
    if (!empty($image_path) && !str_starts_with($image_path, "uploads/")) {
        $image_path = "uploads/" . $image_path;
    }

    if (!empty($image_path) && file_exists($image_path)): 
    ?>
        <div class="image-preview">
            <img src="<?php echo htmlspecialchars($image_path); ?>" width="100">
            <a href="?product_id=<?php echo $product_id; ?>&delete_image=1&image_index=<?php echo $i; ?>" 
               onclick="return confirm('Are you sure?');">
                <button type="button">Delete</button>
            </a>
        </div>
    <?php endif; ?>

    <input type="file" name="additional_image<?php echo $i; ?>"><br><br>
<?php endfor; ?>


    

    <button type="submit">Update</button>
</form>

<a href="product_list.php">Go Back</a>

<script>
        document.getElementById('form').addEventListener('submit', function (event) {
            let form = this;
            let isValid = form.checkValidity();
            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        document.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => {
            input.addEventListener('input', function () {
                this.setCustomValidity('');
                if (!this.checkValidity()) {
                    this.setCustomValidity('Invalid field.');
                }
            });
        });

        document.querySelectorAll('input[type="email"]').forEach(input => {
            input.addEventListener('input', function () {
                let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(this.value)) {
                    this.setCustomValidity('Invalid email format.');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        document.querySelectorAll('input[pattern]').forEach(input => {
            input.addEventListener('input', function () {
                if (!this.value.match(new RegExp(this.getAttribute('pattern')))) {
                    this.setCustomValidity('Invalid format.');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>

</body>
</html>