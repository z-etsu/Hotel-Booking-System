<?php
session_start(); // We need session_start here too for the error messages
// Include the database connection file
require_once 'db_connect.php';

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize and retrieve form data
    $firstName = trim($_POST['firstName'] ?? '');
    $middleInitial = trim($_POST['middleInitial'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // Do not trim password

    // Basic Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($birthday)) {
        $_SESSION['message'] = 'All required fields must be filled out.';
        $_SESSION['message_type'] = 'error';
        header("Location: register.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format.';
        $_SESSION['message_type'] = 'error';
        header("Location: register.html");
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['message'] = 'Password must be at least 8 characters long.';
        $_SESSION['message_type'] = 'error';
        header("Location: register.html");
        exit;
    }

    // 2. Hash the password
    // IMPORTANT: NEVER store raw passwords. Use password_hash() for security.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Set middle initial to NULL if it's empty
    $middleInitial = $middleInitial === '' ? NULL : $middleInitial;
    
    // 3. Prepare the SQL statement for insertion (using prepared statement for security)
    $stmt = $conn->prepare("INSERT INTO users (first_name, middle_initial, last_name, email, password_hash, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Check if preparation was successful
    if ($stmt === false) {
        $_SESSION['message'] = 'A server error occurred during registration.';
        $_SESSION['message_type'] = 'error';
        header("Location: register.html");
        exit;
    }
    
    // Bind parameters: 'ssssss' means 6 strings (or 's' for strings, 'i' for integers, 'd' for doubles, 'b' for blobs)
    // We treat all inputs as strings here.
    $stmt->bind_param("ssssss", $firstName, $middleInitial, $lastName, $email, $passwordHash, $birthday);

    // 4. Execute the statement
    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['message'] = 'Registration successful! You can now log in.';
        $_SESSION['message_type'] = 'success';
        header("Location: register-success.php");
        exit;
    } else {
        // Check for specific error like duplicate entry (e.g., email UNIQUE constraint)
        if ($conn->errno == 1062) {
             $_SESSION['message'] = 'Error: This email address is already registered.';
             $_SESSION['message_type'] = 'error';
        } else {
            // Other database error
             $_SESSION['message'] = 'Error registering user: ' . $stmt->error;
             $_SESSION['message_type'] = 'error';
        }
        header("Location: register.html");
        exit;
    }

    // 5. Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If someone tries to access register.php directly without POST
    header("Location: register.html");
    exit;
}
?>