<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Debug logging
error_log("=== BOOKINGS PAGE DEBUG ===");
error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NOT SET'));
error_log("User ID: " . $user_id);

// First, check if has_review column exists
$checkColumnSQL = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='bookings' AND COLUMN_NAME='has_review'";
$columnResult = $conn->query($checkColumnSQL);
$hasReviewColumnExists = $columnResult && $columnResult->num_rows > 0;

// Build the query dynamically based on whether column exists
if ($hasReviewColumnExists) {
    $selectSQL = "
        SELECT id, room_name, check_in, check_out, price_per_night, number_of_nights, total_price, number_of_guests, order_date, status, has_review
        FROM bookings
        WHERE user_id = ?
        ORDER BY order_date DESC
    ";
} else {
    $selectSQL = "
        SELECT id, room_name, check_in, check_out, price_per_night, number_of_nights, total_price, number_of_guests, order_date, status, 0 as has_review
        FROM bookings
        WHERE user_id = ?
        ORDER BY order_date DESC
    ";
}

// Fetch user's bookings from database
$stmt = $conn->prepare($selectSQL);

if ($stmt === false) {
    die('Database error: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die('Query execution error: ' . $stmt->error);
}

$result = $stmt->get_result();

// Debug: Log the number of results
error_log("User ID: " . $user_id . ", Bookings found: " . $result->num_rows);

$bookings = [];
$active_bookings = [];
$assigned_bookings = [];
$finished_bookings = [];
$cancelled_bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
    
    // If status is empty, default to 'active'
    $status = trim($row['status'] ?? '');
    if (empty($status)) {
        $status = 'active';
    }
    
    // Debug: Log the status value
    if (isset($_GET['debug'])) {
        error_log("Booking ID: " . $row['id'] . ", Original Status: [" . ($row['status'] ?? 'NULL') . "], Final Status: [" . $status . "]");
    }
    
    // Organize by status
    if ($status === 'active') {
        $active_bookings[] = $row;
    } elseif ($status === 'assigned') {
        $assigned_bookings[] = $row;
    } elseif ($status === 'finished') {
        $finished_bookings[] = $row;
    } elseif ($status === 'cancelled') {
        $cancelled_bookings[] = $row;
    } else {
        // Status doesn't match any category - default to active
        if (isset($_GET['debug'])) {
            error_log("WARNING: Status [" . $status . "] does not match any category, defaulting to active");
        }
        $active_bookings[] = $row;
    }
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Elegante</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bookings.css" />
    <script src="bookings.js" defer></script>
    <script src="script.js?v=<?php echo time(); ?>"></script>
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
                <?php if (isset($_GET['debug'])): ?>
                    <p style="background: #fff3cd; padding: 10px; border-radius: 4px; margin-top: 10px; font-size: 12px;">
                        <strong>DEBUG INFO:</strong> User ID: <?php echo $user_id; ?>, 
                        Bookings Found: <?php echo count($bookings); ?>, 
                        Has Review Column: <?php echo ($hasReviewColumnExists ? 'YES' : 'NO'); ?>
                    </p>
                <?php endif; ?>
            </header>

            <!-- Bookings Container -->
            <div class="bookings-container">
                <?php 
                // Debug output - ALWAYS show for ?debug=1
                if (isset($_GET['debug'])): ?>
                    <div style="background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px; border-radius: 4px;">
                        <strong>DEBUG INFO:</strong><br>
                        Total Bookings Array Count: <?php echo count($bookings); ?><br>
                        Active: <?php echo count($active_bookings); ?> | 
                        Assigned: <?php echo count($assigned_bookings); ?> | 
                        Finished: <?php echo count($finished_bookings); ?> | 
                        Cancelled: <?php echo count($cancelled_bookings); ?><br>
                        Has Review Column: <?php echo ($hasReviewColumnExists ? 'YES' : 'NO'); ?><br>
                        Empty Check Result: <?php echo (empty($bookings) ? 'TRUE (empty)' : 'FALSE (has data)'); ?><br>
                        <hr style="margin: 10px 0;">
                        <strong>Actual Statuses in Database:</strong><br>
                        <?php 
                        $statusCounts = [];
                        foreach ($bookings as $b) {
                            $status = $b['status'] ?? 'NULL';
                            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
                        }
                        foreach ($statusCounts as $status => $count) {
                            echo "'" . htmlspecialchars($status) . "': " . $count . " bookings<br>";
                        }
                        ?>
                    </div>
                <?php endif; ?>
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
                    <!-- ACTIVE BOOKINGS SECTION -->
                    <?php if (!empty($active_bookings)): ?>
                    <div class="bookings-list bookings-section">
                        <div class="section-header">
                            <h2>🏨 Active Bookings</h2>
                            <span class="section-count"><?php echo count($active_bookings); ?></span>
                        </div>
                        <?php foreach ($active_bookings as $booking): ?>
                            <div class="booking-card" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-status="active">
                                <div class="booking-card-header">
                                    <div class="booking-title-section">
                                        <h3 class="booking-room-name"><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                                        <span class="booking-order-id">Order #<?php echo htmlspecialchars($booking['id']); ?></span>
                                    </div>
                                    <span class="status-active">Active</span>
                                </div>
                                <div class="booking-card-content">
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
                                    <div class="booking-details-right">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💰 Price per Night</span>
                                            <span class="detail-value">₱<?php echo number_format($booking['price_per_night'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💵 Total Price</span>
                                            <span class="detail-value-total">₱<?php echo number_format($booking['total_price'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📝 Booking Date</span>
                                            <span class="detail-value"><?php echo date('M d, Y \a\t H:i', strtotime($booking['order_date'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="booking-card-footer">
                                    <button class="btn btn-danger cancel-booking-btn" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-room-name="<?php echo htmlspecialchars($booking['room_name']); ?>">
                                        Cancel Booking
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- ASSIGNED BOOKINGS SECTION -->
                    <?php if (!empty($assigned_bookings)): ?>
                    <div class="bookings-list bookings-section">
                        <div class="section-header">
                            <h2>🔑 Assigned Bookings</h2>
                            <span class="section-count"><?php echo count($assigned_bookings); ?></span>
                        </div>
                        <?php foreach ($assigned_bookings as $booking): ?>
                            <div class="booking-card" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-status="assigned">
                                <div class="booking-card-header">
                                    <div class="booking-title-section">
                                        <h3 class="booking-room-name"><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                                        <span class="booking-order-id">Order #<?php echo htmlspecialchars($booking['id']); ?></span>
                                    </div>
                                    <span class="status-assigned">Assigned</span>
                                </div>
                                <div class="booking-card-content">
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
                                    <div class="booking-details-right">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💰 Price per Night</span>
                                            <span class="detail-value">₱<?php echo number_format($booking['price_per_night'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💵 Total Price</span>
                                            <span class="detail-value-total">₱<?php echo number_format($booking['total_price'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📝 Booking Date</span>
                                            <span class="detail-value"><?php echo date('M d, Y \a\t H:i', strtotime($booking['order_date'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="booking-card-footer">
                                    <button class="btn btn-danger cancel-booking-btn" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-room-name="<?php echo htmlspecialchars($booking['room_name']); ?>">
                                        Cancel Booking
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- FINISHED BOOKINGS SECTION -->
                    <?php if (!empty($finished_bookings)): ?>
                    <div class="bookings-list bookings-section">
                        <div class="section-header">
                            <h2>✅ Finished Bookings</h2>
                            <span class="section-count"><?php echo count($finished_bookings); ?></span>
                        </div>
                        <?php foreach ($finished_bookings as $booking): ?>
                            <div class="booking-card" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-status="finished">
                                <div class="booking-card-header">
                                    <div class="booking-title-section">
                                        <h3 class="booking-room-name"><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                                        <span class="booking-order-id">Order #<?php echo htmlspecialchars($booking['id']); ?></span>
                                    </div>
                                    <span class="status-finished">Finished</span>
                                </div>
                                <div class="booking-card-content">
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
                                    <div class="booking-details-right">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💰 Price per Night</span>
                                            <span class="detail-value">₱<?php echo number_format($booking['price_per_night'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💵 Total Price</span>
                                            <span class="detail-value-total">₱<?php echo number_format($booking['total_price'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📝 Booking Date</span>
                                            <span class="detail-value"><?php echo date('M d, Y \a\t H:i', strtotime($booking['order_date'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="booking-card-footer">
                                    <?php if ($booking['has_review']): ?>
                                        <div class="review-status-badge">✓ Review Submitted</div>
                                    <?php else: ?>
                                        <button class="btn btn-primary rate-review-btn" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-room-name="<?php echo htmlspecialchars($booking['room_name']); ?>">
                                            ⭐ Rate & Review
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- CANCELLED BOOKINGS SECTION -->
                    <?php if (!empty($cancelled_bookings)): ?>
                    <div class="bookings-list bookings-section">
                        <div class="section-header">
                            <h2>❌ Cancelled Bookings</h2>
                            <span class="section-count"><?php echo count($cancelled_bookings); ?></span>
                        </div>
                        <?php foreach ($cancelled_bookings as $booking): ?>
                            <div class="booking-card" data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>" data-status="cancelled">
                                <div class="booking-card-header">
                                    <div class="booking-title-section">
                                        <h3 class="booking-room-name"><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                                        <span class="booking-order-id">Order #<?php echo htmlspecialchars($booking['id']); ?></span>
                                    </div>
                                    <span class="status-cancelled">Cancelled</span>
                                </div>
                                <div class="booking-card-content">
                                    <div class="booking-details-left">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📅 Check-in</span>
                                            <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📅 Check-out</span>
                                            <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="booking-details-right">
                                        <div class="booking-detail-item">
                                            <span class="detail-label">💵 Total Price</span>
                                            <span class="detail-value-total">₱<?php echo number_format($booking['total_price'], 0); ?></span>
                                        </div>
                                        <div class="booking-detail-item">
                                            <span class="detail-label">📝 Booking Date</span>
                                            <span class="detail-value"><?php echo date('M d, Y \a\t H:i', strtotime($booking['order_date'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="booking-card-footer">
                                    <div class="booking-cancelled-note">
                                        This booking has been cancelled.
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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
        © <span id="currentYear"></span> Elegante. All rights reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('currentYear').textContent = new Date().getFullYear();
        });
    </script>

    <!-- Elegant Review Modal -->
    <div id="reviewModal" class="modal elegant-modal">
        <div class="modal-content elegant-modal-content">
            <span class="modal-close" id="closeReviewModal">&times;</span>
            <div class="elegant-modal-header">
                <h2>We Value Your Experience</h2>
                <p class="elegant-modal-subtitle">How was your stay? Please rate and review your room.</p>
            </div>
            <form id="reviewForm">
                <div class="star-rating" id="starRating"></div>
                <textarea id="reviewDescription" name="description" rows="4" placeholder="Share your thoughts..." required class="elegant-textarea"></textarea>
                <input type="hidden" id="reviewBookingId" name="booking_id" value="">
                <div id="reviewFeedback" class="elegant-feedback"></div>
                <div class="elegant-modal-actions">
                    <button type="submit" class="btn btn-primary elegant-btn">Submit Review</button>
                    <button type="button" class="btn btn-secondary elegant-btn-cancel" id="cancelReviewBtn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <style>
    /* Elegant Review Modal Styles */
    .elegant-modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(34, 24, 8, 0.25); align-items: center; justify-content: center;
        font-family: 'Cinzel', 'Georgia', serif;
    }
    .elegant-modal.show { display: flex; }
    .elegant-modal-content {
        background: #fff8f2;
        border-radius: 18px;
        box-shadow: 0 8px 40px rgba(184, 134, 11, 0.18), 0 1.5px 8px #e6e1d8;
        padding: 40px 36px 32px 36px;
        max-width: 420px;
        width: 95vw;
        position: relative;
        border: 1.5px solid #e6e1d8;
        animation: fadeIn 0.4s cubic-bezier(.4,0,.2,1);
    }
    .elegant-modal-header h2 {
        font-size: 1.7rem;
        color: #532200;
        margin-bottom: 0.2em;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .elegant-modal-subtitle {
        color: #b8860b;
        font-size: 1.05rem;
        margin-bottom: 18px;
        font-family: 'Georgia', serif;
    }
    .modal-close {
        position: absolute; right: 18px; top: 18px; font-size: 2rem; color: #b8860b; cursor: pointer;
        transition: color 0.2s;
    }
    .modal-close:hover { color: #532200; }
    .star-rating {
        display: flex; gap: 8px; justify-content: center; margin: 18px 0 10px 0;
    }
    .star-rating .star {
        font-size: 2.2rem; color: #e6e1d8; cursor: pointer; transition: color 0.2s, transform 0.2s;
    }
    .star-rating .star.filled { color: #b8860b; text-shadow: 0 2px 8px #f7e7c1; transform: scale(1.12); }
    .star-rating .star:hover { color: #532200; transform: scale(1.18); }
    .elegant-textarea {
        width: 100%; border-radius: 8px; border: 1.2px solid #e6e1d8; padding: 12px; font-size: 1.05rem;
        background: #fff; color: #532200; margin-bottom: 10px; font-family: 'Georgia', serif;
        transition: border 0.2s;
    }
    .elegant-textarea:focus { border: 1.2px solid #b8860b; outline: none; }
    .elegant-modal-actions {
        display: flex; gap: 12px; margin-top: 10px; justify-content: flex-end;
    }
    .elegant-btn {
        background-color: #b8860b;
        color: #fff8f2; font-weight: 600; border: none; border-radius: 6px; padding: 10px 28px;
        font-size: 1rem; box-shadow: 0 2px 8px rgba(184, 134, 11, 0.3); transition: all 0.2s ease;
        cursor: pointer;
    }
    .elegant-btn:hover { 
        background-color: #9a7009;
        box-shadow: 0 4px 12px rgba(184, 134, 11, 0.4);
        transform: translateY(-1px);
    }
    .elegant-btn-cancel {
        background: #fff8f2; color: #b8860b; border: 1.2px solid #b8860b; font-weight: 500;
        border-radius: 6px; padding: 10px 22px; font-size: 1rem; cursor: pointer; transition: all 0.2s ease;
    }
    .elegant-btn-cancel:hover { background: #f7e7c1; color: #532200; border-color: #532200; }
    .elegant-feedback {
        min-height: 22px; color: #c62828; font-size: 1rem; margin-bottom: 6px; text-align: center;
        font-family: 'Georgia', serif;
    }
    .elegant-feedback.success { color: #166534; }
    .review-info {
        display: flex; align-items: center; gap: 12px; padding: 12px; background: #e8f5e9; 
        border-radius: 6px; border-left: 3px solid #166534;
    }
    .review-stars {
        font-size: 1.3rem; color: #b8860b;
    }
    .already-reviewed {
        color: #166534; font-weight: 500; font-size: 0.95rem;
    }
    .star-counter {
        text-align: center; font-size: 0.95rem; margin-bottom: 12px; font-weight: 500;
        font-family: 'Georgia', serif; letter-spacing: 0.01em;
    }
    /* Section headers for organized bookings */
    .bookings-section {
        margin-bottom: 40px;
    }
    .section-header {
        display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 12px;
        border-bottom: 2px solid #e6e1d8;
    }
    .section-header h2 {
        font-size: 1.4rem; color: #532200; margin: 0; font-weight: 600;
    }
    .section-count {
        background: #b8860b; color: #fff8f2; padding: 4px 12px; border-radius: 20px;
        font-size: 0.85rem; font-weight: 600;
    }
    /* Status badges */
    .status-assigned, .status-finished, .status-cancelled {
        padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
        display: inline-block;
    }
    .status-assigned {
        background: #bbdefb; color: #0d47a1;
    }
    .status-finished {
        background: #c8e6c9; color: #1b5e20;
    }
    .status-cancelled {
        background: #ffcdd2; color: #b71c1c;
    }
    .review-status-badge {
        display: inline-block; background: #c8e6c9; color: #1b5e20; padding: 8px 16px;
        border-radius: 6px; font-weight: 600; font-size: 0.95rem;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
    </style>

    <script src="review.js"></script>
</body>
</html>
<?php $conn->close(); ?>
