<?php
// edit_supplier.php
if (isset($_GET['trader_id'])) {
    $trader_id = $_GET['trader_id'];

    //require '../../auth.php'; // auth check

    require '../../config.php';

    // Fetch supplier data
    $sql = "SELECT * FROM supplier WHERE trader_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trader_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No supplier found with ID: $trader_id";
    }
    $stmt->close();
    $conn->close();
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
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1000px;
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h1 {
            color: #0284c7;
            font-weight: 600;
            margin-bottom: 30px;
        }

        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #343a40;
        }

        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.25);
        }

        .btn-success {
            background-color: #0284c7;
            border-color: #0284c7;
        }

        .btn-success:hover {
            background-color: #0268a0;
            border-color: #025a8c;
        }

        .btn-outline-dark {
            border-color: #343a40;
        }

        .btn-outline-dark:hover {
            background-color: #343a40;
            color: #fff;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="updateSupplier.php" method="POST" class="needs-validation" id="form" novalidate>

            <!-- supplier details -->

            <h1 class="text-center">Update Supplier Details</h1>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="supp-first-name">Supplier's First Name *</label>
                    <input type="text" class="form-control" id="supp-first-name" name="supp-first-name"
                        placeholder="First Name" value="<?php echo htmlspecialchars($row['first_name']); ?>"
                        pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-first-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="supp-middle-name">Supplier's Middle Name</label>
                    <input type="text" class="form-control" id="supp-middle-name" name="supp-middle-name"
                        placeholder="Middle Name" value="<?php echo htmlspecialchars($row['middle_name']); ?>"
                        pattern="^[A-Za-z\s]+$">
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-middle-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="supp-last-name">Supplier's Last Name *</label>
                    <input type="text" class="form-control" id="supp-last-name" name="supp-last-name"
                        placeholder="Last Name" value="<?php echo htmlspecialchars($row['last_name']); ?>"
                        pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-last-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="supp-phone">Supplier's Phone *</label>
                    <input type="text" class="form-control" id="supp-phone" name="supp-phone" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($row['phone']); ?>" pattern="^\d{10}$" min="10" max="10"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-phone'] ?? 'Please Enter 10 Digit Phone Number'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="supp-email">Supplier's Email *</label>
                    <input type="email" class="form-control" id="supp-email" name="supp-email" placeholder="Email"
                        value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['supp-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="supp-address">Supplier's Address *</label>
                    <input type="text" class="form-control" id="supp-address" name="supp-address" placeholder="Address"
                        value="<?php echo htmlspecialchars($row['address']); ?>" required>
                    <div class="invalid-feedback">
                        Please Enter Address
                    </div>
                </div>
            </div>

            <!-- company details -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="comp-first-name">Company First Name *</label>
                    <input type="text" class="form-control" id="comp-first-name" name="comp-first-name"
                        placeholder="First Name" value="<?php echo htmlspecialchars($row['comp_first_name']); ?>"
                        pattern="^[A-Za-z0-9.]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-first-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="comp-middle-name">Company Middle Name *</label>
                    <input type="text" class="form-control" id="comp-middle-name" name="comp-middle-name"
                        placeholder="Middle Name" value="<?php echo htmlspecialchars($row['comp_middle_name']); ?>"
                        pattern="^[A-Za-z0-9.]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-middle-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="comp-last-name">Company Last Name *</label>
                    <input type="text" class="form-control" id="comp-last-name" name="comp-last-name"
                        placeholder="Last Name" value="<?php echo htmlspecialchars($row['comp_last_name']); ?>"
                        pattern="^[A-Za-z0-9.]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-last-name'] ?? 'Please Use AlphaNumerics Only'; ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="comp-type">Company Type *</label>
                    <select class="form-select" id="comp-type" name="comp-type" aria-label="Default select example"
                        value="<?php echo htmlspecialchars($row['comp_type']); ?>">
                        <option selected>Corporation</option>
                        <option>Proprietorship</option>
                        <option>Partnerships</option>
                        <option>Limited Liability Companies (LLC)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="comp-email">Company Email </label>
                    <input type="email" class="form-control" id="comp-email" name="comp-email" placeholder="Email"
                        value="<?php echo htmlspecialchars($row['comp_email']); ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="comp-url">Company Website *</label>
                    <input type="url" class="form-control" id="comp-url" name="comp-url" placeholder="URL"
                        value="<?php echo htmlspecialchars($row['website']); ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-url'] ?? 'Please Enter Valid URL'; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="comp-address">Company Address *</label>
                    <input type="text" class="form-control" id="comp-address" name="comp-address" placeholder="Address"
                        value="<?php echo htmlspecialchars($row['comp_address']); ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['comp-address'] ?? 'Please Enter Address'; ?>
                    </div>
                </div>
            </div>

            <!-- manager details -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="manager-name">Manager's Name *</label>
                    <input type="text" class="form-control" id="manager-name" name="manager-name"
                        placeholder="First Name" value="<?php echo htmlspecialchars($row['manager_name']); ?>"
                        pattern="^[A-Za-z\s]+$" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-name'] ?? 'Please Use Alphabets Only'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="manager-phone">Manager's Phone *</label>
                    <input type="text" class="form-control" id="manager-phone" name="manager-phone" pattern="^\d{10}$"
                        min="10" max="10" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($row['manager_phone']); ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-phone'] ?? 'Please Enter 10 Digit Phone Number'; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="manager-email">Manager's Email *</label>
                    <input type="email" class="form-control" id="manager-email" name="manager-email" placeholder="Email"
                        value="<?php echo htmlspecialchars($row['manager_email']); ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $errors['manager-email'] ?? 'Please Enter Valid Email'; ?>
                    </div>
                </div>
            </div>

            <!-- company licensing deatails -->

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="comp-pan-no">Company PAN No *</label>
                    <input type="text" class="form-control" id="comp-pan-no" name="comp-pan-no" placeholder="PAN No"
                    value="<?php echo htmlspecialchars($row['pan_no']); ?>" pattern="^[A-Za-z0-9]{10}$"
                    minlength="10" maxlength="10" required>
                    <div class="invalid-feedback">
                            <?php echo $errors['comp-pan-no'] ?? 'lease Enter a Valid 10-Character Alphanumeric TAN No'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="comp-tan-no">Company's TAN No *</label>
                        <input type="text" class="form-control" id="comp-tan-no" name="comp-tan-no" placeholder="TAN No"
                        value="<?php echo htmlspecialchars($row['tan_no']); ?>" pattern="^[A-Za-z0-9]{10}$"
                        minlength="10" maxlength="10" required>
                        <div class="invalid-feedback">
                            <?php echo $errors['comp-tan-no'] ?? 'Please Enter a Valid 10-Character Alphanumeric TAN No'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="comp-trader-id">Company Trader ID *</label>
                        <input type="text" class="form-control" id="comp-trader-id" name="comp-trader-id"
                        placeholder="Trader ID" value="<?php echo htmlspecialchars($row['trader_id']); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo $errors['comp-trader-id'] ?? 'Please Trader ID'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="comp-gst-no">Company GST No *</label>
                        <input type="int" class="form-control" id="comp-gst-no" name="comp-gst-no" pattern="\d{15}$"
                        min="10" max="10" placeholder="GST No" value="<?php echo htmlspecialchars($row['gst_no']); ?>"
                        required>
                        <div class="invalid-feedback">
                            <?php echo $errors['comp-gst-no'] ?? 'Please Enter 15 Digit GST No'; ?>
                        </div>
                
                
                </div>
                
                <!--  Remarks  -->
                
                <div class="col-md-9">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks"
                    rows="3"><?php echo htmlspecialchars($row['remarks']); ?></textarea>
                </div>
            </div>
            
            <!-- Submit Button -->
            
            <div class="row mt-3">
                <div class="col-md-6 mb-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-md-6 mb-3 d-grid">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
        <script src="supplier.js"></script>
    </body>
    
    </html>