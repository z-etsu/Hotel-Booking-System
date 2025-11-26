-- Fix: Update bookings table status ENUM to include 'active' status

ALTER TABLE bookings MODIFY COLUMN status ENUM('active', 'assigned', 'finished', 'cancelled') DEFAULT 'active';

-- Verify the change
DESCRIBE bookings;
