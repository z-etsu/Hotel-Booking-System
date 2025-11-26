-- Update bookings table schema for review system
-- This migration adds support for 'assigned' and 'finished' statuses and review tracking

-- Step 1: Modify the status ENUM to include new values
ALTER TABLE bookings MODIFY COLUMN status ENUM('active', 'assigned', 'finished', 'cancelled') DEFAULT 'active';

-- Step 2: Add has_review column to track if booking has been reviewed
ALTER TABLE bookings ADD COLUMN has_review TINYINT(1) DEFAULT 0 AFTER status;

-- Step 3: Update any existing 'cancelled' bookings to ensure they're consistent
-- (This shouldn't affect 'active' bookings)

-- Verification queries (optional):
-- DESCRIBE bookings;
-- SELECT id, room_name, status, has_review FROM bookings LIMIT 10;
