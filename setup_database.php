<?php
// Database Setup Script - Run this to initialize bookings table
require_once 'db_connect.php';

echo "=== Hotel Booking System - Database Setup ===\n\n";

// SQL statements to create tables
$sql_statements = [
    // Users table (should already exist)
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        middle_initial CHAR(1),
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        birthday DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
    
    // Bookings table (NEW) - Without foreign key initially, we'll add it after
    "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        room_name VARCHAR(100) NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        price_per_night DECIMAL(10, 2) NOT NULL,
        number_of_nights INT NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        number_of_guests INT NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('active', 'cancelled') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_status (status),
        INDEX idx_check_in (check_in)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

$success_count = 0;
$error_count = 0;

foreach ($sql_statements as $index => $sql) {
    if ($conn->query($sql) === TRUE) {
        $success_count++;
        echo "✓ Query " . ($index + 1) . " executed successfully!\n";
    } else {
        $error_count++;
        echo "✗ Error in Query " . ($index + 1) . ": " . $conn->error . "\n";
    }
}

echo "\n=== Setup Complete ===\n";
echo "Successful: $success_count\n";
echo "Errors: $error_count\n";

// Verify tables exist
echo "\n=== Verifying Tables ===\n";
$result = $conn->query("SHOW TABLES LIKE 'bookings'");
if ($result->num_rows > 0) {
    echo "✓ Bookings table exists!\n";
    
    // Show table structure
    echo "\nBookings Table Structure:\n";
    $structure = $conn->query("DESCRIBE bookings");
    while ($row = $structure->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "✗ Bookings table NOT found\n";
}

$conn->close();
echo "\n✓ Database setup complete! You can now use the bookings feature.\n";
?>
