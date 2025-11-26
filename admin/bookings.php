<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db_connect.php';

// Handle room assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_room') {
    $bookingId = (int)$_POST['booking_id'];
    $roomNumber = $conn->real_escape_string(trim($_POST['room_number']));
    
    // Update booking with room number AND change status to 'assigned'
    $updateQuery = "UPDATE bookings SET room_number = '" . $roomNumber . "', status = 'assigned' WHERE id = " . $bookingId;
    
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['success_message'] = "Room #" . htmlspecialchars($roomNumber) . " assigned successfully!";
        // Redirect to refresh the page
        header("Location: bookings.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Failed to assign room: " . $conn->error;
    }
}

// Handle marking booking as finished
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_finished') {
    $bookingId = (int)$_POST['booking_id'];
    
    // Update booking status to 'finished'
    $updateQuery = "UPDATE bookings SET status = 'finished' WHERE id = " . $bookingId;
    
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['success_message'] = "Booking marked as finished! User can now review.";
        // Redirect to refresh the page
        header("Location: bookings.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Failed to mark booking as finished: " . $conn->error;
    }
}

// Handle cancellation - only allow cancellation of active bookings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_booking') {
    $bookingId = (int)$_POST['booking_id'];
    
    // Check if booking is in 'active' status (can only cancel active bookings, not assigned ones)
    $checkQuery = "SELECT status FROM bookings WHERE id = " . $bookingId;
    $checkResult = $conn->query($checkQuery);
    $bookingData = $checkResult->fetch_assoc();
    
    // Ensure status is either empty (defaults to active) or explicitly 'active'
    $currentStatus = trim($bookingData['status'] ?? '');
    if (empty($currentStatus)) { $currentStatus = 'active'; }
    
    // Only allow cancellation if status is 'active'
    if ($currentStatus !== 'active') {
        $_SESSION['error_message'] = "Cannot cancel booking - room has already been assigned. Contact support if needed.";
        header("Location: bookings.php");
        exit;
    }
    
    $updateQuery = "UPDATE bookings SET status = 'cancelled' WHERE id = " . $bookingId;
    
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['success_message'] = "Booking cancelled successfully!";
        // Redirect to refresh the page
        header("Location: bookings.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Failed to cancel booking: " . $conn->error;
    }
}

// Room numbering system with prefixes
$roomPrefixes = [
    'Single Room' => 'S',
    'Double Room' => 'D',
    'Twin Room' => 'T',
    'Triple Room' => 'TR',
    'Family Room' => 'F',
    'Connected Room' => 'C',
    'Executive Suite' => 'E',
    'Presidential Suite' => 'P',
    'Royal Suite' => 'R'
];

$roomQuantities = [
    'Single Room' => 12,
    'Double Room' => 15,
    'Twin Room' => 10,
    'Triple Room' => 8,
    'Family Room' => 7,
    'Connected Room' => 5,
    'Executive Suite' => 6,
    'Presidential Suite' => 3,
    'Royal Suite' => 2
];

// Generate available room numbers for each room type
$availableRooms = [];
foreach ($roomQuantities as $roomName => $quantity) {
    $prefix = $roomPrefixes[$roomName];
    $rooms = [];
    for ($i = 1; $i <= $quantity; $i++) {
        $rooms[] = $prefix . str_pad($i, 3, '0', STR_PAD_LEFT); // e.g., S001, S002, etc
    }
    $availableRooms[$roomName] = $rooms;
}

// Get already assigned room numbers (from both assigned and finished bookings)
$assignedRoomsQuery = "SELECT room_number FROM bookings WHERE (status = 'assigned' OR status = 'finished') AND room_number IS NOT NULL";
$assignedRoomsResult = $conn->query($assignedRoomsQuery);
$assignedRooms = [];
if ($assignedRoomsResult) {
    while ($row = $assignedRoomsResult->fetch_assoc()) {
        $assignedRooms[] = $row['room_number'];
    }
}

// Fetch all bookings with user info
$bookingsQuery = "SELECT b.*, u.first_name, u.last_name, u.email FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.order_date DESC";
$bookingsResult = $conn->query($bookingsQuery);

if (!$bookingsResult) {
    die('Database error: ' . $conn->error);
}

// Fetch all results into an array to work with them
$allBookings = [];
while ($row = $bookingsResult->fetch_assoc()) {
    $allBookings[] = $row;
}

