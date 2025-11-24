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

// Retrieve and validate input data
$room_name = trim($_POST['room_name'] ?? '');
$check_in = trim($_POST['check_in'] ?? '');
$check_out = trim($_POST['check_out'] ?? '');
$price_per_night = floatval($_POST['price_per_night'] ?? 0);
$number_of_nights = intval($_POST['number_of_nights'] ?? 0);
$total_price = floatval($_POST['total_price'] ?? 0);
$number_of_guests = intval($_POST['number_of_guests'] ?? 0);

// Validate required fields
if (empty($room_name) || empty($check_in) || empty($check_out) || $price_per_night <= 0 || $number_of_nights <= 0 || $total_price <= 0 || $number_of_guests <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required and must contain valid values.'
    ]);
    exit;
}

// Validate date format (YYYY-MM-DD)
$check_in_date = DateTime::createFromFormat('Y-m-d', $check_in);
$check_out_date = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$check_in_date || !$check_out_date) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid date format. Please use YYYY-MM-DD.'
    ]);
    exit;
}

// Validate dates logic
if ($check_out_date <= $check_in_date) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Check-out date must be after check-in date.'
    ]);
    exit;
}

// Validate check-in is not in the past
$today = new DateTime();
$today->setTime(0, 0, 0);
if ($check_in_date < $today) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Check-in date cannot be in the past.'
    ]);
    exit;
}

// Re-verify the calculation of total price (security check)
$calculated_total = $price_per_night * $number_of_nights;
if (abs($calculated_total - $total_price) > 0.01) { // Allow for minor floating point differences
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Price calculation mismatch. Please try again.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Prepare SQL statement to insert booking
$stmt = $conn->prepare("
    INSERT INTO bookings 
    (user_id, room_name, check_in, check_out, price_per_night, number_of_nights, total_price, number_of_guests, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')
");

if ($stmt === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

// Bind parameters: i = integer, s = string, d = double
$stmt->bind_param("isssddii", $user_id, $room_name, $check_in, $check_out, $price_per_night, $number_of_nights, $total_price, $number_of_guests);

// Execute the statement
if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Booking confirmed successfully!',
        'order_id' => $order_id,
        'booking_data' => [
            'room_name' => $room_name,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'total_price' => $total_price,
            'number_of_guests' => $number_of_guests
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create booking: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
