<?php
// Initialize an empty errors array
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather and sanitize the input data
    $supp_first_name = trim($_POST['supp-first-name'] ?? '');
    $supp_middle_name = trim($_POST['supp-middle-name'] ?? '');
    $supp_last_name = trim($_POST['supp-last-name'] ?? '');
    $supp_phone = trim($_POST['supp-phone'] ?? '');
    $supp_email = trim($_POST['supp-email'] ?? '');
    $supp_address = trim($_POST['supp-address'] ?? '');
    $comp_first_name = trim($_POST['comp-first-name'] ?? '');
    $comp_middle_name = trim($_POST['comp-middle-name'] ?? '');
    $comp_last_name = trim($_POST['comp-last-name'] ?? '');
    $comp_type = trim($_POST['comp-type'] ?? '');
    $manager_name = trim($_POST['manager-name'] ?? '');
    $manager_phone = trim($_POST['manager-phone'] ?? '');
    $manager_email = trim($_POST['manager-email'] ?? '');
    $comp_email = trim($_POST['comp-email'] ?? '');
    $comp_address = trim($_POST['comp-address'] ?? '');
    $trader_id = trim($_POST['comp-trader-id'] ?? '');
    $gst_no = trim($_POST['comp-gst-no'] ?? '');
    $pan_no = trim($_POST['comp-pan-no'] ?? '');
    $tan_no = trim($_POST['comp-tan-no'] ?? '');
    $website = trim($_POST['comp-url'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');

    // Validate required fields
    if (empty($supp_first_name)) $errors['supp-first-name'] = 'First Name is required';
    if (empty($supp_last_name)) $errors['supp-last-name'] = 'Last Name is required';
    if (empty($supp_phone) || !preg_match('/^\d{10}$/', $supp_phone)) $errors['supp-phone'] = 'Valid 10-digit phone number is required';
    if (empty($supp_email) || !filter_var($supp_email, FILTER_VALIDATE_EMAIL)) $errors['supp-email'] = 'Valid email is required';
    if (empty($supp_address)) $errors['supp-address'] = 'Address is required';
    if (empty($comp_first_name)) $errors['comp-first-name'] = 'Company First Name is required';
    if (empty($comp_last_name)) $errors['comp-last-name'] = 'Company Last Name is required';
    if (empty($comp_type)) $errors['comp-type'] = 'Company Type is required';
    if (empty($manager_name)) $errors['manager-name'] = 'Manager Name is required';
    if (empty($manager_phone) || !preg_match('/^\d{10}$/', $manager_phone)) $errors['manager-phone'] = 'Valid 10-digit manager phone number is required';
    if (empty($manager_email) || !filter_var($manager_email, FILTER_VALIDATE_EMAIL)) $errors['manager-email'] = 'Valid manager email is required';
    if (empty($comp_address)) $errors['comp-address'] = 'Company Address is required';
    if (empty($trader_id)) $errors['comp-trader-id'] = 'Trader ID is required';
    if (empty($gst_no) || !preg_match('/^\d{15}$/', $gst_no)) $errors['comp-gst-no'] = 'Valid 15-digit GST number is required';
    if (empty($pan_no) || !preg_match('/^[A-Za-z0-9]{10}$/', $pan_no)) $errors['comp-pan-no'] = 'Valid 10-character PAN number is required';
    if (empty($tan_no) || !preg_match('/^[A-Za-z0-9]{10}$/', $tan_no)) $errors['comp-tan-no'] = 'Valid 10-character TAN number is required';
    if (empty($website)) $errors['comp-url'] = 'Website is required';

    // If no errors, proceed to insert into the database
    if (empty($errors)) {
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'perfumes');
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Define the SQL query before preparing it
        $sql = "INSERT INTO supplier (
            first_name, middle_name, last_name, phone, email, address,
            comp_first_name, comp_middle_name, comp_last_name, comp_type,
            manager_name, manager_phone, manager_email,comp_email, comp_address, trader_id, gst_no, pan_no, tan_no,
            website, remarks
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and bind the SQL query
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssssssssss",
            $supp_first_name, $supp_middle_name, $supp_last_name, $supp_phone, $supp_email, $supp_address,
            $comp_first_name, $comp_middle_name, $comp_last_name, $comp_type,
            $manager_name, $manager_phone, $manager_email,$comp_email, $comp_address, $trader_id, $gst_no, $pan_no, $tan_no,
            $website, $remarks
        );

        // Execute the query
        if ($stmt->execute()) {
            echo "New supplier added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Supplier</title>
    <style>
        :root {
            --bs-primary-rgb: 2, 132, 199;
            /* Define the primary color RGB value */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1100px;
            margin-top: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0284c7;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }

        label {
            font-weight: 500;
            color: #343a40;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.25);
            outline: none;
        }

        .form-select {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.25);
            outline: none;
        }

        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #025ea8;
            border-color: #025ea8;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #4e545b;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 90%;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="addSupplier.php" method="POST" class="form-container needs-validation" id="form" novalidate>

            <!-- supplier details -->
            <h1 class="mb-4">New Supplier</h1>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="supp-first-name" class="form-label">Supplier's First Name *</label>
                    <input type="text" class="form-control" id="supp-first-name" name="supp-first-name"
                        placeholder="First Name" pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-first-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="supp-middle-name" class="form-label">Supplier's Middle Name</label>
                    <input type="text" class="form-control" id="supp-middle-name" name="supp-middle-name"
                        placeholder="Middle Name" pattern="^[A-Za-z\s]+$">
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-middle-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="supp-last-name" class="form-label">Supplier's Last Name *</label>
                    <input type="text" class="form-control" id="supp-last-name" name="supp-last-name"
                        placeholder="Last Name" pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-last-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="supp-phone" class="form-label">Supplier's Phone *</label>
                    <input type="text" class="form-control" id="supp-phone" name="supp-phone" placeholder="Phone Number"
                        pattern="^\d{10}$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-phone'] ?? 'Please Enter 10 Digit Phone Number'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="supp-email" class="form-label">Supplier's Email *</label>
                    <input type="email" class="form-control" id="supp-email" name="supp-email" placeholder="Email"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="supp-address" class="form-label">Supplier's Address *</label>
                    <input type="text" class="form-control" id="supp-address" name="supp-address" placeholder="Address"
                        required>
                    <div class="invalid-feedback">
                        Please Enter Address
                    </div>
                </div>
            </div>

            <!-- company details -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="comp-first-name" class="form-label">Company First Name *</label>
                    <input type="text" class="form-control" id="comp-first-name" name="comp-first-name"
                        placeholder="First Name" pattern="^[A-Za-z0-9.]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-first-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="comp-middle-name" class="form-label">Company Middle Name</label>
                    <input type="text" class="form-control" id="comp-middle-name" name="comp-middle-name"
                        placeholder="Middle Name" pattern="^[A-Za-z0-9.]+$">
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-middle-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="comp-last-name" class="form-label">Company Last Name *</label>
                    <input type="text" class="form-control" id="comp-last-name" name="comp-last-name"
                        placeholder="Last Name" pattern="^[A-Za-z0-9.]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-last-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="comp-type" class="form-label">Company Type *</label>
                    <select class="form-select" id="comp-type" name="comp-type" aria-label="Default select example">
                        <option selected>Corporation</option>
                        <option value="1">Proprietorship</option>
                        <option value="2">Partnerships</option>
                        <option value="3">Limited Liability Companies (LLC)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="comp-email" class="form-label">Company Email</label>
                    <input type="email" class="form-control" id="comp-email" name="comp-email" placeholder="Email">
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="comp-url" class="form-label">Company Website *</label>
                    <input type="url" class="form-control" id="comp-url" name="comp-url" placeholder="URL" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-url'] ?? 'Please Enter Valid URL'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="comp-address" class="form-label">Company Address *</label>
                    <input type="text" class="form-control" id="comp-address" name="comp-address" placeholder="Address"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-address'] ?? 'Please Enter Address'; ?>
                    </div>
                </div>
            </div>

            <!-- manager details -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="manager-name" class="form-label">Manager's Name *</label>
                    <input type="text" class="form-control" id="manager-name" name="manager-name"
                        placeholder="Full Name" pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="manager-phone" class="form-label">Manager's Phone *</label>
                    <input type="text" class="form-control" id="manager-phone" name="manager-phone" pattern="^\d{10}$"
                        placeholder="Phone Number" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-phone'] ?? 'Please Enter 10 Digit Phone Number'; ?>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="manager-email" class="form-label">Manager's Email *</label>
                    <input type="email" class="form-control" id="manager-email" name="manager-email" placeholder="Email"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
            </div>

            <!-- company licensing details -->
            <div class="row">
               
                <div class="col-md-3 mb-3">
                    <label for="comp-trader-id" class="form-label">Company Trader ID *</label>
                    <input type="text" class="form-control" id="comp-trader-id" name="comp-trader-id"
                        placeholder="Trader ID" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-trader-id'] ?? 'Please Enter Trader ID'; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="comp-gst-no" class="form-label">Company GST No *</label>
                    <input type="text" class="form-control" id="comp-gst-no" name="comp-gst-no" placeholder="GST No"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-gst-no'] ?? 'Please Enter GST No'; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="comp-pan" class="form-label">Company PAN *</label>
                    <input type="text" class="form-control" id="comp-pan" name="comp-pan" placeholder="PAN No" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-pan'] ?? 'Please Enter PAN'; ?>
                    </div>
                </div>
            </div>
            <div class="row">
    <!-- Company PAN -->
    <div class="col-md-4 mb-3">
        <label for="comp-pan-no" class="form-label">Company PAN No *</label>
        <input type="text" class="form-control" id="comp-pan-no" name="comp-pan-no" placeholder="PAN No" required>
        <div class="invalid-feedback">
            <?php echo $errors['comp-pan-no'] ?? 'Please Enter PAN No'; ?>
        </div>
    </div>

    <!-- Company TAN -->
    <div class="col-md-4 mb-3">
        <label for="comp-tan-no" class="form-label">Company TAN No *</label>
        <input type="text" class="form-control" id="comp-tan-no" name="comp-tan-no" placeholder="TAN No" required>
        <div class="invalid-feedback">
            <?php echo $errors['comp-tan-no'] ?? 'Please Enter TAN No'; ?>
        </div>
    </div>

    <!-- Remarks -->
    <div class="col-md-4 mb-3">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Any additional remarks"></textarea>
        <div class="invalid-feedback">
            <?php echo $errors['remarks'] ?? ''; ?>
        </div>
    </div>
</div>


            <!-- submit button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <script>
        function validateForm(event) {
            let isValid = true;
            
            // Validate Phone Number (Only 10 digits)
            const phoneFields = document.querySelectorAll("input[type='tel']");
            phoneFields.forEach(field => {
                if (!/^[0-9]{10}$/.test(field.value)) {
                    isValid = false;
                    field.classList.add("is-invalid");
                } else {
                    field.classList.remove("is-invalid");
                }
            });
            
            // Validate Email
            const emailFields = document.querySelectorAll("input[type='email']");
            emailFields.forEach(field => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    isValid = false;
                    field.classList.add("is-invalid");
                } else {
                    field.classList.remove("is-invalid");
                }
            });
            
            // Validate ID fields (Alphanumeric only)
            const idFields = document.querySelectorAll("input[data-type='id']");
            idFields.forEach(field => {
                if (!/^[a-zA-Z0-9]+$/.test(field.value)) {
                    isValid = false;
                    field.classList.add("is-invalid");
                } else {
                    field.classList.remove("is-invalid");
                }
            });
            
            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
</body>




</html>