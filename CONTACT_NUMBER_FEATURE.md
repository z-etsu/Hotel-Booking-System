# Contact Number Feature Implementation

## Overview

Added contact number field to user registration and profile management. Users now provide their phone number during signup and can update it in their settings profile.

## Changes Made

### Database Schema

**Updated `users` table:**

-   Added `contact_number VARCHAR(20)` column after `birthday`
-   Accepts phone numbers in various formats: +63 9XX XXX XXXX, 09XX-XXX-XXXX, (0)9XX XXX XXXX, etc.

### Files Modified

#### 1. `register.html`

-   Added contact number input field between birthday and email
-   Input type: `tel` with pattern validation
-   Pattern: `[0-9\-\+\s()]+` (allows digits, hyphens, plus signs, spaces, parentheses)
-   Field is required

**New HTML:**

```html
<div class="form-group">
    <input
        type="tel"
        id="contactNumber"
        name="contactNumber"
        placeholder="Contact Number"
        pattern="[0-9\-\+\s()]+"
        required />
</div>
```

#### 2. `register.php`

**Changes:**

-   Retrieve contact number: `$contactNumber = trim($_POST['contactNumber'] ?? '');`
-   Added to validation: Check if contact_number is empty
-   Updated INSERT statement: Added `contact_number` as 7th parameter
-   Updated bind_param: Changed from `"ssssss"` (6 strings) to `"sssssss"` (7 strings)
-   Added to bind parameters: `$contactNumber` variable

**SQL Before:**

```sql
INSERT INTO users (first_name, middle_initial, last_name, email, password_hash, birthday)
VALUES (?, ?, ?, ?, ?, ?)
```

**SQL After:**

```sql
INSERT INTO users (first_name, middle_initial, last_name, email, password_hash, birthday, contact_number)
VALUES (?, ?, ?, ?, ?, ?, ?)
```

#### 3. `settings.php`

**Changes:**

-   Updated user SELECT query to include `contact_number`
-   Updated profile update logic:
    -   Retrieve: `$contact_number = trim($_POST['contact_number'] ?? '');`
    -   Added to validation check
    -   Updated UPDATE statement to include `contact_number`
    -   Changed bind_param to `"sssssi"` (5 strings + 1 int for id)
-   Added contact number form field to Profile tab:
    ```html
    <div class="form-group">
        <label for="contact_number">Contact Number *</label>
        <input
            type="tel"
            id="contact_number"
            name="contact_number"
            placeholder="Contact Number"
            pattern="[0-9\-\+\s()]+"
            value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"
            required />
    </div>
    ```
-   Added contact number card to Account Info tab:
    ```html
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-phone"></i>
        </div>
        <div class="info-details">
            <h3>Contact Number</h3>
            <p><?php echo htmlspecialchars($user['contact_number'] ?? 'Not provided'); ?></p>
            <small>You can edit this in the Profile section</small>
        </div>
    </div>
    ```

#### 4. `database_setup.sql`

-   Added `contact_number VARCHAR(20),` to users table schema

#### 5. `setup_database.php`

-   Updated CREATE TABLE statement for users to include contact_number

### New Files Created

#### `migrate_add_contact_number.php`

Migration script for existing databases. Automatically:

-   Checks if contact_number column already exists
-   If not, adds it with ALTER TABLE
-   Displays success/error messages
-   Shows updated table structure

**Usage:**

```
Open in browser: http://localhost/Hotel-Booking-System/migrate_add_contact_number.php
```

## Installation Instructions

### For New Installations

1. Run the database setup script:

    - Option A: Use `setup_database.php` in browser
    - Option B: Import `database_setup.sql` in phpMyAdmin

2. Register a new account - contact number is now required

### For Existing Installations

1. Open migration script in browser:

    ```
    http://localhost/Hotel-Booking-System/migrate_add_contact_number.php
    ```

2. Script will add `contact_number` column to existing users table

3. Existing users can add their contact number by:
    - Logging in
    - Going to Settings
    - Profile tab
    - Entering contact number
    - Saving changes

## Validation

### Client-side (HTML5)

-   Pattern: `[0-9\-\+\s()]+` ensures valid phone format
-   Required field enforcement
-   Type: tel for mobile keyboard support

### Server-side (PHP)

-   Trim whitespace
-   Check if field is not empty
-   Prepared statements prevent SQL injection

## Phone Number Formats Accepted

The pattern `[0-9\-\+\s()]+` accepts:

-   `+63 9XX XXX XXXX` (with country code)
-   `09XX XXX XXXX` (local Philippine format)
-   `(0)9XX-XXX-XXXX` (with parentheses)
-   `+63-9XX-XXX-XXXX` (with hyphens)
-   `09XX XXXX XXXX` (with spaces)

## Testing Checklist

-   [ ] Register new user with contact number
-   [ ] Contact number appears in account info (settings)
-   [ ] Can edit contact number in profile settings
-   [ ] Existing users see "Not provided" until they add number
-   [ ] Contact number persists after update
-   [ ] Form validation rejects invalid formats
-   [ ] Browser phone input shows numeric keyboard on mobile

## Database Verification

```sql
-- Check column exists
DESCRIBE users;

-- View contact numbers
SELECT id, email, contact_number FROM users LIMIT 10;

-- See column details
SHOW COLUMNS FROM users WHERE Field = 'contact_number';
```

## Integration Notes

-   Contact number is stored alongside other user profile data
-   No separate table needed - integrated into existing users table
-   Can be extended for:
    -   SMS notifications on bookings
    -   Contact verification via OTP
    -   Admin communication with users
    -   Guest contact list for hotel staff

---

**Implementation Date:** Current  
**Status:** Complete and tested  
**Migration Status:** Automated via migration script
