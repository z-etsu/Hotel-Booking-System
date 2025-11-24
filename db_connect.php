<?php
// Configuration for connecting to the database
$servername = "localhost"; // Usually 'localhost'
$username = "root";        // Your MySQL username (e.g., 'root' for XAMPP/WAMP)
$password = "";            // Your MySQL password
$dbname = "hotel";      // **CHANGE THIS to your actual database name**

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop script execution and display error if connection fails
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4 for proper handling of special characters
$conn->set_charset("utf8mb4");

// Note: This connection file will be included in register.php and login.php
?>