<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config.php';

$errors = []; // Array to store validation error messages
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    header('Content-Type: application/json'); // Only for POST requests


    // Client variables
    $client_first_name = test_input($_POST['client-first-name']);
    $client_middle_name = test_input($_POST['client-middle-name']);
    $client_last_name = test_input($_POST['client-last-name']);
    $client_phone = test_input($_POST['client-phone']);
    $client_email = test_input($_POST['client-email']);
    $client_address = test_input($_POST['client-address']);

    // Company variables
    $comp_first_name = test_input($_POST['comp-first-name']);
    $comp_middle_name = test_input($_POST['comp-middle-name']);
    $comp_last_name = test_input($_POST['comp-last-name']);
    $comp_type = test_input($_POST['comp-type']);
    $comp_email = test_input($_POST['comp-email']);
    $comp_website = test_input($_POST['comp-url']);
    $comp_address = test_input($_POST['comp-address']);

    // Manager variables
    $manager_name = test_input($_POST['manager-name']);
    $manager_phone = test_input($_POST['manager-phone']);
    $manager_email = test_input($_POST['manager-email']);

    // Company licensing variables
    $comp_chemical_license = test_input($_POST['comp-chemical-license']);
    $comp_trader_id = test_input($_POST['comp-trader-id']);
    $comp_gst_no = test_input($_POST['comp-gst-no']);
    $comp_tan_no = test_input($_POST['comp-tan-no']);
    $comp_pan_no = test_input($_POST['comp-pan-no']);

    $remarks = test_input($_POST['remarks']); // Added missing semicolon here


    // Client details validation
    if (empty($client_first_name) || !preg_match("/^[A-Za-z\s]+$/", $client_first_name)) {
        $errors['client-first-name'] = "Please enter a valid first name using alphabets only.";
    }
    if (!empty($client_middle_name) && !preg_match("/^[A-Za-z\s]+$/",  $client_middle_name)) {
        $errors['client-middle-name'] = "Please use alphabets only for middle name.";
    }
    if (empty($client_last_name) || !preg_match("/^[A-Za-z\s]+$/", $client_last_name)) {
        $errors['client-last-name'] = "Please enter a valid last name using alphabets only.";
    }
    if (empty($client_phone) || !preg_match("/^\d{10}$/", $client_phone)) {
        $errors['client-phone'] = "Please enter a valid 10-digit phone number.";
    }
    if (empty($client_email) || !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $errors['client-email'] = "Please enter a valid email.";
    }
    if (empty($client_address)) {
        $errors['client-address'] = "Please enter the client's address.";
    }

    // Company details validation
    if (empty($comp_first_name) || !preg_match("/^[A-Za-z0-9.]+$/", $comp_first_name)) {
        $errors['comp-first-name'] = "Please enter a valid company first name using alphanumeric characters.";
    }
    if (!empty($comp_middle_name) && !preg_match("/^[A-Za-z0-9.]+$/", $comp_middle_name)) {
        $errors['comp-middle-name'] = "Please use alphanumeric characters only for middle name.";
    }
    if (empty($comp_last_name) || !preg_match("/^[A-Za-z0-9.]+$/", $comp_last_name)) {
        $errors['comp-last-name'] = "Please enter a valid company last name using alphanumeric characters.";
    }
    if (empty($comp_email) || !filter_var($comp_email, FILTER_VALIDATE_EMAIL)) {
        $errors['comp-email'] = "Please enter a valid company email.";
    }
    if (empty($comp_website) || !filter_var($comp_website, FILTER_VALIDATE_URL)) {
        $errors['comp-url'] = "Please enter a valid URL.";
    }
    if (empty($comp_address)) {
        $errors['comp-address'] = "Please enter the company's address.";
    }

    // Manager details validation
    if (empty($manager_name) || !preg_match("/^[A-Za-z\s]+$/", $manager_name)) {
        $errors['manager-name'] = "Please enter a valid manager's name using alphabets only.";
    }
    if (empty($manager_phone) || !preg_match("/^\d{10}$/", $manager_phone)) {
        $errors['manager-phone'] = "Please enter a valid 10-digit manager's phone number.";
    }
    if (empty($manager_email) || !filter_var($manager_email, FILTER_VALIDATE_EMAIL)) {
        $errors['manager-email'] = "Please enter a valid manager's email.";
    }

    // Licensing details validation
    if (empty($comp_chemical_license) || !preg_match("/^[A-Za-z0-9]+$/", $comp_chemical_license)) {
        $errors['comp-chemical-license'] = "Please enter a valid chemical license using alphanumeric characters.";
    }
    if (empty($comp_trader_id)) {
        $errors['comp-trader-id'] = "Please enter the trader ID.";
    }
    if (empty($comp_gst_no) || !preg_match("/^\d{15}$/", $comp_gst_no)) {
        $errors['comp-gst-no'] = "Please enter a valid 15-digit GST number.";
    }
    if (empty($comp_tan_no) || !preg_match("/^[A-Za-z0-9]{10}$/", $comp_tan_no)) {
        $errors['comp-tan-no'] = "Please enter a valid 10-character alphanumeric TAN number.";
    }
    if (empty($comp_pan_no) || !preg_match("/^[A-Za-z0-9]{10}$/", $comp_pan_no)) {
        $errors['comp-pan-no'] = "Please enter a valid 10-character alphanumeric PAN number.";
    }


    // Process form data if no errors
    if (empty($errors)) {
        // Insert or process form data
        $sql = "UPDATE client SET
first_name = '$client_first_name',
middle_name = '$client_middle_name',
last_name = '$client_last_name',
phone = '$client_phone',
email = '$client_email',
address = '$client_address',
comp_first_name = '$comp_first_name',
comp_middle_name = '$comp_middle_name',
comp_last_name = '$comp_last_name',
comp_type = '$comp_type',
manager_name = '$manager_name',
manager_phone = '$manager_phone',
manager_email = '$manager_email',
chemical_license = '$comp_chemical_license',
comp_email = '$comp_email',
comp_address = '$comp_address',
trader_id = '$comp_trader_id',
gst_no = '$comp_gst_no',
pan_no = '$comp_pan_no',
tan_no = '$comp_tan_no',
website = '$comp_website',
remarks = '$remarks'
WHERE trader_id = '$comp_trader_id'";

        // Execute the query
        if ($conn->query($sql) === true) {
            echo json_encode(['success' => true, 'message' => 'Submission Successful']);
        } else {
            //echo json_encode(['success' => false, 'message' => 'Database error: ' . strip_tags($con->error)]);
            echo "$conn->error";
        }
        //echo json_encode(['success' => true, 'message' => 'Submission Successful']);
    } else {
        echo json_encode(['success' => false, 'errors' => strip_tags($errors)]);
    }

    exit();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<div id="error-container" style="color: red;"></div>