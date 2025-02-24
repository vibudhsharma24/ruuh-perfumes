<?php
//require '../../auth.php'; // auth check

require '../../config.php';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
// Fetch logistics details
$orderIds = $conn->query("SELECT DISTINCT order_id FROM logistics");

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
            estimated_delivery_date = '$estimated_delivery_date', 
            WHERE order_id = '$order_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Logistics details updated successfully!');history.back();</script>";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <!-- Hidden input to store the order ID -->
        <input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id; ?>">

        <form method="POST" action="updateLogistics.php">
            <h1 class="mb-4">Update Logistics Details</h1>

            <!-- Form for logistics details -->
            <div id="logisticsDetails">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="vehicle_no" class="form-label">Vehicle Number</label>
                        <input type="text" id="vehicle_no" name="vehicle_no" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" id="driver_name" name="driver_name" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="driver_phone" class="form-label">Driver Phone</label>
                        <input type="text" id="driver_phone" name="driver_phone" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="driver_gst_no" class="form-label">Driver GST No</label>
                        <input type="text" id="driver_gst_no" name="driver_gst_no" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="estimated_date" class="form-label">Estimated Delivery Date</label>
                        <input type="date" id="estimated_date" name="estimated_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="is_transferred" class="form-label">Freight Transferred</label>
                        <select id="is_transferred" name="is_transferred" class="form-select">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                </div>

                <!-- Additional fields for transferred freight -->
                <div id="transferFields" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="client_vehicle_no" class="form-label">Client Vehicle Number</label>
                            <input type="text" id="client_vehicle_no" name="client_vehicle_no" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="client_driver_name" class="form-label">Client Driver Name</label>
                            <input type="text" id="client_driver_name" name="client_driver_name" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="client_driver_phone" class="form-label">Client Driver Phone</label>
                            <input type="text" id="client_driver_phone" name="client_driver_phone" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="client_driver_gst_no" class="form-label">Client Driver GST No</label>
                            <input type="text" id="client_driver_gst_no" name="client_driver_gst_no" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="transfer_date" class="form-label">Transfer Date</label>
                            <input type="date" id="transfer_date" name="transfer_date" class="form-control">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script>
        // Add event listener to toggle transfer fields based on is_transferred selection
        document.getElementById('is_transferred').addEventListener('change', function() {
            const transferFields = document.getElementById('transferFields');
            if (this.value === 'yes') {
                transferFields.style.display = 'block';
            } else {
                transferFields.style.display = 'none';
                // Clear transfer fields when "No" is selected
                document.getElementById('client_vehicle_no').value = '';
                document.getElementById('client_driver_name').value = '';
                document.getElementById('client_driver_phone').value = '';
                document.getElementById('client_driver_gst_no').value = '';
                document.getElementById('transfer_date').value = '';
            }
        });

        const order_id = document.getElementById('order_id').value;

        if (order_id) {
            fetch(`getLogisticDetails.php?order_id=${order_id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('vehicle_no').value = data.vehicle_no;
                    document.getElementById('driver_name').value = data.driver_name;
                    document.getElementById('driver_phone').value = data.driver_phone;
                    document.getElementById('driver_gst_no').value = data.driver_gst_no;
                    document.getElementById('estimated_date').value = data.estimated_delivery_date;
                    document.getElementById('is_transferred').value = data.is_transferred;

                    if (data.is_transferred === 'yes') {
                        document.getElementById('transferFields').style.display = 'block';
                        document.getElementById('client_vehicle_no').value = data.client_vehicle_no;
                        document.getElementById('client_driver_name').value = data.client_driver_name;
                        document.getElementById('client_driver_phone').value = data.client_driver_phone;
                        document.getElementById('client_driver_gst_no').value = data.client_driver_gst_no;
                        document.getElementById('transfer_date').value = data.transfer_date;
                    } else {
                        document.getElementById('transferFields').style.display = 'none';
                    }
                    document.getElementById('logisticsDetails').style.display = 'block';
                });
        } else {
            document.getElementById('logisticsDetails').style.display = 'none';
        }
    </script>
</body>

</html>