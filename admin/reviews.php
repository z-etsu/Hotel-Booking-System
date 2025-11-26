<?php
session_start();
require_once '../db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch all reviews with user and booking details
$reviewsQuery = "
    SELECT 
        r.id,
        r.booking_id,
        r.room_name,
        r.stars,
        r.description,
        r.review_date,
        u.first_name,
        u.last_name,
        u.email,
        b.check_in,
        b.check_out
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN bookings b ON r.booking_id = b.id
    ORDER BY r.review_date DESC
";

$reviewsResult = $conn->query($reviewsQuery);
$reviews = [];
if ($reviewsResult) {
    while ($row = $reviewsResult->fetch_assoc()) {
        $reviews[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Reviews - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .reviews-container {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .reviews-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .review-count {
            background: #b8860b;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .review-card {
            background: #fff8f2;
            border-left: 4px solid #b8860b;
            border-radius: 6px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .review-card:hover {
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
            transform: translateX(2px);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .review-user {
            font-weight: 600;
            color: #532200;
            font-size: 1.1rem;
        }

        .review-email {
            color: #b8860b;
            font-size: 0.9rem;
        }

        .review-date {
            color: #666;
            font-size: 0.9rem;
        }

        .review-stars {
            font-size: 1.3rem;
            color: #b8860b;
            margin-bottom: 0.5rem;
        }

        .review-room {
            background: #e6e1d8;
            color: #532200;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 0.8rem;
        }

        .review-dates {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.8rem;
        }

        .review-description {
            color: #333;
            line-height: 1.6;
            font-family: 'Georgia', serif;
            padding: 1rem;
            background: #fff;
            border-radius: 4px;
            border-left: 2px solid #b8860b;
        }

        .no-reviews {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="logo">Elegante <span>Admin</span></div>
        <div class="admin-header-right">
            <div class="admin-user-info">
                <div class="user-icon">A</div>
                <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
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
                <a href="rooms.php" class="nav-item">
                    <span class="nav-icon">🏨</span>
                    <span class="nav-label">Rooms</span>
                </a>
                <a href="reviews.php" class="nav-item active">
                    <span class="nav-icon">⭐</span>
                    <span class="nav-label">Reviews</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main-content">
            <div class="admin-container">
                <div class="reviews-header-top">
                    <div>
                        <h1 class="page-title">Guest Reviews</h1>
                        <p class="page-subtitle">View and manage all guest reviews</p>
                    </div>
                    <span class="review-count"><?php echo count($reviews); ?> Reviews</span>
                </div>

                <div class="reviews-container">
                    <?php if (empty($reviews)): ?>
                        <div class="no-reviews">
                            <p>No reviews yet. Check back once guests start leaving reviews!</p>
                        </div>
                    <?php else: ?>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-card">
                                    <div class="review-header">
                                        <div>
                                            <div class="review-user"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></div>
                                            <div class="review-email"><?php echo htmlspecialchars($review['email']); ?></div>
                                        </div>
                                        <div class="review-date"><?php echo date('M d, Y H:i', strtotime($review['review_date'])); ?></div>
                                    </div>
                                    
                                    <div style="margin-bottom: 1rem;">
                                        <div class="review-stars"><?php echo str_repeat('★', $review['stars']) . str_repeat('☆', 5 - $review['stars']); ?></div>
                                    </div>

                                    <div style="margin-bottom: 1rem;">
                                        <span class="review-room"><?php echo htmlspecialchars($review['room_name']); ?></span>
                                    </div>

                                    <div class="review-dates">
                                        📅 Stayed: <?php echo date('M d, Y', strtotime($review['check_in'])); ?> - <?php echo date('M d, Y', strtotime($review['check_out'])); ?>
                                    </div>

                                    <div class="review-description">
                                        <?php echo nl2br(htmlspecialchars($review['description'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
