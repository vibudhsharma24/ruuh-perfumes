<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT * FROM product_details";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Product</title>
    <style>
        /* General table styling */


        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .add-product-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: fit-content;
            margin: 10px auto;
            transition: background-color 0.3s;
        }
        .add-product-btn:hover {
            background-color: #218838;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #003366;
            color: white;
        }
        .update-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .update-btn {
            background-color: #008CBA;
            color: white;
        }
        .update-btn:hover {
            background-color: #007B9E;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .product-img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }
        /* Truncate long text with ellipsis */
        .truncate {
            max-width: 150px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        /* Responsive Table for Small Screens */
        @media screen and (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            tr {
                margin-bottom: 10px;
                border: 1px solid #ddd;
            }
            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
            }
        }



   
    </style>

</head>

<body>
    <div class="row mw-100 mh-100">
        <!-- Sidebar -->
        <div class="col-3">
            <?php include("sidebar.php") ?>
        </div>

        <!-- Main Content -->
        <div id="main" class="col-9">
            <h2 class="mb-4 mt-4">Product Details</h2>
            <a href="./addForms/product/addProduct.html"><button type="button" class="btn btn-primary mb-4">Add New
                    Product</button></a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                        <th>ACTIONS</th>
                        <th>IMAGE</th>
                        <th> ID</th>
                        <th> NAME</th>
                        <th>LOT</th>
                        <th>SIZE</th>
                        <th>TYPE</th>
                        <th>VERSION</th>
                        <th>GENDER</th>
                        <th>CATEGORY</th>
                        <th>S.P</th>
                        <th>C.P</th>
                        <th>IMAGE 1</th>
                        <th>IMAGE 2</th>
                        <th>IMAGE 3</th>
                        <th>IMAGE 4</th>
                        <th>IMAGE 5</th>
                        <th>IMAGE 6</th>
                        <th>IMAGE 7</th>
                        <th>IMAGE 8</th>
                        <th>IMAGE 9</th>
                        <th>IMAGE 10</th>
                        <th>DESC</th>
                        <th>NOTES</th>
                        <th>INFO</th>
                        <th>PURCHASE DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        
                        <td>
                        <a href="update_product.php?product_id=<?= $row['product_id']; ?>">
                            <button class="update-btn">Update</button>
                        </a>

                                <a href="delete_product.php?id=<?= $row['product_id']; ?>" onclick="return confirm('Are you sure?');">
                                    <button class="delete-btn">Delete</button>
                                </a>
                            </td>
                        <td>
                            <img src="uploads/<?= $row['product_image']; ?>" class="product-img" alt="Product Image">
                        </td>
                        
                        <td><?= $row['product_id']; ?></td>
                        <td class="truncate" title="<?= $row['product_name']; ?>"><?= $row['product_name']; ?></td>
                        <td><?= $row['lot_number']; ?></td>
                        <td><?= $row['product_size']; ?></td>
                        <td><?= $row['product_type']; ?></td>
                        <td><?= $row['product_version']; ?></td>
                        <td><?= $row['product_gender']; ?></td>
                        <td><?= $row['product_category']; ?></td>
                        <td style="color:blue">₹<?= $row['selling_price']; ?></td>
                        <td style="color:red"><s>₹<?= $row['crossed_price']; ?></s></td>
                        <td>
            <?php if (!empty($row['additional_image1'])): ?>
                <img src="uploads/<?= $row['additional_image1']; ?>" class="product-img" alt="Image 1">
            <?php else: ?>
                <p> not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image2'])): ?>
                <img src="<?= $row['additional_image2']; ?>" class="product-img" alt="Image 2">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image3'])): ?>
                <img src="<?= $row['additional_image3']; ?>" class="product-img" alt="Image 3">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image4'])): ?>
                <img src="<?= $row['additional_image4']; ?>" class="product-img" alt="Image 4">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image5'])): ?>
                <img src="<?= $row['additional_image5']; ?>" class="product-img" alt="Image 5">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image6'])): ?>
                <img src="<?= $row['additional_image6']; ?>" class="product-img" alt="Image 6">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image7'])): ?>
                <img src="<?= $row['additional_image7']; ?>" class="product-img" alt="Image 7">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image8'])): ?>
                <img src="<?= $row['additional_image8']; ?>" class="product-img" alt="Image 8">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image9'])): ?>
                <img src="<?= $row['additional_image9']; ?>" class="product-img" alt="Image 9">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($row['additional_image10'])): ?>
                <img src="<?= $row['additional_image10']; ?>" class="product-img" alt="Image 10">
            <?php else: ?>
                <p>not added</p>
            <?php endif; ?>
        </td>
                        <td class="truncate" title="<?= $row['product_description']; ?>"><?= $row['product_description']; ?></td>
                        <td class="truncate" title="Top: <?= $row['top_notes']; ?>, Middle: <?= $row['middle_notes']; ?>, Base: <?= $row['base_notes']; ?>">
                            <?= $row['top_notes']; ?>, <?= $row['middle_notes']; ?>, <?= $row['base_notes']; ?>
                        </td>
                        <td><?= $row['additional_information']; ?></td>
                        <td><?= $row['purchase_date']; ?></td>
                        
                    
                        
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            let batchCodeToDelete = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    batchCodeToDelete = this.dataset.batchCode;
                    document.getElementById('deleteBatchCode').value = batchCodeToDelete;
                    deleteModal.show();
                });
            });

            confirmDeleteBtn.addEventListener('click', function() {
                const enteredPassword = document.getElementById('password').value;
                const batchCode = document.getElementById('deleteBatchCode').value;

                // AJAX request to server-side script for password verification and deletion
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'deleteProduct.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert('Product deleted successfully.');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert('An error occurred.');
                    }
                    deleteModal.hide();
                };
                xhr.send('batch_code=' + encodeURIComponent(batchCode) + '&password=' + encodeURIComponent(enteredPassword));
            });
        });
    </script>

</body>

</html>