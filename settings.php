<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, middle_initial, last_name, email, birthday, contact_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    $_SESSION['message'] = 'User not found.';
    $_SESSION['message_type'] = 'error';
    header("Location: login-page.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'update_profile') {
        $first_name = trim($_POST['first_name'] ?? '');
        $middle_initial = trim($_POST['middle_initial'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $birthday = trim($_POST['birthday'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');

        // Validation
        if (empty($first_name) || empty($last_name) || empty($birthday) || empty($contact_number)) {
            $message = 'All required fields must be filled out.';
            $message_type = 'error';
        } elseif (!preg_match('/^(\+63\s\d{3}\s\d{3}\s\d{4}|09\d{9})$/', $contact_number)) {
            $message = 'Please enter a valid Philippine phone number (e.g., +63 912 345 6789 or 09123456789).';
            $message_type = 'error';
        } else {
            // Convert empty middle_initial to NULL
            $middle_initial = $middle_initial === '' ? NULL : $middle_initial;

            $stmt = $conn->prepare("UPDATE users SET first_name = ?, middle_initial = ?, last_name = ?, birthday = ?, contact_number = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $first_name, $middle_initial, $last_name, $birthday, $contact_number, $user_id);

            if ($stmt->execute()) {
                $_SESSION['user_name'] = "$first_name $last_name";
                $user['first_name'] = $first_name;
                $user['middle_initial'] = $middle_initial;
                $user['last_name'] = $last_name;
                $user['birthday'] = $birthday;
                $user['contact_number'] = $contact_number;
                $message = 'Profile updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating profile. Please try again.';
                $message_type = 'error';
            }
            $stmt->close();
        }
    } elseif ($action === 'update_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Fetch current password hash
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();

        // Validate current password
        if (!password_verify($current_password, $user_data['password_hash'])) {
            $message = 'Current password is incorrect.';
            $message_type = 'error';
        } elseif (strlen($new_password) < 8) {
            $message = 'New password must be at least 8 characters long.';
            $message_type = 'error';
        } elseif ($new_password !== $confirm_password) {
            $message = 'New passwords do not match.';
            $message_type = 'error';
        } else {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $user_id);

            if ($stmt->execute()) {
                $message = 'Password updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating password. Please try again.';
                $message_type = 'error';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Elegante</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="settings.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
    <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition settings-page">
    <?php if ($message): ?>
        <div id="alert-message" class="message-box <?php echo $message_type; ?> show">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php include 'navbar.php'; ?>

    <main class="settings-main">
        <div class="container">
            <!-- Page Header -->
            <section class="settings-header">
                <h1>Account Settings</h1>
                <p>Manage your profile information and security settings</p>
            </section>

            <!-- Settings Container -->
            <div class="settings-container">
                <!-- Sidebar Navigation -->
                <aside class="settings-sidebar">
                    <nav class="settings-nav">
                        <button class="nav-item active" data-tab="profile">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </button>
                        <button class="nav-item" data-tab="password">
                            <i class="fas fa-lock"></i>
                            <span>Password</span>
                        </button>
                        <button class="nav-item" data-tab="account">
                            <i class="fas fa-cog"></i>
                            <span>Account Info</span>
                        </button>
                    </nav>
                </aside>

                <!-- Main Content Area -->
                <section class="settings-content">
                    <!-- Profile Tab -->
                    <div id="profile" class="settings-tab active">
                        <div class="tab-header">
                            <h2>Profile Information</h2>
                            <p>Update your personal details</p>
                        </div>

                        <form method="POST" class="settings-form">
                            <input type="hidden" name="action" value="update_profile">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input type="text" id="middle_initial" name="middle_initial" maxlength="1" value="<?php echo htmlspecialchars($user['middle_initial'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="birthday">Birthday (16+ required) *</label>
                                <input type="text" id="birthday" name="birthday" placeholder="Select your birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="contact_number">Contact Number *</label>
                                <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number (e.g., +63 912 345 6789)" pattern="(^\+63\s\d{3}\s\d{3}\s\d{4}$|^09\d{9}$)" title="Please enter a valid Philippine phone number (e.g., +63 912 345 6789 or 09123456789)" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>" required>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Tab -->
                    <div id="password" class="settings-tab">
                        <div class="tab-header">
                            <h2>Change Password</h2>
                            <p>Update your password to keep your account secure</p>
                        </div>

                        <form method="POST" class="settings-form">
                            <input type="hidden" name="action" value="update_password">

                            <div class="form-group">
                                <label for="current_password">Current Password *</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="current_password" name="current_password" required>
                                    <button type="button" class="toggle-password" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password *</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="new_password" name="new_password" required>
                                    <button type="button" class="toggle-password" data-target="new_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small>Must be at least 8 characters long</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password *</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                    <button type="button" class="toggle-password" data-target="confirm_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Account Info Tab -->
                    <div id="account" class="settings-tab">
                        <div class="tab-header">
                            <h2>Account Information</h2>
                            <p>View your account details</p>
                        </div>

                        <div class="account-info-grid">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-details">
                                    <h3>Email Address</h3>
                                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                                    <small>Your email is used for login and notifications</small>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="info-details">
                                    <h3>Full Name</h3>
                                    <p><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['middle_initial'] ? $user['middle_initial'] . ' ' : '') . $user['last_name']); ?></p>
                                    <small>You can edit this in the Profile section</small>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="info-details">
                                    <h3>Birthday</h3>
                                    <p><?php echo date('F j, Y', strtotime($user['birthday'])); ?></p>
                                    <small>You can edit this in the Profile section</small>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-details">
                                    <h3>Contact Number</h3>
                                    <p><?php echo htmlspecialchars($user['contact_number'] ?? 'Not provided'); ?></p>
                                    <small>You can edit this in the Profile section</small>
                                </div>
                            </div>
                        </div>

                        <div class="account-actions">
                            <div class="action-card danger">
                                <div class="action-icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="action-details">
                                    <h3>Logout</h3>
                                    <p>Sign out of your account on this device</p>
                                    <button id="settingsLogoutBtn" class="btn btn-danger">Logout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-info">
                    <h1 class="footer-logo">Elegante</h1>
                    <p>Experience luxury and comfort in the heart of Manila. Our hotel offers exceptional service, elegant accommodations, and unforgettable experiences.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="quick-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="rooms.php">Our Rooms</a></li>
                        <li><a href="facilities.php">Facilities</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </div>
                <div class="contact-links">
                    <h3>Contact Info</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Masangkay Street, Binondo, Manila</li>
                        <li><i class="fas fa-phone"></i> +63 912 345 6789</li>
                        <li><i class="fas fa-envelope"></i> info@hotelname.com</li>
                        <li><i class="fas fa-clock"></i> 24/7 Open</li>
                    </ul>
                </div>
                <div class="newsletter">
                    <h3>Newsletter</h3>
                    <p>Subscribe to receive special offers and updates</p>
                    <form class="newsletter-form">
                        <div class="form-group">
                            <input type="email" placeholder="Your Email Address" required>
                            <button type="submit" class="btn">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Elegante. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Tab switching
        document.querySelectorAll('.nav-item').forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');

                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
                document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));

                // Add active class to clicked item
                this.classList.add('active');
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Auto-hide message box
        window.onload = function() {
            const msgBox = document.getElementById('alert-message');
            if (msgBox) {
                setTimeout(() => {
                    msgBox.style.opacity = '0';
                    setTimeout(() => msgBox.remove(), 500);
                }, 4000);
            }
        };

        // Set max date for birthday input (16+ requirement)
        const birthdayInput = document.getElementById('birthday');
        if (birthdayInput) {
            const today = new Date();
            const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
            const maxDate16YearsAgo = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());

            // Initialize Flatpickr with 16+ validation
            flatpickr("#birthday", {
                mode: "single",
                dateFormat: "Y-m-d",
                maxDate: maxDate16YearsAgo,
                minDate: minDate,
                yearRange: [1925, new Date().getFullYear() - 16],
                altInput: true,
                altFormat: "F j, Y",
                animate: true,
                theme: "light",
            });
        }

        // Disable numbers on name fields
        const firstNameInput = document.getElementById("first_name");
        const lastNameInput = document.getElementById("last_name");
        const middleInitialInput = document.getElementById("middle_initial");

        // Function to remove numbers from input
        const removeNumbers = (input) => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[0-9]/g, "");
            });
        };

        if (firstNameInput) removeNumbers(firstNameInput);
        if (lastNameInput) removeNumbers(lastNameInput);
        if (middleInitialInput) removeNumbers(middleInitialInput);

        // Disable text on contact number - only numbers allowed
        const contactNumberInput = document.getElementById("contact_number");
        if (contactNumberInput) {
            contactNumberInput.addEventListener("input", function () {
                // Remove all non-digit and non-special characters (keep only numbers, +, spaces)
                this.value = this.value.replace(/[^\d\+\s]/g, "");
            });
        }

        // Handle logout button on settings page
        const settingsLogoutBtn = document.getElementById('settingsLogoutBtn');
        if (settingsLogoutBtn) {
            settingsLogoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = document.getElementById('logoutConfirmationModal');
                if (modal) {
                    modal.classList.add('show');
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        }
    </script>
</body>

</html>