// Separate bookings into categories
$pendingBookings = [];
$assignedBookings = [];
$finishedBookings = [];
$cancelledBookingsArray = [];

foreach ($allBookings as $booking) {
    // Handle empty status - default to 'active' (pending)
    $status = trim($booking['status'] ?? '');
    if (empty($status)) {
        $status = 'active';
    }
    
    if ($status === 'cancelled') {
        $cancelledBookingsArray[] = $booking;
    } else if ($status === 'finished') {
        $finishedBookings[] = $booking;
    } else if ($status === 'assigned') {
        $assignedBookings[] = $booking;
    } else if ($status === 'active') {
        $pendingBookings[] = $booking;
    }
}

// Get booking statistics from the arrays we just created
$totalBookings = count($pendingBookings) + count($assignedBookings) + count($finishedBookings) + count($cancelledBookingsArray);
$pendingBookingsCount = count($pendingBookings);
$assignedBookingsCount = count($assignedBookings);
$finishedBookingsCount = count($finishedBookings);
$cancelledBookingsCount = count($cancelledBookingsArray);

// Calculate revenue
$totalRevenue = 0;
foreach ($assignedBookings as $booking) {
    $totalRevenue += (float)$booking['total_price'];
}
foreach ($finishedBookings as $booking) {
    $totalRevenue += (float)$booking['total_price'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .booking-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .booking-tab {
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            bottom: -2px;
        }

        .booking-tab.active {
            color: var(--accent-color);
            border-bottom-color: var(--accent-color);
        }

        .booking-tab:hover {
            color: var(--text-dark);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .booking-row-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-assign {
            background-color: var(--success-color);
            color: white;
        }

        .btn-assign:hover {
            background-color: #229954;
        }

        .btn-cancel {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c0392b;
        }

        .btn-finish {
            background-color: #2e7d32;
            color: white;
        }

        .btn-finish:hover {
            background-color: #1b5e20;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-header h2 {
            margin: 0;
            color: var(--text-dark);
        }

        .modal-body {
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-modal {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-confirm {
            background-color: var(--success-color);
            color: white;
        }

        .btn-confirm:hover {
            background-color: #229954;
        }

        .btn-close-modal {
            background-color: #ddd;
            color: var(--text-dark);
        }

        .btn-close-modal:hover {
            background-color: #ccc;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.15);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .no-bookings {
            text-align: center;
            padding: 3rem;
            color: #999;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .booking-info-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            background: rgba(184, 134, 11, 0.1);
            color: var(--accent-color);
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="logo">Elegante <span>Admin</span></div>
        <div class="admin-header-right">
            <div class="admin-user-info">
                <div class="user-icon">A</div>
                <span><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
            </div>
            <a href="logout.php" class="admin-logout-btn">LOG OUT</a>
        </div>
    </header>

    <!-- Admin Container with Sidebar -->
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <a href="dashboard.php" class="nav-item">
                    <span class="nav-icon">📊</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="bookings.php" class="nav-item active">
                    <span class="nav-icon">📅</span>
                    <span class="nav-label">Bookings</span>
                </a>
                <a href="users.php" class="nav-item">
                    <span class="nav-icon">👥</span>
                    <span class="nav-label">Users</span>
                </a>
                <a href="rooms.php" class="nav-item">
                    <span class="nav-icon">🏨</span>
                    <span class="nav-label">Rooms</span>
                </a>
                <a href="reviews.php" class="nav-item">
                    <span class="nav-icon">⭐</span>
                    <span class="nav-label">Reviews</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main-content">
            <div class="admin-container">
                <!-- Page Title -->
                <h1 class="page-title">Bookings</h1>
                <p class="page-subtitle">Manage bookings, assign rooms, and track reservations.</p>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Bookings Statistics -->
                <div class="stats-grid">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">📋</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Total Bookings</p>
                                <h3 class="stat-number"><?php echo (int)$totalBookings; ?></h3>
                                <p class="stat-description">All reservations</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">⏳</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Pending Check-in</p>
                                <h3 class="stat-number"><?php echo (int)$pendingBookingsCount; ?></h3>
                                <p class="stat-description">Awaiting room assignment</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-primary">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">🔑</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Assigned Bookings</p>
                                <h3 class="stat-number"><?php echo (int)$assignedBookingsCount; ?></h3>
                                <p class="stat-description">Room assigned</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-success">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">✅</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Finished Bookings</p>
                                <h3 class="stat-number"><?php echo $finishedBookingsCount; ?></h3>
                                <p class="stat-description">Completed stays</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-info">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">💰</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Active Revenue</p>
                                <h3 class="stat-number">₱<?php echo number_format((int)$totalRevenue); ?></h3>
                                <p class="stat-description">Pending & booked</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Tabs -->
                <div class="booking-tabs">
                    <button class="booking-tab active" onclick="switchTab('pending')">
                        ⏳ Pending (<?php echo count($pendingBookings); ?>)
                    </button>
                    <button class="booking-tab" onclick="switchTab('assigned')">
                        🔑 Assigned (<?php echo count($assignedBookings); ?>)
                    </button>
                    <button class="booking-tab" onclick="switchTab('booked')">
                        ✅ Finished (<?php echo count($finishedBookings); ?>)
                    </button>
                    <button class="booking-tab" onclick="switchTab('cancelled')">
                        ❌ Cancelled (<?php echo $cancelledBookingsCount; ?>)
                    </button>
                </div>

                <!-- Pending Bookings Tab -->
                <section class="tab-content active" id="pending">
                    <div class="analytics-section">
                        <h2 class="analytics-title">Pending Check-in</h2>
                        <?php if (count($pendingBookings) > 0): ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Guest Name</th>
                                            <th>Room Type</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Total Price</th>
                                            <th>Guests</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingBookings as $booking):
                                            $checkIn = new DateTime($booking['check_in']);
                                            $checkOut = new DateTime($booking['check_out']);
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo (int)$booking['id']; ?></strong></td>
                                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td><span class="booking-info-badge"><?php echo htmlspecialchars($booking['room_name']); ?></span></td>
                                                <td><?php echo $checkIn->format('M d, Y'); ?></td>
                                                <td><?php echo $checkOut->format('M d, Y'); ?></td>
                                                <td><strong>₱<?php echo number_format((int)$booking['total_price']); ?></strong></td>
                                                <td><?php echo (int)$booking['number_of_guests']; ?></td>
                                                <td>
                                                    <div class="booking-row-actions">
                                                        <button class="btn-small btn-assign" onclick="openAssignModal(<?php echo (int)$booking['id']; ?>, '<?php echo htmlspecialchars($booking['room_name']); ?>')">
                                                            Assign Room
                                                        </button>
                                                        <button class="btn-small btn-cancel" onclick="cancelBooking(<?php echo (int)$booking['id']; ?>)">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-bookings">
                                <p>No pending check-ins at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Assigned Bookings Tab -->
                <section class="tab-content" id="assigned">
                    <div class="analytics-section">
                        <h2 class="analytics-title">Assigned Rooms</h2>
                        <?php if (count($assignedBookings) > 0): ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Guest Name</th>
                                            <th>Room Type</th>
                                            <th>Room Number</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Total Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assignedBookings as $booking):
                                            $checkIn = new DateTime($booking['check_in']);
                                            $checkOut = new DateTime($booking['check_out']);
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo (int)$booking['id']; ?></strong></td>
                                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td><span class="booking-info-badge"><?php echo htmlspecialchars($booking['room_name']); ?></span></td>
                                                <td><strong><?php echo htmlspecialchars($booking['room_number']); ?></strong></td>
                                                <td><?php echo $checkIn->format('M d, Y'); ?></td>
                                                <td><?php echo $checkOut->format('M d, Y'); ?></td>
                                                <td><strong>₱<?php echo number_format((int)$booking['total_price']); ?></strong></td>
                                                <td>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="action" value="mark_finished">
                                                        <input type="hidden" name="booking_id" value="<?php echo (int)$booking['id']; ?>">
                                                        <button type="submit" class="btn-small btn-finish" onclick="return confirm('Mark this booking as finished? User will be able to review.');">
                                                            ✓ Finish
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-bookings">
                                <p>No bookings with assigned rooms yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Finished Bookings Tab -->
                <section class="tab-content" id="booked">
                    <div class="analytics-section">
                        <h2 class="analytics-title">Finished Bookings</h2>
                        <?php if (count($finishedBookings) > 0): ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Guest Name</th>
                                            <th>Room Type</th>
                                            <th>Room Number</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Total Price</th>
                                            <th>Review Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($finishedBookings as $booking):
                                            $checkIn = new DateTime($booking['check_in']);
                                            $checkOut = new DateTime($booking['check_out']);
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo (int)$booking['id']; ?></strong></td>
                                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td><span class="booking-info-badge"><?php echo htmlspecialchars($booking['room_name']); ?></span></td>
                                                <td><strong><?php echo htmlspecialchars($booking['room_number']); ?></strong></td>
                                                <td><?php echo $checkIn->format('M d, Y'); ?></td>
                                                <td><?php echo $checkOut->format('M d, Y'); ?></td>
                                                <td><strong>₱<?php echo number_format((int)$booking['total_price']); ?></strong></td>
                                                <td>
                                                    <?php if ($booking['has_review']): ?>
                                                        <a href="reviews.php?booking_id=<?php echo (int)$booking['id']; ?>" class="status-badge status-success">
                                                            ✓ View Review
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="status-badge status-warning">
                                                            ⏳ Pending Review
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-bookings">
                                <p>No finished bookings yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Cancelled Bookings Tab -->
                <section class="tab-content" id="cancelled">
                    <div class="analytics-section">
                        <h2 class="analytics-title">Cancelled Bookings</h2>
                        <?php if (count($cancelledBookingsArray) > 0): ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Guest Name</th>
                                            <th>Room Type</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Total Price</th>
                                            <th>Cancellation Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cancelledBookingsArray as $booking):
                                            $checkIn = new DateTime($booking['check_in']);
                                            $checkOut = new DateTime($booking['check_out']);
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo (int)$booking['id']; ?></strong></td>
                                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td><span class="booking-info-badge"><?php echo htmlspecialchars($booking['room_name']); ?></span></td>
                                                <td><?php echo $checkIn->format('M d, Y'); ?></td>
                                                <td><?php echo $checkOut->format('M d, Y'); ?></td>
                                                <td><strong>₱<?php echo number_format((int)$booking['total_price']); ?></strong></td>
                                                <td><?php echo $booking['order_date']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-bookings">
                                <p>No cancelled bookings.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Assign Room Modal -->
    <div id="assignRoomModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Assign Room Number</h2>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="assign_room">
                <input type="hidden" name="booking_id" id="bookingId">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roomSelect">Select Room Number:</label>
                        <select id="roomSelect" name="room_number" required>
                            <option value="">-- Select a room --</option>
                        </select>
                    </div>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 1rem;">
                        <strong>Room Number Format:</strong> Each room has a prefix indicating its type (e.g., S for Single, D for Double).
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-close-modal" onclick="closeAssignModal()">Cancel</button>
                    <button type="submit" class="btn-modal btn-confirm">Assign Room</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Booking Form (Hidden) -->
    <form id="cancelForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="cancel_booking">
        <input type="hidden" name="booking_id" id="cancelBookingId">
    </form>

    <script>
        // Room prefixes and quantities
        const availableRooms = <?php echo json_encode($availableRooms); ?>;
        const assignedRooms = <?php echo json_encode($assignedRooms); ?>;

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.booking-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function openAssignModal(bookingId, roomName) {
            document.getElementById('bookingId').value = bookingId;
            
            // Populate room select options
            const roomSelect = document.getElementById('roomSelect');
            roomSelect.innerHTML = '<option value="">-- Select a room --</option>';
            
            if (availableRooms[roomName]) {
                availableRooms[roomName].forEach(roomNumber => {
                    const option = document.createElement('option');
                    option.value = roomNumber;
                    
                    // Check if room is already assigned
                    if (assignedRooms.includes(roomNumber)) {
                        option.textContent = roomNumber + ' (Already Assigned)';
                        option.disabled = true;
                        option.style.color = '#999';
                        option.style.backgroundColor = '#f5f5f5';
                    } else {
                        option.textContent = roomNumber + ' (Available)';
                    }
                    
                    roomSelect.appendChild(option);
                });
            }
            
            document.getElementById('assignRoomModal').classList.add('active');
        }

        function closeAssignModal() {
            document.getElementById('assignRoomModal').classList.remove('active');
        }

        function cancelBooking(bookingId) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                document.getElementById('cancelBookingId').value = bookingId;
                document.getElementById('cancelForm').submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('assignRoomModal');
            if (event.target === modal) {
                closeAssignModal();
            }
        };

        // Page transition animation
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.admin-container');
            if (container) {
                container.style.opacity = '0';
                container.style.animation = 'fadeIn 0.5s ease-in forwards';
            }

            // Logout confirmation
            const logoutBtn = document.querySelector('.admin-logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to log out?')) {
                        window.location.href = this.href;
                    }
                });
            }
        });

        // Add fade-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
