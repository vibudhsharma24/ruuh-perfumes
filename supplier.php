<?php
//require 'auth.php'; // auth check

require 'config.php'; // database connection

// Fetch supplier details
$sql = "
    SELECT 
        CONCAT_WS(' ', first_name, middle_name, last_name) AS full_name,
        phone,
        email,
        address,
        CONCAT_WS(' ', comp_first_name, comp_middle_name, comp_last_name) AS company_name,
        comp_type,
        website,
        manager_name,
        manager_phone,
        manager_email,
        comp_email,
        comp_address,
        trader_id,
        gst_no,
        pan_no,
        tan_no,
        remarks
    FROM 
        supplier
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Supplier Details</title>
    <style>
        /* General table styling */
        .table {
            border-collapse: collapse;
            background-color: #ffffff;
            /* White background for the table */
        }

        /* Header Styling (lighter color) */
        .table thead {
            background-color: #f1f3f5;
            /* Light grey background */
            color: #495057;
            /* Dark grey text for contrast */
            text-transform: uppercase;
            font-weight: bold;
        }

        .table thead th {
            text-align: center;
            padding: 12px;
            background-color: #0284c7;
        }

        /* Table body styling */
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
            color: #495057;
            /* Dark grey text */
        }

        /* Zebra striping for rows */
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
            /* Light grey for even rows */
        }

        /* Hover effect for rows */
        .table tbody tr:hover {
            background-color: #e2e6ea;
            /* Slightly darker hover color */
            cursor: pointer;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }

        /* Improve scrollbar appearance */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #343a40;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #495057;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Add a subtle shadow for the table */
        .table {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.27);
            /* Soft shadow */
        }

        /* Add professional button styles */
        .btn {
            border-radius: 5px;
            padding: 8px 15px;
        }

        .btn-primary {
            background-color: #0284c7;
            /* Soft blue for primary button */
            border-color: #0284c7;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red for danger button */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }


        .details-btn {
            background-color: #008CBA;
            color: white;
        }
        .details-btn:hover {
            background-color: #007B9E;
        }
        .update-btn {
            background-color: #f44336;
            color: white;
        }
        .update-btn:hover {
            background-color: #d32f2f;
        }
        .update-btn, .details-btn {
            padding: 8px 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .btn-main{
            display: flex;
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
            <h2 class="mb-4 mt-4">Supplier Details</h2>
            <a href="addSupplier.php"><button type="button" class="btn btn-primary mb-4">Add New Supplier</button></a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Actions</th>
                            <th>Company Name</th>
                            <th>Company Type</th>
                            <th>Company Address</th>
                            <th>Manager Name</th>
                            <th>Manager Phone</th>
                            <th>GST No</th>
                            <th>PAN No</th>
                            <th>TAN No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and output them
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";



                                    echo "<td class='btn-main'><a href='./updateForms/supplier/updateSupplierDetails.php?trader_id=" . urlencode($row['trader_id']) . "'>
                                            <button style=\"margin-bottom: 5px;\" type='button' class='update-btn'>Edit</button>
                                        </a>
                                        <a href='./supplierDetails.php?trader_id=" . urlencode($row['trader_id']) . "'>
                                            <button type='button' class='details-btn'>Details</button>
                                    </a>
                                </td>";
                                echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['comp_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['comp_address']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['manager_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['manager_phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['gst_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['pan_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tan_no']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='20'>No suppliers found.</td></tr>";
                        }

                        // Close the connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>