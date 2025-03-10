<?php
require('fpdf/fpdf.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//require 'auth.php'; // Make sure this includes necessary session checks
require 'config.php'; // Ensure this file uses MySQLi for the database connection

// Get the order ID from the URL
$order_id = isset($_GET['id']) ? $_GET['id'] : '';

// Determine the order type
$type_sql = "SELECT type FROM orders WHERE order_id = '$order_id'";
$type_result = $conn->query($type_sql);
if ($type_result->num_rows === 0) {
    die("Invalid order ID or order type not found.");
}

$order_type = $type_result->fetch_assoc()['type'];

// Adjust query based on order type
if ($order_type === 'Purchase') {
    $sql = "
        SELECT  
            orders.order_id,
            orders.date,
            order_items.batch_code,
            order_items.quantity,
            orders.type,
            orders.client_id,
            orders.supplier_id,
            orders.payment_method,
            orders.payment_date,
            order_items.discount,
            order_items.freight,
            order_items.cgst,
            order_items.sgst,
            order_items.igst,
            order_items.billing_amount,
            CONCAT_WS(' ', supplier.comp_first_name, supplier.comp_middle_name, supplier.comp_last_name) AS company_name,
            supplier.comp_address,
            supplier.gst_no,
            supplier.manager_phone,
            product.general_name,
            product.chemical_size,
            product.pp,
            product.sp
        FROM 
            orders
        LEFT JOIN order_items ON orders.order_id = order_items.order_id
        LEFT JOIN supplier ON orders.supplier_id = supplier.id
        LEFT JOIN product ON order_items.batch_code = product.batch_code
        WHERE orders.order_id = '$order_id'
    ";
} else {
    $sql = "
        SELECT  
            orders.order_id,
            orders.date,
            order_items.batch_code,
            order_items.quantity,
            orders.type,
            orders.client_id,
            orders.supplier_id,
            orders.payment_method,
            orders.payment_date,
            order_items.discount,
            order_items.freight,
            order_items.cgst,
            order_items.sgst,
            order_items.igst,
            order_items.billing_amount,
            CONCAT_WS(' ', client.comp_first_name, client.comp_middle_name, client.comp_last_name) AS company_name,
            client.comp_address,
            client.gst_no,
            client.manager_phone,
            product.general_name,
            product.chemical_size,
            product.pp,
            product.sp
        FROM 
            orders
        LEFT JOIN order_items ON orders.order_id = order_items.order_id
        LEFT JOIN client ON orders.client_id = client.id
        LEFT JOIN product ON order_items.batch_code = product.batch_code
        WHERE orders.order_id = '$order_id'
    ";
}



$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die("No order found with ID $order_id");
}

// Fetch the first result
$order = $result->fetch_assoc();
if (!$order) {
    die("Order data is unavailable.");
}

// Initialize variables
$grand_total = 0;
$subtotal = 0;
$total_tax_cgst = 0;
$total_tax_sgst = 0;
$total_tax_igst = 0;
$item_number = 1;

// Initialize FPDF with Landscape orientation
$pdf = new FPDF('L', 'mm', 'A4'); // Landscape mode
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 11);

// --- Logo Image ---
$logoPath = './assets/images/logo.jpeg'; // Path to your logo image
$logoX = 10;          // X-coordinate for the logo
$logoY = 10;          // Y-coordinate for the logo
$logoWidth = 30;      // Width of the logo (adjust as needed)

// Check if the logo file exists
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, $logoX, $logoY, $logoWidth);
} else {
    // Handle error (e.g., display a message or use a default image)
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(135, 5, 'Logo Not Found', 0, 0, 'L');
}

// Adjust cell position to account for the logo space
$pdf->SetXY($logoX + $logoWidth + 105, $logoY); // Move to the right of the logo
// --- Dealer Details ---
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(135, 5, 'Dealer Details', 0, 1, 'R');


$pdf->SetFont('Arial', '', 10);

$pdf->Cell(135, 5, '', 0, 0, 'L');
$pdf->Cell(135, 5, 'Company Name: Amba Associats', 0, 1, 'R');

$pdf->Cell(135, 5, '', 0, 0, 'L');
$pdf->Cell(135, 5, 'Phone: +91-9999365643', 0, 1, 'R');

$pdf->Cell(135, 5, '', 0, 0, 'L');
$pdf->Cell(135, 5, 'Address: Sector- 7A, Faridabad, Haryana', 0, 1, 'R');

$pdf->Cell(135, 5, '', 0, 0, 'L');
$pdf->Cell(135, 5, 'GST No: 06AANFA6454B1ZN', 0, 1, 'R');
$pdf->Ln(5);

$order_date = new DateTime($order['date']);

$pdf->SetFont('Arial', 'B', 14);
// Invoice Header
$pdf->Cell(270, 10, 'Order Invoice', 1, 1, 'C'); // Adjust the width to fit the page in landscape
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(270, 5, 'Order Date: ' . ($order_date ? $order_date->format('d/m/Y') : 'N/A'), 0, 0, 'L');
$pdf->Ln(8);

// Client Details

// Client and Company Details (Two Columns)
$pdf->SetFont('Arial', 'B', 11);

// Left Column: Customer Details
$pdf->Cell(135, 5, 'Customer Details', 0, 0, 'L');
$pdf->Cell(135, 5, '', 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 10);

