<?php
// Initialize the database connection
$host = 'localhost';
$dbname = 'perfumes';
$username = 'root'; // Replace with your DB username
$password = '';     // Replace with your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the username and hashed password into the database
        $stmt = $conn->prepare("INSERT INTO user (username, password_hash) VALUES (:username, :password_hash)");

        // Execute the query with the values from the form
        $stmt->execute(['username' => $username, 'password_hash' => $hashedPassword]);

        echo "User added successfully.";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
