<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db_connect.php';

// Get total bookings
$totalBookingsQuery = "
    SELECT 
        COUNT(*) as total_count,
        COALESCE(SUM(total_price), 0) as total_revenue
    FROM bookings
";
$totalBookingsResult = $conn->query($totalBookingsQuery);
$totalBookingsData = $totalBookingsResult->fetch_assoc();
$totalBookings = $totalBookingsData['total_count'];
$totalRevenue = $totalBookingsData['total_revenue'];

// Get active bookings
$activeBookingsQuery = "
    SELECT 
        COUNT(*) as active_count,
        COALESCE(SUM(total_price), 0) as active_revenue
    FROM bookings
    WHERE status = 'active'
";
$activeBookingsResult = $conn->query($activeBookingsQuery);
$activeBookingsData = $activeBookingsResult->fetch_assoc();
$activeBookings = $activeBookingsData['active_count'];

// Get cancelled bookings
$cancelledBookingsQuery = "
    SELECT 
        COUNT(*) as cancelled_count,
        COALESCE(SUM(total_price), 0) as cancelled_revenue
    FROM bookings
    WHERE status = 'cancelled'
";
$cancelledBookingsResult = $conn->query($cancelledBookingsQuery);
$cancelledBookingsData = $cancelledBookingsResult->fetch_assoc();
$cancelledBookings = $cancelledBookingsData['cancelled_count'];

// Get total users
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsersData = $totalUsersResult->fetch_assoc();
$totalUsers = $totalUsersData['total_users'];

// Get total rooms (static count from room types)
$roomTypes = [
    'Single Room' => 12,
    'Double Room' => 15,
    'Twin Room' => 10,
    'Triple Room' => 8,
    'Family Room' => 5,
    'Royal Suite' => 3,
    'Presidential Suite' => 2
];
$totalRooms = array_sum($roomTypes);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Elegante</title>
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
                <a href="dashboard.php" class="nav-item active">
                    <span class="nav-icon">📊</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="bookings.php" class="nav-item">
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
        <!-- Page Title -->
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome to your admin dashboard. Here's an overview of your bookings.</p>

        <!-- Booking Analytics Section -->
        <section class="analytics-section">
            <h2 class="analytics-title">Booking Analytics</h2>
            <div class="analytics-grid">
                <!-- Total Bookings Card -->
                <div class="analytics-card total-bookings">
                    <div class="card-icon">📊</div>
                    <p class="card-label">Total Bookings</p>
                    <div class="card-value"><?php echo number_format($totalBookings); ?></div>
                    <p class="card-subtext">₱<?php echo number_format($totalRevenue, 0); ?> total revenue</p>
                </div>

                <!-- Active Bookings Card -->
                <div class="analytics-card success active-bookings">
                    <div class="card-icon">✓</div>
                    <p class="card-label">Active Bookings</p>
                    <div class="card-value"><?php echo number_format($activeBookings); ?></div>
                    <p class="card-subtext"><?php echo round(($activeBookings / max($totalBookings, 1)) * 100, 1); ?>% of total</p>
                </div>

                <!-- Cancelled Bookings Card -->
                <div class="analytics-card danger cancelled-bookings">
                    <div class="card-icon">✕</div>
                    <p class="card-label">Cancelled Bookings</p>
                    <div class="card-value"><?php echo number_format($cancelledBookings); ?></div>
                    <p class="card-subtext"><?php echo round(($cancelledBookings / max($totalBookings, 1)) * 100, 1); ?>% of total</p>
                </div>

                <!-- Revenue Card -->
                <div class="analytics-card warning total-revenue">
                    <div class="card-icon">₱</div>
                    <p class="card-label">Total Revenue</p>
                    <div class="card-value">₱<?php echo number_format($totalRevenue, 0); ?></div>
                    <p class="card-subtext">From all bookings</p>
                </div>
            </div>
        </section>

        <!-- Users & Rooms Summary Section -->
        <section class="analytics-section">
            <h2 class="analytics-title">System Overview</h2>
            <div class="analytics-grid">
                <!-- Total Users Card -->
                <div class="analytics-card primary total-users">
                    <div class="card-icon">👥</div>
                    <p class="card-label">Total Users</p>
                    <div class="card-value"><?php echo number_format($totalUsers); ?></div>
                    <p class="card-subtext">Registered members</p>
                </div>

                <!-- Total Rooms Card -->
                <div class="analytics-card success total-rooms">
                    <div class="card-icon">🏨</div>
                    <p class="card-label">Total Rooms</p>
                    <div class="card-value"><?php echo number_format($totalRooms); ?></div>
                    <p class="card-subtext">Available inventory</p>
                </div>
            </div>
        </section>
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