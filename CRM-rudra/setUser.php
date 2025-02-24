<?php
// Initialize the database connection
$host = 'localhost';
$dbname = 'amba_associats';
$username = 'root'; // Replace with your DB username
$password = 'root';     // Replace with your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hash a password and insert a user
    $hashedPassword = password_hash('password', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (username, password_hash) VALUES (:username, :password_hash)");
    $stmt->execute(['username' => 'rudra', 'password_hash' => $hashedPassword]);

    echo "User added successfully.";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
