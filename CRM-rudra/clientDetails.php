<?php
//require 'auth.php'; // auth check

require 'config.php'; // database connection

// Get the trader_id from the query string
$trader_id = isset($_GET['trader_id']) ? intval($_GET['trader_id']) : 0;

// Fetch client details
$sql = "SELECT 
            CONCAT_WS(' ', first_name, middle_name, last_name) AS full_name,
            phone, email, address,
            CONCAT_WS(' ', comp_first_name, comp_middle_name, comp_last_name) AS company_name,
            comp_type, website, manager_name, manager_phone, manager_email,
            chemical_license, comp_email, comp_address, trader_id, gst_no, pan_no, tan_no, remarks
        FROM 
            client
        WHERE 
            trader_id = $trader_id";

$result = $conn->query($sql);
$client = $result->fetch_assoc();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <title>Client Details</title>
    <style>
        body {
            background-color: #f7f8fa;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        h1 {
            color: #0284c7;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #0284c7;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .card-text strong {
            color: #333;
        }

        .alert {
            font-size: 1.2rem;
            font-weight: bold;
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Client Details</h1>
        <?php if ($client): ?>
            <div class="row g-4">
                <!-- Card 1: Client Info -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Client Information</h5>
                            <p class="card-text"><strong>Name:</strong> <?= htmlspecialchars($client['full_name']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Phone:</strong> <?= htmlspecialchars($client['phone']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?: "Not Available" ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Company Info -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Company Information</h5>
                            <p class="card-text"><strong>Company Name:</strong> <?= htmlspecialchars($client['company_name']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Company Type:</strong> <?= htmlspecialchars($client['comp_type']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Website:</strong> <?= htmlspecialchars($client['website']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($client['comp_address']) ?: "Not Available" ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Manager Info -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Manager Information</h5>
                            <p class="card-text"><strong>Name:</strong> <?= htmlspecialchars($client['manager_name']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Phone:</strong> <?= htmlspecialchars($client['manager_phone']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($client['manager_email']) ?: "Not Available" ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Other Details -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Other Details</h5>
                            <p class="card-text"><strong>Chemical License:</strong> <?= htmlspecialchars($client['chemical_license']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Trader ID:</strong> <?= htmlspecialchars($client['trader_id']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>GST Number:</strong> <?= htmlspecialchars($client['gst_no']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>PAN Number:</strong> <?= htmlspecialchars($client['pan_no']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>TAN Number:</strong> <?= htmlspecialchars($client['tan_no']) ?: "Not Available" ?></p>
                            <p class="card-text"><strong>Remarks:</strong> <?= htmlspecialchars($client['remarks']) ?: "Not Available" ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                Client not found.
            </div>
        <?php endif; ?>
    </div>
</body>

</html>