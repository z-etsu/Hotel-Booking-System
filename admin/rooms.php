<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db_connect.php';

// Room data with quantities
$rooms = [
    [
        "name" => "Single Room",
        "category" => "Standard",
        "size" => "20 M²",
        "maxPeople" => 1,
        "price" => 5000,
        "quantity" => 12
    ],
    [
        "name" => "Double Room",
        "category" => "Standard",
        "size" => "30 M²",
        "maxPeople" => 2,
        "price" => 7200,
        "quantity" => 15
    ],
    [
        "name" => "Twin Room",
        "category" => "Standard",
        "size" => "35 M²",
        "maxPeople" => 2,
        "price" => 7800,
        "quantity" => 10
    ],
    [
        "name" => "Triple Room",
        "category" => "Family",
        "size" => "40 M²",
        "maxPeople" => 3,
        "price" => 10600,
        "quantity" => 8
    ],
    [
        "name" => "Family Room",
        "category" => "Family",
        "size" => "45 M²",
        "maxPeople" => 4,
        "price" => 12800,
        "quantity" => 7
    ],
    [
        "name" => "Connected Room",
        "category" => "Family",
        "size" => "50 M²",
        "maxPeople" => 4,
        "price" => 14000,
        "quantity" => 5
    ],
    [
        "name" => "Executive Suite",
        "category" => "Suite",
        "size" => "70 M²",
        "maxPeople" => 5,
        "price" => 16800,
        "quantity" => 6
    ],
    [
        "name" => "Presidential Suite",
        "category" => "Suite",
        "size" => "90 M²",
        "maxPeople" => 6,
        "price" => 22400,
        "quantity" => 3
    ],
    [
        "name" => "Royal Suite",
        "category" => "Suite",
        "size" => "100 M²",
        "maxPeople" => 6,
        "price" => 25200,
        "quantity" => 2
    ]
];

// Get booked rooms information
$bookedRooms = [];
$bookingsQuery = "
    SELECT 
        room_name,
        check_in,
        check_out,
        status,
        (SELECT first_name FROM users WHERE id = bookings.user_id) as guest_name
    FROM bookings
    WHERE status = 'active'
    AND check_out >= CURDATE()
    ORDER BY check_in ASC
";
$bookingsResult = $conn->query($bookingsQuery);
if ($bookingsResult) {
    while ($row = $bookingsResult->fetch_assoc()) {
        $bookedRooms[] = $row;
    }
}

