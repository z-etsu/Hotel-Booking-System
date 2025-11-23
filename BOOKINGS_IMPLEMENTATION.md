# Hotel Booking System - Bookings Feature Implementation

## Overview
This document outlines the complete implementation of the User Bookings feature for the Hotel Booking System. Users can now book rooms, view all their bookings, and cancel bookings with real-time status updates.

## Features Implemented

### 1. **Booking Creation**
- Users can book a room by selecting check-in/check-out dates and number of guests
- System validates dates, capacity, and calculates total price
- Booking is saved to database with all details

### 2. **Bookings Management Page**
- Display all user bookings in an attractive card-based layout
- Shows: Room name, check-in date, check-out date, number of guests, price per night, total price, number of nights, booking date, and Order ID
- Empty state when no bookings exist with link to browse rooms
- Responsive design for mobile and desktop

### 3. **Booking Status System**
- **Active**: Current bookings displayed with cancel button
- **Cancelled**: Cancelled bookings displayed with greyed out styling and status indicator
- Cancelled bookings remain in the list (not deleted) for historical reference

### 4. **Cancellation Feature**
- Users can cancel active bookings with a confirmation dialog
- Modal confirms cancellation details before processing
- Upon confirmation, booking status updates to "cancelled" in database
- UI immediately reflects the change without page reload
- Success notification shown to user

---

## Database Schema

### Users Table (Existing)
```sql
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
```

### Bookings Table (NEW)
```sql
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
```

---

## Files Created/Modified

### New Files

1. **database_setup.sql**
   - SQL script containing the bookings table schema
   - Run this in phpMyAdmin or MySQL command line

2. **bookings.php**
   - Main page displaying user's bookings
   - Fetches bookings from database for logged-in user
   - Shows empty state when no bookings
   - Requires user to be logged in

3. **bookings.css**
   - Polished styling for bookings page
   - Card-based layout with status badges
   - Modal styling for cancellation confirmation
   - Responsive design (mobile-first approach)
   - Animations for smooth UX

4. **bookings.js**
   - Handles cancel button clicks
   - Opens/closes confirmation modal
   - Sends cancellation request to backend
   - Updates UI in real-time upon successful cancellation
   - Shows success notifications

5. **process_booking.php**
   - Backend endpoint for creating bookings
   - Validates all input data
   - Performs security checks (user authentication, date validation, price verification)
   - Inserts booking into database
   - Returns JSON response with order ID and booking details

6. **process_cancel_booking.php**
   - Backend endpoint for cancelling bookings
   - Verifies booking ownership
   - Checks if already cancelled
   - Updates database status to 'cancelled'
   - Returns JSON response with success/error

### Modified Files

1. **booking.js**
   - Updated "Reserve & Pay" button to submit real booking data
   - Changed from simulation to actual form submission via fetch()
   - Sends POST request to process_booking.php
   - Redirects to bookings.php on success
   - Shows order ID in confirmation alert

2. **login.php**
   - Added `$_SESSION['user_email']` to session variables
   - Needed for checking user login status in booking.php

3. **navbar.php** (no changes needed)
   - Already contains the "Bookings" link in user dropdown
   - Link already points to bookings.php

---

## Setup Instructions

### Step 1: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select your 'hotel' database
3. Go to the "SQL" tab
4. Copy the content from `database_setup.sql`
5. Paste and execute the SQL query
6. Alternatively, import the database_setup.sql file directly

### Step 2: File Verification
Ensure all files are in the correct location:
- `/SDLC FINALS/bookings.php`
- `/SDLC FINALS/bookings.css`
- `/SDLC FINALS/bookings.js`
- `/SDLC FINALS/process_booking.php`
- `/SDLC FINALS/process_cancel_booking.php`
- `/SDLC FINALS/database_setup.sql`

### Step 3: Test the Feature

#### Testing Booking Creation:
1. Go to http://localhost/SDLC%20FINALS/ (or your local URL)
2. Click "Rooms" to browse rooms
3. Click "Book Now from $[price]" on any room
4. Select check-in and check-out dates
5. Select number of guests
6. Click "Reserve & Pay"
7. Confirm the booking in the dialog
8. Should see success message with Order ID
9. You'll be redirected to bookings.php

#### Testing Bookings Page:
1. You should see your newly created booking displayed as a card
2. Card should show all details: room name, dates, guests, price, order ID
3. Status badge should show "ACTIVE" with green styling
4. Cancel button should be visible

#### Testing Cancellation:
1. On bookings page, click "Cancel Booking" button
2. Confirmation modal appears asking to confirm cancellation
3. Click "Yes, Cancel Booking"
4. Modal closes and booking card updates
5. Status badge changes to "CANCELLED" with red styling
6. Cancel button is replaced with "This booking has been cancelled" message
7. Success notification appears

---

