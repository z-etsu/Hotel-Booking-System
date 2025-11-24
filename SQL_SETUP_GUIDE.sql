-- ========================================
-- HOTEL BOOKING SYSTEM - DATABASE SETUP
-- ========================================
-- Run this script in phpMyAdmin or MySQL CLI
-- to initialize the bookings functionality
-- ========================================

-- Step 1: Create/Verify Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_initial CHAR(1),
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 2: Create Bookings Table (NEW - Required for bookings feature)
CREATE TABLE IF NOT EXISTS bookings (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_check_in (check_in)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- VERIFICATION QUERIES
-- ========================================
-- Run these queries to verify the tables were created correctly

-- Check users table structure
-- DESCRIBE users;

-- Check bookings table structure
-- DESCRIBE bookings;

-- View all bookings (useful for testing)
-- SELECT * FROM bookings;

-- View bookings for a specific user (replace 1 with user_id)
-- SELECT * FROM bookings WHERE user_id = 1 ORDER BY order_date DESC;

-- Count active and cancelled bookings
-- SELECT status, COUNT(*) as count FROM bookings GROUP BY status;

-- ========================================
-- SAMPLE TEST DATA (Optional - for testing)
-- ========================================
-- Uncomment and run if you want to add test data

/*
-- Insert a test user (password: Test@12345)
INSERT INTO users (first_name, middle_initial, last_name, email, password_hash, birthday) 
VALUES ('John', 'D', 'Doe', 'john@example.com', '$2y$10$slYQmyNdGzin7olVN3/p2OPST9/PgBkqquzi.Gy3eF7sVeUU.2Z92', '1990-01-15');

-- Insert test bookings for the user
INSERT INTO bookings (user_id, room_name, check_in, check_out, price_per_night, number_of_nights, total_price, number_of_guests, status)
VALUES 
(1, 'Executive Suite', '2025-12-01', '2025-12-05', 399.00, 4, 1596.00, 2, 'active'),
(1, 'Double Room', '2025-11-25', '2025-11-28', 129.00, 3, 387.00, 2, 'cancelled');
*/

-- ========================================
-- IMPORTANT NOTES
-- ========================================
-- 1. Make sure the 'hotel' database exists before running this script
-- 2. Use 'hotel' as your database name (or update in db_connect.php)
-- 3. Status can only be 'active' or 'cancelled'
-- 4. Check-in and check-out dates should be in YYYY-MM-DD format
-- 5. All prices are stored as DECIMAL for accuracy
-- 6. The bookings table has proper foreign key to users table
-- 7. Cascade delete ensures bookings are deleted when user is deleted
-- 8. Indexes are included for optimal query performance
-- ========================================
