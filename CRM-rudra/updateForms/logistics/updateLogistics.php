<?php
//require '../../auth.php'; // auth check
require '../../config.php';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
$logisticsDetails = null; // Initialize to null

if ($order_id) {
    // Fetch logistics details using PHP
    $sql = "SELECT * FROM logistics WHERE order_id = '$order_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $logisticsDetails = $result->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $vehicle_no = $_POST['vehicle_no'];
    $driver_name = $_POST['driver_name'];
    $driver_phone = $_POST['driver_phone'];
    $driver_gst_no = $_POST['driver_gst_no'];
    $estimated_delivery_date = $_POST['estimated_date'];


    $sql = "UPDATE logistics SET 
            vehicle_no = '$vehicle_no', 
            driver_name = '$driver_name', 
            driver_phone = '$driver_phone', 
            driver_gst_no = '$driver_gst_no', 
            estimated_delivery_date = '$estimated_delivery_date' 
            WHERE order_id = '$order_id'";


    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Logistics details updated successfully!');
         location.replace('../../logistics.php');
        </script>";
    } else {
        echo "<script>alert('Error updating logistics details: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Logistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <style>
        body {
            background-color: #f8f9fa;
            /* Light grey background */
        }

        .container {
            background-color: #ffffff;
            /* White background for container */
            border-radius: 10px;
            /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            padding: 30px;
        }

        h1 {
            color: #0284c7;
            /* Primary color for headings */
            font-weight: bold;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            /* Light grey border */
            border-radius: 5px;
            /* Rounded corners */
            padding: 8px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0284c7;
            /* Primary color on focus */
            box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.25);
            /* Primary color glow effect */
        }

        .btn-primary {
            background-color: #0284c7;
            /* Primary color for button */
            border-color: #0284c7;
            border-radius: 8px;
            /* Rounded corners */
            padding: 10px 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            /* Smooth transition on hover */
        }

        .btn-primary:hover {
            background-color: #025ea1;
            /* Darker shade on hover */
            border-color: #025ea1;
        }
    </style>
</head>

<body>
    <div class="container mt-5">

        <form method="POST" action="updateLogistics.php">
            <h1 class="mb-4">Update Logistics Details</h1>
            <!-- Hidden input to store the order ID -->
            <input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id; ?>">

            <!-- Form for logistics details -->
            <div id="logisticsDetails">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="vehicle_no" class="form-label">Vehicle Number</label>
                        <input type="text" id="vehicle_no" name="vehicle_no" class="form-control" value="<?php echo $logisticsDetails ? $logisticsDetails['vehicle_no'] : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" id="driver_name" name="driver_name" class="form-control" value="<?php echo $logisticsDetails ? $logisticsDetails['driver_name'] : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="driver_phone" class="form-label">Driver Phone</label>
                        <input type="text" id="driver_phone" name="driver_phone" class="form-control" value="<?php echo $logisticsDetails ? $logisticsDetails['driver_phone'] : ''; ?>">
                    </div>
                  
                    <div class="col-md-2">
                        <label for="estimated_date" class="form-label">Estimated Delivery Date</label>
                        <input type="date" id="estimated_date" name="estimated_date" class="form-control" value="<?php echo $logisticsDetails ? $logisticsDetails['estimated_delivery_date'] : ''; ?>">
                    </div>

                </div>


                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript for toggling transfer fields
        document.getElementById('is_transferred').addEventListener('change', function() {
            const transferFields = document.getElementById('transferFields');
            if (this.value === 'yes') {
                transferFields.style.display = 'block';
            } else {
                transferFields.style.display = 'none';
            }
        });
    </script>
</body>

</html>