## Technical Details

### Security Measures
- User authentication check on all booking endpoints
- Prepared statements to prevent SQL injection
- Floating-point tolerance check for price calculation
- Date validation (check-out > check-in, no past dates)
- Booking ownership verification (users can only cancel their own bookings)
- Already cancelled bookings cannot be cancelled again

### Data Validation
- **Dates**: Must be in YYYY-MM-DD format, check-out must be after check-in
- **Price**: Recalculated server-side to prevent client-side manipulation
- **Guests**: Validated against room capacity
- **Booking ID**: Must be positive integer and belong to current user

### UI/UX Features
- Smooth animations and transitions
- Real-time updates without page reload
- Clear visual status indicators
- Responsive design for all screen sizes
- Empty state with helpful CTA
- Success notifications with auto-dismiss
- Loading states for async operations
- Modal confirmations for destructive actions

### Database Indexes
- `idx_user_id`: Speeds up queries by user
- `idx_status`: Speeds up queries filtering by status
- `idx_check_in`: Speeds up date range queries

---

## Future Enhancements

1. **Email Notifications**
   - Send confirmation email when booking is created
   - Send cancellation confirmation email

2. **Booking Modifications**
   - Allow users to modify check-in/check-out dates
   - Change number of guests (if allowed by policy)

3. **Advanced Filtering**
   - Filter bookings by status (active/cancelled)
   - Date range filter
   - Search by room name

4. **Admin Dashboard**
   - View all bookings across users
   - Manage bookings from admin panel
   - View cancellation statistics

5. **Payment Integration**
   - Integrate with payment gateway (Stripe, PayPal)
   - Process refunds for cancelled bookings
   - Track payment status

6. **Reviews & Ratings**
   - Allow users to rate rooms after checkout
   - Display reviews on room pages

---

## Troubleshooting

### Issue: "User not authenticated" error
**Solution**: Make sure the user is logged in before attempting to book. Check that `$_SESSION['user_id']` is properly set.

### Issue: Bookings not appearing on bookings.php
**Solution**: 
1. Check database connection in db_connect.php
2. Verify bookings table was created correctly
3. Check browser console for any JavaScript errors
4. Ensure user_id is correctly set in session

### Issue: Cancellation button not working
**Solution**:
1. Check browser console for errors
2. Verify process_cancel_booking.php exists
3. Check database permissions
4. Ensure booking belongs to logged-in user

### Issue: Page not redirecting after booking
**Solution**: Check that `$_SESSION['user_id']` is set in booking.js check. Navigate manually to bookings.php if needed.

---

## File Structure Summary

```
SDLC FINALS/
├── bookings.php                    (NEW - Bookings list page)
├── bookings.css                    (NEW - Bookings styling)
├── bookings.js                     (NEW - Bookings JavaScript)
├── process_booking.php             (NEW - Booking creation backend)
├── process_cancel_booking.php      (NEW - Booking cancellation backend)
├── database_setup.sql              (NEW - Database schema)
├── booking.php                     (MODIFIED - Updated form submission)
├── booking.js                      (MODIFIED - Real booking submission)
├── login.php                       (MODIFIED - Added user_email to session)
├── navbar.php                      (NO CHANGES - Already has bookings link)
├── db_connect.php                  (NO CHANGES)
├── rooms.php                       (NO CHANGES)
├── rooms.js                        (NO CHANGES)
├── style.css                       (NO CHANGES)
├── rooms.css                       (NO CHANGES)
└── ... other files
```

---

## Testing Checklist

- [ ] Database bookings table created successfully
- [ ] User can navigate to booking.php (room detail page)
- [ ] User can select dates and guests
- [ ] "Reserve & Pay" button submits booking
- [ ] Order ID is displayed in confirmation
- [ ] User is redirected to bookings.php
- [ ] Booking appears in bookings list with correct details
- [ ] Status badge shows "ACTIVE" for new bookings
- [ ] Cancel button is visible and clickable
- [ ] Cancellation modal appears with correct room name
- [ ] Confirming cancellation updates database
- [ ] Cancelled booking status updates in UI
- [ ] Status badge changes to "CANCELLED"
- [ ] Success notification appears
- [ ] Cancelled bookings remain in list (not deleted)
- [ ] Empty state appears when no bookings
- [ ] Page is responsive on mobile

---

## Performance Notes

- Database queries use indexes for fast lookups
- Prepared statements prevent SQL injection
- Minimal data transfer (JSON responses)
- CSS animations use GPU acceleration
- JavaScript is optimized and modular

---

## Support

For issues or questions, check:
1. Browser console for JavaScript errors (F12)
2. Server logs for PHP errors
3. Database integrity with phpMyAdmin
4. User session status in browser DevTools

---

**Status**: ✅ Complete and Ready to Use
**Last Updated**: November 23, 2025
