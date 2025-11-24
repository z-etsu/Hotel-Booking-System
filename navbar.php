<?php
// This file includes the navbar for all pages
// Make sure session_start() is called before including this file
if (!isset($_SESSION)) {
    session_start();
}
?>
<header id="navbar">
    <div class="container">
        <h1 class="logo">Hotel Name</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="rooms.php">Rooms</a>
            <a href="facilities.php">Facilities</a>
            <a href="about.php">About</a>
          

            <?php if (isset($_SESSION['user_name'])): ?>
                <div class="user-greeting-container" style="position:relative;display:inline-block;">
                    <span class="user-greeting" id="userGreeting" style="cursor:pointer;">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <div class="user-dropdown" id="userDropdown" style="display:none;position:absolute;top:100%;right:0;background:#fff;border:1px solid #ddd;border-radius:6px;min-width:150px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:999;margin-top:8px;">
                        <a href="bookings.php" style="display:block;padding:10px 16px;color:#111;text-decoration:none;font-size:0.9rem;">Bookings</a>
                        <a href="settings.php" style="display:block;padding:10px 16px;color:#111;text-decoration:none;font-size:0.9rem;">Settings</a>
                        <a href="#" id="logoutLink" style="display:block;padding:10px 16px;color:#111;text-decoration:none;font-size:0.9rem;border-top:1px solid #eee;">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login-page.php">Login / Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    // Navbar scroll detection
    const navbar = document.getElementById('navbar');
    
    // Check if we're on the booking page - if so, start in scrolled state
    const isBookingPage = document.body.classList.contains('booking-page');
    const isBookingsPage = document.body.classList.contains('bookings-page');
    
    if (isBookingPage || isBookingsPage) {
        navbar.classList.add('scrolled');
    }

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            // Don't remove scrolled class if on booking page or bookings page
            if (!isBookingPage && !isBookingsPage) {
                navbar.classList.remove('scrolled');
            }
        }
    });

        // User dropdown menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const userGreeting = document.getElementById('userGreeting');
            const userDropdown = document.getElementById('userDropdown');
            if (userGreeting && userDropdown) {
                userGreeting.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.style.display = (userDropdown.style.display === 'block') ? 'none' : 'block';
                });
                document.addEventListener('click', function(e) {
                    if (!userGreeting.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.style.display = 'none';
                    }
                });
            }
        });
</script>
