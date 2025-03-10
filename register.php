<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfumes";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($lastname) || empty($email) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    $email_check_query = "SELECT * FROM `register` WHERE `email` = '$email'";
    $email_check_result = $con->query($email_check_query);

    if ($email_check_result->num_rows > 0) {
        echo "Email already registered!";
        exit();
    }

    do {
        $customer_id = mt_rand(10000000, 99999999);
        $check_query = "SELECT * FROM `register` WHERE `customer_id` = '$customer_id'";
        $result = $con->query($check_query);
    } while ($result->num_rows > 0);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `register` (`customer_id`, `customer_name`, `customer_middle_name`, `customer_last_name`, `email`, `password`) 
            VALUES ('$customer_id', '$name', '$middlename', '$lastname', '$email', '$hashed_password')";

    if ($con->query($sql) === TRUE) {
        $_SESSION['user_id'] = $customer_id;
        $_SESSION['user_name'] = $name;

        header("Location: register.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex justify-between items-center p-4 bg-white shadow">
        <h1 class="text-2xl font-bold">Welcome to Ruuh perfumes</h1>
        <div class="flex items-center space-x-4">
            <i class="fas fa-user-circle text-2xl text-gray-600"></i>
            
        </div>
    </div>

    <div class="container mx-auto mt-10">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest';?> Your account has been successfully been created please click on button to login !</h1>
            <button type="Login" onclick="window.location.href='login.php';" class="w-20 mt-10 bg-gray-600 text-white py-2 rounded">Login</button>
        </div>
    </div>
</body>
</html>
