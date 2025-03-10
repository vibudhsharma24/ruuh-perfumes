<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$sql = "SELECT product_id, product_name, product_image FROM product_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        img {
            width: 100px;
            height: auto;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Product List</h2>

<table>
    <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Product ID</th>
        <th>Actions</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img src='uploads/" . htmlspecialchars($row['product_image']) . "' alt='Product Image'></td>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
            echo "<td>
                    <a href='update_product.php?product_id=" . $row['product_id'] . "'><button>Update</button></a>
                    <a href='delete_product.php?product_id=" . $row['product_id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\");'>
                        <button style='background-color: red; color: white;'>Delete</button>
                    </a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No products found.</td></tr>";
    }
    $conn->close();
    ?>

</table>

</body>
</html>
