<?php
session_start();
require_once 'db_connect.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated. Please log in first.'
    ]);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

// Retrieve and validate booking ID
$booking_id = intval($_POST['booking_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($booking_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid booking ID.'
    ]);
    exit;
}

// First, verify that the booking belongs to the current user and is active
$stmt = $conn->prepare("SELECT id, status FROM bookings WHERE id = ? AND user_id = ?");

if ($stmt === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Booking not found or does not belong to you.'
    ]);
    $stmt->close();
    exit;
}

$booking = $result->fetch_assoc();
$stmt->close();

// Check if already cancelled
if ($booking['status'] === 'cancelled') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'This booking is already cancelled.'
    ]);
    exit;
}

// Update booking status to cancelled
$updateStmt = $conn->prepare("UPDATE bookings SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ?");

if ($updateStmt === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

$updateStmt->bind_param("i", $booking_id);

if ($updateStmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Booking cancelled successfully.',
        'booking_id' => $booking_id,
        'new_status' => 'cancelled'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to cancel booking: ' . $updateStmt->error
    ]);
}

$updateStmt->close();
$conn->close();
?>
