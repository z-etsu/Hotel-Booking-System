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
        contact_number VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Bookings table (NEW) - With support for review system
    "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        room_name VARCHAR(100) NOT NULL,
        room_number VARCHAR(10) DEFAULT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        price_per_night DECIMAL(10, 2) NOT NULL,
        number_of_nights INT NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        number_of_guests INT NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('active', 'assigned', 'finished', 'cancelled') DEFAULT 'active',
        has_review TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_status (status),
        INDEX idx_check_in (check_in)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Reviews table - for storing user reviews of bookings
    "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT NOT NULL,
        user_id INT NOT NULL,
        room_name VARCHAR(100) NOT NULL,
        stars INT NOT NULL CHECK (stars >= 1 AND stars <= 5),
        description TEXT NOT NULL,
        review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_booking_id (booking_id),
        INDEX idx_user_id (user_id),
        INDEX idx_stars (stars),
        UNIQUE KEY unique_booking_review (booking_id)
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
