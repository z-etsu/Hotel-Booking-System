<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['booking_id'], $input['stars'], $input['description'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$booking_id = intval($input['booking_id']);
$stars = intval($input['stars']);
$description = trim($input['description']);
$user_id = $_SESSION['user_id'];

// Validate stars
if ($stars < 1 || $stars > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid star rating']);
    exit;
}

// Validate description
if (empty($description) || strlen($description) < 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Description must be at least 5 characters']);
    exit;
}

// Check if booking exists and belongs to user and is finished
$checkBooking = $conn->prepare("SELECT room_name FROM bookings WHERE id = ? AND user_id = ? AND status = 'finished'");
if (!$checkBooking) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$checkBooking->bind_param("ii", $booking_id, $user_id);
$checkBooking->execute();
$result = $checkBooking->get_result();

if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Booking not found or not eligible for review']);
    exit;
}

$booking = $result->fetch_assoc();
$room_name = $booking['room_name'];

// Check if review already exists
$checkReview = $conn->prepare("SELECT id FROM reviews WHERE booking_id = ?");
if (!$checkReview) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$checkReview->bind_param("i", $booking_id);
$checkReview->execute();
$reviewResult = $checkReview->get_result();

if ($reviewResult->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'You have already reviewed this booking']);
    exit;
}

// Insert review
$stmt = $conn->prepare("
    INSERT INTO reviews (booking_id, user_id, room_name, stars, description, review_date)
    VALUES (?, ?, ?, ?, ?, NOW())
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("iisis", $booking_id, $user_id, $room_name, $stars, $description);

if ($stmt->execute()) {
    // Update booking status to 'finished' and set has_review flag
    $updateStmt = $conn->prepare("UPDATE bookings SET status = 'finished', has_review = 1 WHERE id = ?");
    if ($updateStmt) {
        $updateStmt->bind_param("i", $booking_id);
        $updateStmt->execute();
        $updateStmt->close();
    }
    echo json_encode(['success' => true, 'message' => 'Thank you for your review!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error submitting review: ' . $stmt->error]);
}

$stmt->close();
$checkBooking->close();
$checkReview->close();
$conn->close();
?>
