<?php
session_start();

// Destroy the admin session
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_name']);

// Clear all session data
session_destroy();

// Redirect to admin login page
header("Location: login.php");
exit;
