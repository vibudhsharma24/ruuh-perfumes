<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];

    // Check if product_id already exists
    $check_stmt = $conn->prepare("SELECT product_id FROM product_details WHERE product_id = ?");
    $check_stmt->bind_param("s", $product_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("Error: A product with this Product ID already exists. Please use a unique Product ID.");
    }
    $check_stmt->close();

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['product_image']['tmp_name']);

        if (!in_array($file_type, $allowed_types)) {
            die("Invalid file type! Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        // Main product details
        $product_name = $_POST['product_name'];
        $product_size = $_POST['product_size'];
        $product_type = $_POST['product_type'];
        $product_version = $_POST['product_version'];
        $crossed_price = $_POST['crossed_price'];
        $selling_price = $_POST['selling_price'];
        $product_description = $_POST['product_description'];
        $top_notes = $_POST['top_notes'];
        $middle_notes = $_POST['middle_notes'];
        $base_notes = $_POST['base_notes'];
        $additional_information = $_POST['additional_information'];
        $product_category = $_POST['product_category'];
        $product_gender = $_POST['product_gender'];
        $lot_number = isset($_POST['lot_number']) && $_POST['lot_number'] !== '' ? $_POST['lot_number'] : NULL;
        $purchase_date = $_POST['purchase_date'];

        // Validate purchase_date format
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $purchase_date)) {
            die("Error: Invalid purchase date format. Use YYYY-MM-DD.");
        }

        $image_name = uniqid() . "_" . basename($_FILES['product_image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Upload main image
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            // Insert new product
            $stmt = $conn->prepare("INSERT INTO product_details 
                (product_name, product_id, product_size, product_type, product_version, crossed_price, selling_price, product_description, product_image, top_notes, middle_notes, base_notes, additional_information, product_category, product_gender, lot_number, purchase_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("sssssssssssssssss", 
                $product_name, $product_id, $product_size, $product_type, $product_version,
                $crossed_price, $selling_price, $product_description, 
                $image_name, $top_notes, $middle_notes, $base_notes, $additional_information,
                $product_category, $product_gender, $lot_number, $purchase_date);

            if ($stmt->execute()) {
                echo "Product uploaded successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Please upload a valid image file.";
    }

    // Handling additional images
    if (isset($_FILES['additional_images'])) {
        $additional_images = $_FILES['additional_images'];
        $additional_image_paths = []; // Store additional image paths
        $columns = [
            'additional_image1', 'additional_image2', 'additional_image3',
            'additional_image4', 'additional_image5', 'additional_image6',
            'additional_image7', 'additional_image8', 'additional_image9', 'additional_image10'
        ];
        $image_index = 0;

        for ($i = 0; $i < count($additional_images['name']); $i++) {
            if ($image_index >= count($columns)) {
                break; // Stop if there are more than 10 additional images
            }

            if ($additional_images['error'][$i] == 0) {
                $image_type = mime_content_type($additional_images['tmp_name'][$i]);
                if (in_array($image_type, $allowed_types)) {
                    $additional_image_name = uniqid() . "_" . basename($additional_images['name'][$i]);
                    $additional_image_target = $target_dir . $additional_image_name;
                    if (move_uploaded_file($additional_images['tmp_name'][$i], $additional_image_target)) {
                        // Map the additional image to the appropriate column in the database
                        $additional_image_paths[$columns[$image_index]] = $additional_image_target;
                        $image_index++;
                    } else {
                        echo "Failed to upload additional image: " . $additional_images['name'][$i] . "<br>";
                    }
                } else {
                    echo "Invalid file type for additional image: " . $additional_images['name'][$i] . "<br>";
                }
            }
        }

        // Update the product with additional images
        if (!empty($additional_image_paths)) {
            $update_query = "UPDATE product_details SET ";
            $update_params = [];
            $update_types = "";

            foreach ($additional_image_paths as $column => $image_path) {
                $update_query .= "$column = ?, ";
                $update_params[] = $image_path;
                $update_types .= "s";
            }

            $update_query = rtrim($update_query, ", ") . " WHERE product_id = ?";
            $update_params[] = $product_id; // Append product_id for WHERE clause
            $update_types .= "s";

            // Prepare and execute the update query
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param($update_types, ...$update_params);
            if ($update_stmt->execute()) {
                echo "Additional images saved successfully!";
            } else {
                echo "Failed to save additional images in the database.";
            }
            $update_stmt->close();
        }
    }
}

$conn->close();
?>
