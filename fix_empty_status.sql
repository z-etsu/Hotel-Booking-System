-- Fix empty status values in bookings table
-- Set empty status values to 'active' (pending confirmation from admin)

UPDATE bookings SET status = 'active' WHERE status IS NULL OR status = '';

-- Verify the fix
SELECT id, room_name, status FROM bookings LIMIT 10;
