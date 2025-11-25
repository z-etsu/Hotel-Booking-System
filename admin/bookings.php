<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db_connect.php';

// Fetch all bookings with user info
$bookingsQuery = "SELECT b.*, u.first_name, u.last_name, u.email FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.order_date DESC";
$bookingsResult = $conn->query($bookingsQuery);

if (!$bookingsResult) {
    die('Database error: ' . $conn->error);
}

// Get booking statistics
$totalBookingsQuery = "SELECT COUNT(*) as total FROM bookings";
$totalBookingsResult = $conn->query($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetch_assoc()['total'];

$activeBookingsQuery = "SELECT COUNT(*) as active FROM bookings WHERE status = 'active'";
$activeBookingsResult = $conn->query($activeBookingsQuery);
$activeBookings = $activeBookingsResult->fetch_assoc()['active'];

$cancelledBookingsQuery = "SELECT COUNT(*) as cancelled FROM bookings WHERE status = 'cancelled'";
$cancelledBookingsResult = $conn->query($cancelledBookingsQuery);
$cancelledBookings = $cancelledBookingsResult->fetch_assoc()['cancelled'];

$revenueQuery = "SELECT COALESCE(SUM(total_price), 0) as total_revenue FROM bookings WHERE status = 'active'";
$revenueResult = $conn->query($revenueQuery);
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

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
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main-content">
            <div class="admin-container">
                <!-- Page Title -->
                <h1 class="page-title">Bookings</h1>
                <p class="page-subtitle">All bookings with order details and user information.</p>

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
                                <p class="stat-description">All time reservations</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-success">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">✅</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Active Bookings</p>
                                <h3 class="stat-number"><?php echo (int)$activeBookings; ?></h3>
                                <p class="stat-description">Currently active</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">❌</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Cancelled</p>
                                <h3 class="stat-number"><?php echo (int)$cancelledBookings; ?></h3>
                                <p class="stat-description">Cancelled bookings</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-info">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">💰</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Total Revenue</p>
                                <h3 class="stat-number">₱<?php echo number_format((int)$totalRevenue); ?></h3>
                                <p class="stat-description">From active bookings</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Table Section -->
                <section class="analytics-section">
                    <h2 class="analytics-title">Recent Bookings</h2>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Booking #</th>
                                    <th>Room</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Nights</th>
                                    <th>Guests</th>
                                    <th>Total</th>
                                    <th>User</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($bookingsResult && $bookingsResult->num_rows > 0): ?>
                                    <?php while ($b = $bookingsResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo (int)$b['id']; ?></td>
                                            <td><?php echo htmlspecialchars($b['room_name']); ?></td>
                                            <td><?php echo htmlspecialchars($b['check_in']); ?></td>
                                            <td><?php echo htmlspecialchars($b['check_out']); ?></td>
                                            <td><?php echo (int)$b['number_of_nights']; ?></td>
                                            <td><?php echo (int)$b['number_of_guests']; ?></td>
                                            <td>₱<?php echo number_format($b['total_price'], 0); ?></td>
                                            <td><?php echo htmlspecialchars(($b['first_name'] ?? 'Unknown') . ' ' . ($b['last_name'] ?? '')); ?><br><small><?php echo htmlspecialchars($b['email'] ?? ''); ?></small></td>
                                            <td><span class="status-badge <?php echo strtolower($b['status']); ?>"><?php echo htmlspecialchars(ucfirst($b['status'])); ?></span></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 2rem;">No bookings found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
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
