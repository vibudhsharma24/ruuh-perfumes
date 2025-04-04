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
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Email and password are required!";
        exit();
    }

    $sql = "SELECT * FROM `register` WHERE `email` = '$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['customer_id'];
            $_SESSION['user_name'] = $user['customer_name'];
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with this email!";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-white text-black">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">Log In</h1>
            <div class="text-sm text-gray-500 mt-2">
                <a href="index (4).html" class="hover:underline">Home</a> &gt; <a href="login.php" class="hover:underline">Account</a>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-center items-start md:space-x-16">
            <div class="w-full md:w-1/2 mb-8 md:mb-0">
                <h2 class="text-xl font-bold mb-4">Log In</h2>
                <form action="login.php" method="POST">
                    <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <a href="#" class="text-sm text-gray-500 hover:underline">Forgot your password?</a>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-black text-white py-2 rounded">Sign In</button>
                    </div>
                </form>
            </div>

            <div class="w-full md:w-1/2">
                <h2 class="text-xl font-bold mb-4">New Customer</h2>
                <p class="text-gray-500 mb-4">Sign up for early Sale access plus tailored new arrivals, trends and promotions. To opt out, click unsubscribe in our emails.</p>
                <a href="register.html" class="w-full">
                    <button class="w-full bg-black text-white py-2 rounded">Register</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>