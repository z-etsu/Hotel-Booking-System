<?php
// This file includes the navbar for all pages
// Make sure session_start() is called before including this file
if (!isset($_SESSION)) {
    session_start();
}
?>
<header id="navbar">
    <div class="container">
        <a href="index.php" style="text-decoration: none;" class="logo-link"><h1 class="logo">Hotel Name</h1></a>
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
    const isSettingsPage = document.body.classList.contains('settings-page');
    
    if (isBookingPage || isBookingsPage || isSettingsPage) {
        navbar.classList.add('scrolled');
    }

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            // Don't remove scrolled class if on booking page, bookings page, or settings page
            if (!isBookingPage && !isBookingsPage && !isSettingsPage) {
                navbar.classList.remove('scrolled');
            }
        }
    });

    // User dropdown menu toggle and logout confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const userGreeting = document.getElementById('userGreeting');
        const userDropdown = document.getElementById('userDropdown');
        const logoutLink = document.getElementById('logoutLink');
        
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

        // Logout confirmation modal
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                showLogoutConfirmation();
            });
        }

        function showLogoutConfirmation() {
            const modal = document.getElementById('logoutConfirmationModal');
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        // Close modal functionality
        const closeButtons = document.querySelectorAll('.logout-modal-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.getElementById('logoutConfirmationModal');
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        const cancelBtn = document.getElementById('cancelLogoutBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const modal = document.getElementById('logoutConfirmationModal');
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }

        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', function() {
                window.location.href = 'logout.php';
            });
        }

        // Close modal when clicking outside
        const modal = document.getElementById('logoutConfirmationModal');
        if (modal) {
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
</script>

<!-- Logout Confirmation Modal -->
<div id="logoutConfirmationModal" class="logout-modal">
    <div class="logout-modal-content">
        <button class="logout-modal-close">&times;</button>
        <div class="logout-modal-icon">👋</div>
        <h2>Confirm Logout</h2>
        <p>Are you sure you want to log out? You'll need to log in again to access your bookings and account.</p>
        <div class="logout-modal-actions">
            <button id="cancelLogoutBtn" class="btn btn-modal-secondary">Cancel</button>
            <button id="confirmLogoutBtn" class="btn btn-modal-logout">Logout</button>
        </div>
    </div>
</div>

<!-- Logout Modal Styles -->
<style>
    /* Logout Modal Styles */
    .logout-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        animation: blurIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .logout-modal.show {
        display: flex;
    }

    @keyframes blurIn {
        from {
            opacity: 0;
            filter: blur(8px);
        }
        to {
            opacity: 1;
            filter: blur(0px);
        }
    }

    .logout-modal-content {
        background-color: #fdf8f4;
        padding: 40px;
        border-radius: 12px;
        width: 90%;
        max-width: 420px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        position: relative;
        text-align: center;
        animation: slideUpBlur 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes slideUpBlur {
        from {
            transform: translateY(20px);
            opacity: 0;
            filter: blur(6px);
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .logout-modal-close {
        position: absolute;
        right: 15px;
        top: 15px;
        background: none;
        border: none;
        font-size: 28px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .logout-modal-close:hover {
        color: #333;
    }

    .logout-modal-icon {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
    }

    .logout-modal-content h2 {
        font-size: 1.6rem;
        color: #111;
        margin-bottom: 15px;
        font-weight: 500;
        letter-spacing: -0.01em;
        font-family: 'Cinzel', 'Georgia', serif;
    }

    .logout-modal-content p {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .logout-modal-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .logout-modal-actions .btn {
        flex: 1;
        max-width: 160px;
    }

    .btn-modal-secondary {
        background-color: #e8e8e8;
        color: #333;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-modal-secondary:hover {
        background-color: #d0d0d0;
        transform: translateY(-2px);
    }

    .btn-modal-logout {
        background-color: #c62828;
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-modal-logout:hover {
        background-color: #ad1457;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
    }

    @media (max-width: 480px) {
        .logout-modal-content {
            width: 95%;
            padding: 25px;
        }

        .logout-modal-actions {
            flex-direction: column;
        }

        .logout-modal-actions .btn {
            max-width: 100%;
        }
    }
</style>
