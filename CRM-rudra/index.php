<?php
session_start();

require 'config.php';

// Redirect to shop if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: revenue.php');
    exit;
}

$error = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the statement
    $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->bind_param('s', $username); // Bind the username as a string parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify the password
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: revenue.php'); // Redirect to the shop page
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8fafc;
            /* Light background */
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Professional font */
        }

        .nav-custom-color {
            background-color: #0284c7;
            color: white !important;
        }

        .nav {
            --bs-nav-link-padding-x: 1rem;
            --bs-nav-link-padding-y: 0.5rem;
            --bs-nav-link-font-weight: ;
            --bs-nav-link-color: white;
            --bs-nav-link-hover-color: var(--bs-link-hover-color);
            --bs-nav-link-disabled-color: #6c757d;
            display: flex;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

        .nav-link {
            display: block;
            /* padding: 10px 20px; */
            margin: 10px 20px;
            font-size: 18px;
            font-weight: 500;
            color: white;
            text-decoration: none;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }


        @media (prefers-reduced-motion: reduce) {
            .nav-link {
                transition: none;
            }
        }

        .nav-link:hover,
        .nav-link:focus {
            color: var(--bs-nav-link-hover-color);
        }

        .nav-link.disabled {
            color: var(--bs-nav-link-disabled-color);
            pointer-events: none;
            cursor: default;
        }

        .nav-tabs {
            --bs-nav-tabs-border-width: 1px;
            --bs-nav-tabs-border-color: #dee2e6;
            --bs-nav-tabs-border-radius: 0.375rem;
            --bs-nav-tabs-link-hover-border-color: #e9ecef #e9ecef #dee2e6;
            --bs-nav-tabs-link-active-color: #495057;
            --bs-nav-tabs-link-active-bg: #fff;
            --bs-nav-tabs-link-active-border-color: #dee2e6 #dee2e6 #fff;
            border-bottom: var(--bs-nav-tabs-border-width) solid var(--bs-nav-tabs-border-color);
        }

        .nav-tabs .nav-link {
            margin-bottom: calc(-1 * var(--bs-nav-tabs-border-width));
            background: none;
            border: var(--bs-nav-tabs-border-width) solid transparent;
            border-top-left-radius: var(--bs-nav-tabs-border-radius);
            border-top-right-radius: var(--bs-nav-tabs-border-radius);
        }

        .nav-tabs .nav-link:hover,
        .nav-tabs .nav-link:focus {
            isolation: isolate;
            border-color: var(--bs-nav-tabs-link-hover-border-color);
        }

        .nav-tabs .nav-link.disabled,
        .nav-tabs .nav-link:disabled {
            color: var(--bs-nav-link-disabled-color);
            background-color: transparent;
            border-color: transparent;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
            color: var(--bs-nav-tabs-link-active-color);
            background-color: var(--bs-nav-tabs-link-active-bg);
            border-color: var(--bs-nav-tabs-link-active-border-color);
        }

        .navbar {
            --bs-navbar-padding-x: 0;
            --bs-navbar-padding-y: 0.5rem;
            --bs-navbar-color: rgba(0, 0, 0, 0.55);
            --bs-navbar-hover-color: rgba(0, 0, 0, 0.7);
            --bs-navbar-disabled-color: rgba(0, 0, 0, 0.3);
            --bs-navbar-active-color: rgba(0, 0, 0, 0.9);
            --bs-navbar-brand-padding-y: 0.3125rem;
            --bs-navbar-brand-margin-end: 1rem;
            --bs-navbar-brand-font-size: 1.25rem;
            --bs-navbar-brand-color: rgba(0, 0, 0, 0.9);
            --bs-navbar-brand-hover-color: rgba(0, 0, 0, 0.9);
            --bs-navbar-nav-link-padding-x: 0.5rem;
            --bs-navbar-toggler-padding-y: 0.25rem;
            --bs-navbar-toggler-padding-x: 0.75rem;
            --bs-navbar-toggler-font-size: 1.25rem;
            --bs-navbar-toggler-icon-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            --bs-navbar-toggler-border-color: rgba(0, 0, 0, 0.1);
            --bs-navbar-toggler-border-radius: 0.375rem;
            --bs-navbar-toggler-focus-width: 0.25rem;
            --bs-navbar-toggler-transition: box-shadow 0.15s ease-in-out;
            /* position: absolute;
            top: 0; */
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: var(--bs-navbar-padding-y) var(--bs-navbar-padding-x);
            height: 80px;
        }

        .navbar>.container,
        .navbar>.container-fluid,
        .navbar>.container-sm,
        .navbar>.container-md,
        .navbar>.container-lg,
        .navbar>.container-xl,
        .navbar>.container-xxl {
            display: flex;
            flex-wrap: inherit;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            padding-top: var(--bs-navbar-brand-padding-y);
            padding-bottom: var(--bs-navbar-brand-padding-y);
            margin-right: var(--bs-navbar-brand-margin-end);
            font-size: var(--bs-navbar-brand-font-size);
            color: white;
            text-decoration: none;
            white-space: nowrap;
        }

        .navbar-brand:hover,
        .navbar-brand:focus {
            color: var(--bs-navbar-brand-hover-color);
        }

        .navbar-nav {
            --bs-nav-link-padding-x: 0;
            --bs-nav-link-padding-y: 0.5rem;
            --bs-nav-link-font-weight: ;
            --bs-nav-link-color: white;
            --bs-nav-link-hover-color: var(--bs-navbar-hover-color);
            --bs-nav-link-disabled-color: var(--bs-navbar-disabled-color);
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

        .navbar-nav .show>.nav-link,
        .navbar-nav .nav-link.active {
            color: white;
        }

        .navbar-nav .dropdown-menu {
            position: static;
        }

        .navbar-text {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            color: white;
        }

        .navbar-text a,
        .navbar-text a:hover,
        .navbar-text a:focus {
            color: white;
        }

        .login-container {
            background-color: #fff;
            /* White container */
            padding: 4rem 3rem;
            /* Increased padding */
            border-radius: 15px;
            /* More rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* More pronounced shadow */
            max-width: 450px;
            /* Increased max-width */
            width: 100%;
        }

        .login-container .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            /* Increased margin */
        }

        .login-container .logo img {
            width: 250px;
            /* Increased logo size */
        }

        .login-container h2 {
            color: #0284c7;
            /* Primary color for heading */
            text-align: center;
            margin-bottom: 2rem;
            /* Increased margin */
            font-weight: 600;
            /* Make heading bolder */
        }

        .login-container .form-control {
            border-radius: 10px;
            /* More rounded inputs */
            margin-bottom: 1.5rem;
            height: 55px;
            /* Increased input height */
            font-size: 1rem;
            /* Larger font size */
            border: 1px solid #ced4da;
            /* Lighter border color */
            padding: 0.5rem 1rem;
            /* More padding */
        }

        .login-container .form-control:focus {
            border-color: #86b7fe;
            /* Lighter blue border on focus */
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.25);
            /* Primary color shadow on focus */
        }

        .login-container .btn-primary {
            height: 55px;
            /* Increased button height */
            width: 100%;
            /* Button takes full width */
            background-color: #0284c7;
            /* Primary color for button */
            border: none;
            border-radius: 10px;
            /* More rounded button */
            font-size: 1.1rem;
            /* Larger font size */
            font-weight: 500;
            /* Bolder font weight */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }

        .login-container .btn-primary:hover {
            background-color: #025ea1;
            /* Darker shade of primary color on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }

        .login-container .error-message {
            color: #dc3545;
            /* Red color for error */
            background-color: #f8d7da;
            /* Light red background */
            border: 1px solid #f5c6cb;
            /* Light red border */
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            /* Slightly smaller font size */
        }
    </style>
</head>

<body>
    <div class="row" style="width: 100%; display: flex; justify-content: center; align-items: center">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light nav-custom-color col-12">
            <div class="container px-4 px-lg-5 ">
                <a class="navbar-brand" href="../index.html">Amba Associates</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="../index.html">Home</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="../shop.html">Shop</a></li>
                        <li class="nav-item"><a class="nav-link" href="../feedback.html">Feedback</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">CRM</a></li>
                    </ul>
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>




        <!-- login container -->
        <div class="login-container col-12" style="margin-top: 20px; margin-bottom: 30px">
            <div class="logo w-100">
                <img src="./assets/images/logo.jpeg" alt="Company Logo">
            </div>
            <h2>Login</h2>
            <?php if (!empty($error))
                echo "<p class='error-message'>$error</p>"; ?>
            <form method="post">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>