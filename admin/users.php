<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db_connect.php';

// Fetch all users
$usersQuery = "SELECT id, first_name, middle_initial, last_name, email, birthday FROM users ORDER BY id DESC";
$usersResult = $conn->query($usersQuery);

if (!$usersResult) {
    die('Database error: ' . $conn->error);
}

// Get user statistics
$totalUsersQuery = "SELECT COUNT(*) as total FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

// Get bookings count for users
$usersWithBookingsQuery = "SELECT COUNT(DISTINCT user_id) as users_booked FROM bookings WHERE status = 'active'";
$usersWithBookingsResult = $conn->query($usersWithBookingsQuery);
$usersBooked = $usersWithBookingsResult->fetch_assoc()['users_booked'];

// Get total revenue
$revenueQuery = "SELECT COALESCE(SUM(total_price), 0) as total_revenue FROM bookings WHERE status = 'active'";
$revenueResult = $conn->query($revenueQuery);
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Dashboard</title>
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
                <a href="bookings.php" class="nav-item">
                    <span class="nav-icon">📅</span>
                    <span class="nav-label">Bookings</span>
                </a>
                <a href="users.php" class="nav-item active">
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
                <h1 class="page-title">Users</h1>
                <p class="page-subtitle">All registered users and their details.</p>

                <!-- Users Statistics -->
                <div class="stats-grid">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">👥</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Total Users</p>
                                <h3 class="stat-number"><?php echo (int)$totalUsers; ?></h3>
                                <p class="stat-description">Registered members</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-success">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">📅</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Active Bookers</p>
                                <h3 class="stat-number"><?php echo (int)$usersBooked; ?></h3>
                                <p class="stat-description">With active bookings</p>
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

                <!-- Users Table Section -->
                <section class="analytics-section">
                    <h2 class="analytics-title">Registered Users</h2>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Birthday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($usersResult && $usersResult->num_rows > 0): ?>
                                    <?php while ($u = $usersResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo (int)$u['id']; ?></td>
                                            <td><?php echo htmlspecialchars($u['first_name'] . ' ' . ($u['middle_initial'] ? $u['middle_initial'] . ' ' : '') . $u['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                                            <td><?php echo htmlspecialchars($u['birthday']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem;">No users found.</td>
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
