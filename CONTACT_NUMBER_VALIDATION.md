# Contact Number Validation Guide

## Philippine Phone Number Formats Accepted

The system now validates Philippine phone numbers strictly. Two formats are supported:

### ✅ Valid Formats:

1. **International Format**: `+63 912 345 6789` (spaces required)

    - Pattern: `+63 ` (country code) + `[3 digits]` + space + `[3 digits]` + space + `[4 digits]`
    - Examples:
        - `+63 912 345 6789`
        - `+63 917 123 4567`
        - `+63 921 987 6543`

2. **Local Format**: `09123456789` (no spaces)
    - Pattern: `09` (leading zeros) + `[9 digits]`
    - Examples:
        - `09123456789`
        - `09217654321`
        - `09999999999`

### ❌ Invalid Formats (will be rejected):

-   `12323213` ✗ (too short, no leading 09 or +63)
-   `123 456 7890` ✗ (no country code or leading 09)
-   `+63 123 456 7890` ✗ (wrong area code format)
-   `09 123 456 789` ✗ (spaces in local format)
-   `Letters or symbols` ✗ (non-numeric)
-   `+63 (912) 345-6789` ✗ (parentheses/dashes not supported)

## Implementation Details

### Client-Side Validation (HTML5)

**File**: `register.html`, `settings.php`

-   HTML5 pattern attribute: `(^\+63\s\d{3}\s\d{3}\s\d{4}$|^09\d{9}$)`
-   Error message shows when user tries to submit invalid format
-   Placeholder text shows example: "Contact Number (e.g., +63 912 345 6789)"

### Server-Side Validation (PHP)

**Files**: `register.php`, `settings.php`

-   PHP regex validation: `/^(\+63\s\d{3}\s\d{3}\s\d{4}|09\d{9})$/`
-   Validates on form submission
-   Returns error message if format is invalid
-   Prevents database insertion of invalid numbers

## Test Scenarios

### Registration Form (`register.html`)

1. Try entering `12323213` → Browser blocks with validation error ✗
2. Try entering `09123456789` → Accepted ✓
3. Try entering `+63 912 345 6789` → Accepted ✓
4. Try entering letters → Browser rejects (tel input type) ✗

### Settings Profile Form (`settings.php`)

1. User must be logged in
2. Edit profile with valid number → Saves successfully ✓
3. Edit profile with invalid number → Shows error message ✗
4. Contact number displays in Account Info tab → Shows saved number ✓

## Database

-   **Column**: `users.contact_number`
-   **Type**: `VARCHAR(20)`
-   **Requirement**: NOT NULL (must provide during registration)
-   **Storage**: Both formats stored as-is (e.g., "+63 912 345 6789" or "09123456789")

## User Feedback

-   If validation fails on form submission, user sees error message
-   Browser shows title/tooltip: "Please enter a valid Philippine phone number (e.g., +63 912 345 6789 or 09123456789)"
-   Form prevents submission until valid number is entered
-   Server-side validation provides additional security

## Migration Note

If you're upgrading from the previous version:

1. Run `migrate_add_contact_number.php` to add the column
2. Existing users won't have contact numbers (NULL)
3. On next profile edit, they must enter valid contact number
4. New registrations require contact number
