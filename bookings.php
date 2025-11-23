<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's bookings from database
$stmt = $conn->prepare("
    SELECT id, room_name, check_in, check_out, price_per_night, number_of_nights, total_price, number_of_guests, order_date, status
    FROM bookings
    WHERE user_id = ?
    ORDER BY order_date DESC
");

if ($stmt === false) {
    die('Database error: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Hotel Name</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bookings.css">
    <script src="bookings.js" defer></script>
</head>
<body class="page-transition bookings-page">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <main class="bookings-main-container">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb" style="margin-top:18px;">
                <a href="index.php">Home</a>
                <span aria-hidden="true">›</span>
                <span class="current">My Bookings</span>
            </nav>

            <!-- Page Header -->
            <header class="bookings-header" style="margin: 30px 0 40px;">
                <h1>My Bookings</h1>
                <p class="bookings-subtitle">View and manage your hotel reservations</p>
            </header>

            <!-- Bookings Container -->
            <div class="bookings-container">
                <?php if (empty($bookings)): ?>
                    <!-- Empty State -->
                    <div class="bookings-empty-state">
                        <div class="empty-icon">🏨</div>
                        <h2>No Bookings Yet</h2>
                        <p>You haven't made any reservations yet. Start exploring our rooms and book your perfect stay!</p>
                        <a href="rooms.php" class="btn btn-primary">Browse Rooms</a>
                    </div>
                <?php else: ?>
                    <!-- Bookings List -->
                    <div class="bookings-list">
                        <?php foreach ($bookings as $booking): ?>
                            <div class="booking-card" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-status="<?php echo htmlspecialchars($booking['status']); ?>">
                                <!-- Card Header with Status Badge -->
                                <div class="booking-card-header">
                                    <div class="booking-title-section">
                                        <h3 class="booking-room-name"><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                                        <span class="booking-order-id">Order #<?php echo htmlspecialchars($booking['id']); ?></span>
                                    </div>
                                    <div class="booking-status-badge" data-status="<?php echo htmlspecialchars($booking['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                    </div>
                                </div>

                                <!-- Card Content - Two Column Layout -->
                                <div class="booking-card-content">
                                    <!-- Left Column: Dates and Guests -->
                                    <div class="booking-details-left">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📅 Check-in</span>
                                            <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📅 Check-out</span>
                                            <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">👥 Guests</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($booking['number_of_guests']); ?> <?php echo $booking['number_of_guests'] == 1 ? 'Guest' : 'Guests'; ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">🌙 Nights</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($booking['number_of_nights']); ?> <?php echo $booking['number_of_nights'] == 1 ? 'Night' : 'Nights'; ?></span>
                                        </div>
                                    </div>

                                    <!-- Right Column: Price and Date -->
                                    <div class="booking-details-right">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💰 Price per Night</span>
                                            <span class="detail-value">${<?php echo number_format($booking['price_per_night'], 2); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💵 Total Price</span>
                                            <span class="detail-value-total">${<?php echo number_format($booking['total_price'], 2); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📝 Booking Date</span>
                                            <span class="detail-value"><?php echo date('M d, Y \a\t H:i', strtotime($booking['order_date'])); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer: Actions -->
                                <div class="booking-card-footer">
                                    <?php if ($booking['status'] === 'active'): ?>
                                        <button class="btn btn-danger cancel-booking-btn" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-room-name="<?php echo htmlspecialchars($booking['room_name']); ?>">
                                            Cancel Booking
                                        </button>
                                    <?php else: ?>
                                        <div class="booking-cancelled-note">
                                            This booking has been cancelled.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Cancel Confirmation Modal -->
    <div class="modal" id="cancelConfirmModal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Cancel Booking</h2>
            <p>Are you sure you want to cancel your booking for <strong id="cancelRoomName"></strong>?</p>
            <p class="modal-warning">⚠️ This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelModalCancelBtn">Keep Booking</button>
                <button class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Booking</button>
            </div>
        </div>
    </div>

    <!-- Cancellation Success Message -->
    <style>
        .cancellation-success {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #d4edda;
            color: #155724;
            padding: 15px 30px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            z-index: 2000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-in-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>

    <footer class="site-footer" aria-hidden="true" style="padding:40px 0; text-align:center; color:#666; margin-top:60px;">
        © <span id="currentYear"></span> Hotel Name. All rights reserved.
    </footer>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
<?php $conn->close(); ?>