// Calculate available rooms per type
$roomAvailability = [];
foreach ($rooms as $room) {
    $bookedCount = 0;
    foreach ($bookedRooms as $booking) {
        if ($booking['room_name'] === $room['name']) {
            $bookedCount++;
        }
    }
    $roomAvailability[$room['name']] = [
        'total' => $room['quantity'],
        'booked' => $bookedCount,
        'available' => $room['quantity'] - $bookedCount
    ];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Admin Dashboard</title>
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
                <a href="users.php" class="nav-item">
                    <span class="nav-icon">👥</span>
                    <span class="nav-label">Users</span>
                </a>
                <a href="rooms.php" class="nav-item active">
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
                <h1 class="page-title">Hotel Rooms</h1>
                <p class="page-subtitle">Manage all available rooms and their quantities.</p>

                <!-- Rooms Statistics -->
                <div class="stats-grid">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">🏨</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Total Rooms</p>
                                <h3 class="stat-number"><?php echo array_sum(array_column($rooms, 'quantity')); ?></h3>
                                <p class="stat-description">Across all room types</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-success">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">✅</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Available Now</p>
                                <h3 class="stat-number"><?php echo array_sum(array_column($roomAvailability, 'available')); ?></h3>
                                <p class="stat-description">Ready for booking</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">📅</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Currently Booked</p>
                                <h3 class="stat-number"><?php echo array_sum(array_column($roomAvailability, 'booked')); ?></h3>
                                <p class="stat-description">Active reservations</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-info">
                        <div class="stat-card-inner">
                            <div class="stat-icon-wrapper">
                                <span class="stat-icon-large">💰</span>
                            </div>
                            <div class="stat-info">
                                <p class="stat-label">Average Price</p>
                                <h3 class="stat-number">₱<?php echo number_format((int)(array_sum(array_column($rooms, 'price')) / count($rooms))); ?></h3>
                                <p class="stat-description">Per night</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rooms Table Section -->
                <section class="analytics-section">
                    <h2 class="analytics-title">Room Inventory</h2>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Room Name</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>Max Capacity</th>
                                    <th>Price/Night</th>
                                    <th>Total</th>
                                    <th>Booked</th>
                                    <th>Available</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $room): 
                                    $avail = $roomAvailability[$room['name']];
                                    $availPercent = ($avail['available'] / $avail['total']) * 100;
                                ?>
                                    <tr>
                                        <td class="room-name"><?php echo htmlspecialchars($room['name']); ?></td>
                                        <td>
                                            <span class="category-badge category-<?php echo strtolower($room['category']); ?>">
                                                <?php echo htmlspecialchars($room['category']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($room['size']); ?></td>
                                        <td>
                                            <span class="capacity-badge">
                                                <?php echo (int)$room['maxPeople']; ?> 
                                                <?php echo (int)$room['maxPeople'] === 1 ? 'Person' : 'People'; ?>
                                            </span>
                                        </td>
                                        <td class="price">₱<?php echo number_format((int)$room['price']); ?></td>
                                        <td><strong><?php echo (int)$avail['total']; ?></strong></td>
                                        <td>
                                            <span class="status-badge status-booked"><?php echo (int)$avail['booked']; ?></span>
                                        </td>
                                        <td>
                                            <div class="availability-cell">
                                                <span class="status-badge status-available"><?php echo (int)$avail['available']; ?></span>
                                                <div class="availability-bar">
                                                    <div class="availability-fill" style="width: <?php echo $availPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Room Categories Summary -->
                <section class="analytics-section">
                    <h2 class="analytics-title">Booked Rooms</h2>
                    <?php if (count($bookedRooms) > 0): ?>
                        <div class="table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Room Name</th>
                                        <th>Guest Name</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookedRooms as $booking): 
                                        $checkIn = new DateTime($booking['check_in']);
                                        $checkOut = new DateTime($booking['check_out']);
                                        $duration = $checkOut->diff($checkIn)->days;
                                    ?>
                                        <tr>
                                            <td class="room-name"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['guest_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo $checkIn->format('M d, Y'); ?></td>
                                            <td><?php echo $checkOut->format('M d, Y'); ?></td>
                                            <td><?php echo $duration; ?> night<?php echo $duration !== 1 ? 's' : ''; ?></td>
                                            <td>
                                                <span class="status-badge status-active">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No active bookings at the moment.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Room Categories Summary -->
                <section class="analytics-section">
                    <h2 class="analytics-title">Room Categories Overview</h2>
                    <div class="category-summary">
                        <?php 
                        $categories = [];
                        $categoryIcons = [
                            'standard' => '🛏️',
                            'family' => '👨‍👩‍👧‍👦',
                            'suite' => '✨'
                        ];
                        
                        foreach ($rooms as $room) {
                            if (!isset($categories[$room['category']])) {
                                $categories[$room['category']] = ['count' => 0, 'quantity' => 0, 'booked' => 0];
                            }
                            $categories[$room['category']]['count']++;
                            $categories[$room['category']]['quantity'] += $room['quantity'];
                            $categories[$room['category']]['booked'] += $roomAvailability[$room['name']]['booked'];
                        }
                        
                        foreach ($categories as $category => $data):
                            $available = $data['quantity'] - $data['booked'];
                            $occupancyPercent = ($data['booked'] / $data['quantity']) * 100;
                            $categoryKey = strtolower($category);
                            $icon = $categoryIcons[$categoryKey] ?? '🏨';
                        ?>
                            <div class="category-card category-card-<?php echo $categoryKey; ?>">
                                <div class="category-card-header">
                                    <span class="category-icon"><?php echo $icon; ?></span>
                                    <h3><?php echo htmlspecialchars($category); ?></h3>
                                </div>
                                <div class="category-stats-grid">
                                    <div class="category-stat">
                                        <span class="stat-label">Room Types</span>
                                        <span class="stat-value"><?php echo $data['count']; ?></span>
                                    </div>
                                    <div class="category-stat">
                                        <span class="stat-label">Total Rooms</span>
                                        <span class="stat-value"><?php echo $data['quantity']; ?></span>
                                    </div>
                                    <div class="category-stat">
                                        <span class="stat-label">Booked</span>
                                        <span class="stat-value stat-booked"><?php echo $data['booked']; ?></span>
                                    </div>
                                    <div class="category-stat">
                                        <span class="stat-label">Available</span>
                                        <span class="stat-value stat-available"><?php echo $available; ?></span>
                                    </div>
                                </div>
                                <div class="occupancy-bar">
                                    <div class="occupancy-fill" style="width: <?php echo $occupancyPercent; ?>%"></div>
                                </div>
                                <span class="occupancy-text"><?php echo round($occupancyPercent, 1); ?>% occupancy</span>
                            </div>
                        <?php endforeach; ?>
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
