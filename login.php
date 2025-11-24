<?php
session_start(); // Start the session to store user login status
require_once 'db_connect.php'; // Include the database connection file

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize and retrieve form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // Password is not trimmed
    
    // Basic Validation
    if (empty($email) || empty($password)) {
        // Set error message in session
        $_SESSION['message'] = 'Please enter both email and password.';
        $_SESSION['message_type'] = 'error';
        header("Location: login-page.php");
        exit;
    }

    // 2. Prepare the SQL statement to fetch the user by email
    $stmt = $conn->prepare("SELECT id, first_name, password_hash FROM users WHERE email = ?");
    
    // Check if preparation was successful
    if ($stmt === false) {
        // Set error message and redirect
        $_SESSION['message'] = 'A server error occurred during login.';
        $_SESSION['message_type'] = 'error';
        header("Location: login-page.php");
        exit;
    }

    // Bind parameter: 's' for string
    $stmt->bind_param("s", $email);

    // 3. Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User found
        $user = $result->fetch_assoc();
        
    // 4. Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct! Set session variables.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['user_email'] = $email;
            
            // Set success message in session before redirecting to the success page
            $_SESSION['message'] = 'Login successful! Welcome, ' . htmlspecialchars($user['first_name']) . '.';
            $_SESSION['message_type'] = 'success';
            
            // Redirect to the login success page
            header("Location: login-success.php"); 
            exit();
        } else {
            // Incorrect password
            $_SESSION['message'] = 'Invalid email or password.';
            $_SESSION['message_type'] = 'error';
            header("Location: login-page.php");
            exit;
        }
    } else {
        // User not found
        $_SESSION['message'] = 'Invalid email or password.';
        $_SESSION['message_type'] = 'error';
        header("Location: login-page.php");
        exit;
    }

    // 5. Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If someone tries to access login.php directly without POST
    header("Location: login-page.php");
    exit;
}
?>