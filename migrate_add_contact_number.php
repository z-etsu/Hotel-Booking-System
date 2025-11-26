<?php
// Migration Script - Add contact_number column to users table for existing installations
require_once 'db_connect.php';

echo "=== Hotel Booking System - Contact Number Migration ===\n\n";

// Check if contact_number column already exists
$check_query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'contact_number'";
$result = $conn->query($check_query);

if ($result && $result->num_rows > 0) {
    echo "✓ contact_number column already exists in users table.\n";
    echo "✓ Migration is not needed.\n";
    $conn->close();
    exit;
}

// Add contact_number column
$alter_query = "ALTER TABLE users ADD COLUMN contact_number VARCHAR(20) AFTER birthday";

if ($conn->query($alter_query) === TRUE) {
    echo "✓ Successfully added contact_number column to users table!\n";

    // Verify the column was added
    $verify_query = "DESCRIBE users";
    $result = $conn->query($verify_query);

    echo "\nUsers Table Structure (Updated):\n";
    while ($row = $result->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }

    echo "\n✓ Migration completed successfully!\n";
    echo "Users can now add contact numbers during registration and in their profile settings.\n";
} else {
    echo "✗ Error adding contact_number column: " . $conn->error . "\n";
    echo "Make sure the users table exists and the contact_number column doesn't already exist.\n";
}

$conn->close();
