-- Add room_number column to bookings table if it doesn't exist
ALTER TABLE bookings ADD COLUMN room_number VARCHAR(10) DEFAULT NULL AFTER room_name;