// Left Column Content: Customer Details
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(135, 5, 'Company Name: ' . ($order['company_name'] ?? 'N/A'), 0, 0, 'L');
$pdf->Cell(135, 5, '', 0, 1, 'R');

$pdf->Cell(135, 5, 'Manager Phone: +91-' . ($order['manager_phone'] ?? 'N/A'), 0, 0, 'L');
$pdf->Cell(135, 5, '', 0, 1, 'R');

$pdf->Cell(135, 5, 'Company Address: ' . ($order['comp_address'] ?? 'N/A'), 0, 0, 'L');
$pdf->Cell(135, 5, '', 0, 1, 'R');

$pdf->Cell(135, 5, 'GST No: ' . ($order['gst_no'] ?? 'N/A'), 0, 0, 'L');
$pdf->Cell(135, 5, '', 0, 1, 'R');

$pdf->Ln(8); // Add spacing after details


// Table: Order Details (Header)
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, 'Item No', 1, 0, 'C');
$pdf->Cell(50, 5, 'Item Name', 1, 0, 'C');
// $pdf->Cell(30, 10, 'Batch Code', 1, 0, 'C');
$pdf->Cell(25, 5, 'Price/Unit', 1, 0, 'C');
$pdf->Cell(20, 5, 'Quantity', 1, 0, 'C');
$pdf->Cell(20, 5, 'Discount', 1, 0, 'C');
$pdf->Cell(25, 5, 'Freight', 1, 0, 'C');
// $pdf->Cell(25, 10, 'Subtotal', 1, 0, 'C');
$pdf->Cell(25, 5, 'CGST', 1, 0, 'C');
$pdf->Cell(25, 5, 'SGST', 1, 0, 'C');
$pdf->Cell(25, 5, 'IGST', 1, 0, 'C');
$pdf->Cell(40, 5, 'Subtotal After Tax', 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);

$freight_charge = $order['freight'] ?? 0;
$payment_date = $order['payment_date'] ?? null;
$payment_method = $order['payment_method'] ?? 'N/A';

// Loop through all items
do {
    $quantity = $order['quantity'] ?? 0;
    $price = $order['sp'] ?? 0;
    $discount_percent = $order['discount'] ?? 0;
    $cgst = $order['cgst'] ?? 0;
    $sgst = $order['sgst'] ?? 0;
    $igst = $order['igst'] ?? 0;

    $item_total = $quantity * $price;
    $discount_amount = ($item_total * $discount_percent) / 100;
    $subtotal_item = $item_total - $discount_amount + $freight_charge;

    $subtotal_after_tax = $subtotal_item + $cgst + $sgst + $igst;

    $total_tax_cgst += $cgst;
    $total_tax_sgst += $sgst;
    $total_tax_igst += $igst;
    $subtotal += $subtotal_item;

    $pdf->Cell(20, 5, $item_number++, 1, 0, 'C');
    $pdf->Cell(50, 5, $order['general_name'] ?? 'N/A', 1, 0, 'C');
    // $pdf->Cell(30, 10, $order['batch_code'] ?? 'N/A', 1, 0, 'C');
    $pdf->Cell(25, 5, number_format($price, 2), 1, 0, 'C');
    $pdf->Cell(20, 5, $quantity, 1, 0, 'C');
    $pdf->Cell(20, 5, number_format($discount_percent, 2) . '%', 1, 0, 'C');
    $pdf->Cell(25, 5, number_format($freight_charge, 2), 1, 0, 'C');
    // $pdf->Cell(25, 10, number_format($subtotal_item, 2), 1, 0, 'C');
    $pdf->Cell(25, 5, number_format($cgst, 2), 1, 0, 'C');
    $pdf->Cell(25, 5, number_format($sgst, 2), 1, 0, 'C');
    $pdf->Cell(25, 5, number_format($igst, 2), 1, 0, 'C');
    $pdf->Cell(40, 5, number_format($subtotal_after_tax, 2), 1, 1, 'C');
} while ($order = $result->fetch_assoc());

// Calculate Grand Total
$grand_total = $subtotal + $total_tax_cgst + $total_tax_sgst + $total_tax_igst;
$pdf->Cell(275, 5, 'Grand Total: INR ' . number_format($grand_total, 2), 1, 1, 'R');

// Convert Grand Total to Words
function convertNumberToWords($num)
{
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return ucwords($f->format($num));
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(200, 5, 'Total Amount (In Words) :' . convertNumberToWords($grand_total) . ' Only', 0, 'L');

// Add payment details section
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(270, 10, 'Payment Details', 0, 1, 'L'); // Payment details header

$pdf->SetFont('Arial', 'B', 8);
// Table headers for payment details
$pdf->Cell(135, 5, 'Payment Date', 1, 0, 'C');
$pdf->Cell(135, 5, 'Payment Method', 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
// Payment details values

$pdf->Cell(135, 5, ($payment_date ? (new DateTime($order['payment_date']))->format('d/m/Y') : 'N/A'), 1, 0, 'C');
$pdf->Cell(135, 5, ($payment_method ?? 'N/A'), 1, 1, 'C');


// Output PDF
ob_clean(); // Clear output buffer to avoid output issues
$pdf->Output('I', 'Invoice_' . ($order_id ?? 'unknown') . '.pdf');
