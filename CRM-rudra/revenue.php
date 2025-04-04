<?php

require 'config.php'; // Database connection

// Initialize variables for filtering
$whereClause = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'];
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($reportType === 'custom' && !empty($startDate) && !empty($endDate)) {
        $whereClause = "WHERE ordermaster.date BETWEEN '$startDate' AND '$endDate'";
    } else {
        switch ($reportType) {
            case 'weekly':
                $whereClause = "WHERE ordermaster.date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
                break;
            case 'fortnightly':
                $whereClause = "WHERE ordermaster.date >= DATE_SUB(CURDATE(), INTERVAL 2 WEEK)";
                break;
            case 'quarterly':
                $whereClause = "WHERE ordermaster.date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
                break;
            case 'half_yearly':
                $whereClause = "WHERE ordermaster.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
                break;
            case 'yearly':
                $whereClause = "WHERE ordermaster.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                break;
        }
    }
}

// Fetch revenue summary
$summarySql = "
    SELECT 
        SUM(revenue.amount_received) - SUM(revenue.amount_paid) AS gross_profit,
        SUM(revenue.amount_received - revenue.amount_paid) AS net_profit,
        SUM(revenue.amount_received) AS net_amount_credited,
        SUM(revenue.due_supplier) AS net_amount_due
    FROM 
        revenue
    LEFT JOIN ordermaster ON revenue.order_id = ordermaster.order_id
    $whereClause
";

$summaryResult = $conn->query($summarySql);

// Check if summary query is successful
if ($summaryResult) {
    $summary = $summaryResult->fetch_assoc();
} else {
    echo "Error fetching summary: " . $conn->error;
    exit;
}

// Fetch the detailed revenue report
$sql = "
    SELECT 
    revenue.order_id,
    revenue.supplier_id,
    revenue.total_amount_supplier,
    revenue.amount_received,
    revenue.due_supplier,
    revenue.amount_paid,
    revenue.order_date,
    ordermaster.type  -- Fetching the 'type' column from the ordermaster table
FROM 
    revenue
LEFT JOIN ordermaster ON revenue.order_id = ordermaster.order_id
$whereClause
ORDER BY ordermaster.date ASC;

";

$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    echo "Error fetching report data: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Revenue Reports</title>
    <style>
        /* General table styling */
        .table {
            ordermaster-collapse: collapse;
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
            bordermaster-radius: 4px;
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
            bordermaster-radius: 5px;
            padding: 8px 15px;
        }

        .btn-primary {
            background-color: #0284c7;
            /* Soft blue for primary button */
            bordermaster-color: #0284c7;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            bordermaster-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red for danger button */
            bordermaster-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            bordermaster-color: #bd2130;
        }
    </style>

</head>

<body>
    <div class="row mw-100 mh-100">
        <!-- Sidebar -->
        <div class="col-3">
            <?php include("sidebar.php") ?>
        </div>
        <div class="col-9">
            <h2 class="mb-4 mt-4">Revenue Reports</h2>

            <!-- Revenue Summary Cards -->
            <!-- <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #0284c7;">
                        <div class="card-body">
                            <h5 class="card-title">Gross Profit</h5>
                            <p class="card-text fs-4">
                                <?php
                                $grossProfit = $summary['gross_profit'] ?? 0;
                                $rupees = floor($grossProfit); // Integer part
                                $paise = round(($grossProfit - $rupees) * 100); // Fractional part
                                echo "₹" . number_format($rupees) . " and " . $paise . "p";
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Net Profit</h5>
                            <p class="card-text fs-4">
                                <?php
                                $netProfit = $summary['net_profit'] ?? 0;
                                $rupees = floor($netProfit); // Integer part
                                $paise = round(($netProfit - $rupees) * 100); // Fractional part
                                echo "₹" . number_format($rupees) . " and " . $paise . "p";
                                ?>
                            </p>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="col-md-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h5 class="card-title">Net Amount Credited</h5>
                            <p class="card-text fs-4">
                                <?php
                                $netAmountCredited = $summary['net_amount_credited'] ?? 0;
                                $rupees = floor($netAmountCredited); // Integer part
                                $paise = round(($netAmountCredited - $rupees) * 100); // Fractional part
                                echo "₹" . number_format($rupees) . " and " . $paise . "p";
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Net Amount Due</h5>
                            <p class="card-text fs-4">
                                <?php
                                $netAmountDue = $summary['net_amount_due'] ?? 0;
                                $rupees = floor($netAmountDue); // Integer part
                                $paise = round(($netAmountDue - $rupees) * 100); // Fractional part
                                echo "₹" . number_format($rupees) . " and " . $paise . "p";
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Filter Form -->
            <!-- <form method="POST" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="reportType" class="form-label">Select Report Type</label>
                        <select id="reportType" name="report_type" class="form-select" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="weekly">Weekly</option>
                            <option value="fortnightly">Fortnightly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="half_yearly">Half-Yearly</option>
                            <option value="yearly">Yearly</option>
                            <option value="custom">Custom Date Range</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" name="start_date" class="form-control" disabled>
                    </div>
                    <div class="col-md-4">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" name="end_date" class="form-control" disabled>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form> -->

            <!-- Revenue Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>order ID</th>
                            <th>order Date</th>
                            <th>order Type</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $orderType = $row['supplier_id'] === null ? 'Sale' : 'Purchase';
                                $orderColor = $row['supplier_id'] === null ? 'text-success' : 'text-danger';
                                echo "<tr>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['order_id']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['order_date']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td class='text-center'>" . ($row['supplier_id'] === null ? htmlspecialchars($row['total_amount_supplier']) : htmlspecialchars($row['total_amount_supplier'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>


                </table>
            </div>
        </div>
    </div>

    <script>
        // Enable/Disable date inputs based on selection
        document.getElementById('reportType').addEventListener('change', function() {
            const customSelected = this.value === 'custom';
            document.getElementById('startDate').disabled = !customSelected;
            document.getElementById('endDate').disabled = !customSelected;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>