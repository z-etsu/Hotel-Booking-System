<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Elegante</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
  <style>
    /* --- Custom Styles for Auth Pages (Re-using the same theme) --- */
    .auth-container {
      padding-top: 100px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: var(--bg-color);
      padding-bottom: 4rem;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .auth-form {
      background-color: #ffffff;
      padding: 3rem;
      border-radius: 10px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      max-width: 450px;
      /* Standard width for login form */
      width: 100%;
    }

    .auth-form h2 {
      color: #111;
      text-align: center;
      margin-bottom: 2rem;
      font-family: var(--brand-font);
      font-weight: 500;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      font-size: 2rem;
    }

    .auth-form .form-group {
      margin-bottom: 1.5rem;
    }

    .auth-form input {
      width: 100%;
      padding: 1rem 1.25rem;
      /* Bigger padding for larger inputs */
      border: 2px solid #e0e0e0;
      /* Thicker, defined border */
      border-radius: 6px;
      font-size: 1rem;
      /* Larger font size */
      transition: border-color 0.3s, box-shadow 0.3s;
      color: #333;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }

    /* Professional input focus state */
    .auth-form input:focus {
      outline: none;
      border-color: var(--accent);
      /* Accent color border */
      box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.2);
      /* Soft glow */
      background-color: #fff;
    }

    .auth-form input::placeholder {
      color: #999;
    }

    .auth-form .btn {
      width: 100%;
      padding: 1.1rem;
      /* Bigger button */
      font-size: 1.1rem;
      background-color: var(--accent);
      border: none;
      cursor: pointer;
      font-family: var(--brand-font);
      border-radius: 6px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 500;
    }

    .auth-form .btn:hover {
      background-color: #9a6b06;
    }

    .auth-form p {
      text-align: center;
      margin-top: 2rem;
      color: #666;
      font-size: 1rem;
    }

    .auth-form a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }

    .auth-form a:hover {
      color: #9a6b06;
    }

    /* Password Toggle Styles */
    .password-group {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.2rem;
      color: #666;
      transition: color 0.3s ease;
      background: none;
      border: none;
      padding: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .password-toggle:hover {
      color: var(--accent);
    }

    .password-group input[type="password"],
    .password-group input[type="text"] {
      padding-right: 3rem;
    }

    /* Error/Success Message Styles */
    .error-message {
      background-color: #f8d7da;
      border: 2px solid #f5c6cb;
      color: #721c24;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1.5rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.8rem;
      animation: slideDown 0.3s ease-out;
    }

    .error-message::before {
      content: "⚠";
      font-size: 1.3rem;
      flex-shrink: 0;
    }

    .success-message {
      background-color: #d4edda;
      border: 2px solid #c3e6cb;
      color: #155724;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1.5rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.8rem;
      animation: slideDown 0.3s ease-out;
    }

    .success-message::before {
      content: "✓";
      font-size: 1.3rem;
      flex-shrink: 0;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 500px) {
      .auth-form {
        padding: 2rem;
      }
    }
  </style>
  <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition">
  <header id="navbar" class="scrolled">
    <div class="container">
      <h1 class="logo">Elegante</h1>
      <nav>
        <a href="index.php">Home</a>
        <a href="rooms.php">Rooms</a>
        <a href="facilities.php">Facilities</a>
        <a href="about.php">About</a>
        <a href="login-page.php" style="color: var(--accent);">Login / Register</a>
      </nav>
    </div>
  </header>

  <div class="auth-container">
    <div style="width: 100%; max-width: 450px;">
      <!-- Display error or success messages -->
      <?php if (isset($_SESSION['message'])): ?>
        <div class="<?php echo ($_SESSION['message_type'] === 'error') ? 'error-message' : 'success-message'; ?>">
          <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
      <?php endif; ?>

      <!-- ACTION and METHOD added here -->
      <form class="auth-form" id="loginForm" action="login.php" method="POST">
        <h2>Login to Your Account</h2>

        <div class="form-group">
          <!-- NAME attribute added -->
          <input type="email" id="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="form-group password-group">
          <!-- NAME attribute added -->
          <input type="password" id="password" name="password" placeholder="Password" required>
          <button type="button" class="password-toggle" id="togglePassword">
            <span id="toggleIcon">👁️</span>
          </button>
        </div>

        <button type="submit" class="btn">Login</button>

        <p>
          Don't have an account? <a href="register.html">Register now</a>.
        </p>
      </form>
    </div>
  </div>

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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-hide error messages after 5 seconds
      const errorMessages = document.querySelectorAll('.error-message');
      errorMessages.forEach(function(msg) {
        setTimeout(() => {
          msg.style.opacity = '0';
          msg.style.transition = 'opacity 0.3s ease-out';
          setTimeout(() => msg.remove(), 300);
        }, 5000);
      });

      // Password toggle functionality
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');

      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function(e) {
          e.preventDefault();
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          toggleIcon.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
      }
    });
  </script>
</body>

</html>