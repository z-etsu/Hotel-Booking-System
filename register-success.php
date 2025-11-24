<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Hotel Name</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
  <style>
    /* --- Custom Styles for Auth Pages --- */
    .auth-container {
      /* Pushes the form down below the fixed navbar */
      padding-top: 100px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: var(--bg-color);
      /* Light background */
      padding-bottom: 4rem;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .auth-form {
      background-color: #ffffff;
      padding: 3rem;
      /* Increased padding */
      border-radius: 10px;
      /* Slightly more rounded */
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      /* Stronger, professional shadow */
      max-width: 550px;
      /* Slightly wider form */
      width: 100%;
    }

    .auth-form h2 {
      color: #111;
      text-align: center;
      margin-bottom: 2rem;
      /* Increased margin */
      font-family: var(--brand-font);
      font-weight: 500;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      font-size: 2rem;
    }

    /* Inherit form group styling from contact section for consistency */
    .auth-form .form-group {
      margin-bottom: 1.5rem;
      /* Increased spacing between fields */
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
      /* Resetting default browser styles */
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

    /* Grid for Name Fields - Stays inside the box */
    .name-fields {
      display: grid;
      grid-template-columns: 2fr 1fr 2fr;
      /* F, MI, L */
      gap: 1.5rem;
      /* Increased gap */
      margin-bottom: 0.5rem;
      /* Adjusted margin since form-group has its own */
    }

    .name-fields .form-group {
      margin-bottom: 0;
    }

    .birthday-label {
      color: #333;
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      font-weight: 500;
    }

    @media (max-width: 600px) {
      .auth-form {
        padding: 2rem;
      }

      /* Stack name fields on mobile to ensure they stay inside the box */
      .name-fields {
        grid-template-columns: 1fr;
        gap: 0;
      }

      .name-fields .form-group {
        margin-bottom: 1.5rem;
      }
    }

    /* Success Modal Styles */
    .success-modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.3s ease-in;
    }

    .success-modal.show {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #ffffff;
      padding: 3rem;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      width: 90%;
      text-align: center;
      animation: slideUp 0.3s ease-out;
    }

    .modal-content .success-icon {
      width: 80px;
      height: 80px;
      background-color: #4CAF50;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 3rem;
      color: white;
    }

    .modal-content h3 {
      color: #111;
      font-size: 1.8rem;
      margin-bottom: 1rem;
      font-family: var(--brand-font);
      font-weight: 500;
      letter-spacing: 0.05em;
    }

    .modal-content p {
      color: #666;
      font-size: 1rem;
      margin-bottom: 2rem;
      line-height: 1.6;
    }

    .modal-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .modal-actions a,
    .modal-actions button {
      padding: 0.9rem 2rem;
      border-radius: 6px;
      font-size: 0.95rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      cursor: pointer;
      text-decoration: none;
      border: none;
      font-family: var(--brand-font);
      transition: all 0.3s ease;
    }

    .modal-actions a.login-btn {
      background-color: var(--accent);
      color: white;
    }

    .modal-actions a.login-btn:hover {
      background-color: #9a6b06;
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @media (max-width: 600px) {
      .modal-content {
        padding: 2rem;
      }

      .modal-content h3 {
        font-size: 1.5rem;
      }

      .modal-actions {
        flex-direction: column;
        gap: 0.8rem;
      }

      .modal-actions a {
        width: 100%;
      }
    }
  </style>
  <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition">
  <header id="navbar" class="scrolled">
    <div class="container">
      <h1 class="logo">Hotel Name</h1>
      <nav>
        <a href="index.php">Home</a>
        <a href="#rooms">Rooms</a>
        <a href="#facilities">Facilities</a>
        <a href="#contact">About</a>
        <a href="login-page.php" style="color: var(--accent);">Login / Register</a>
      </nav>
    </div>
  </header>

  <div class="auth-container">
  </div>

  <!-- Success Modal -->
  <div id="successModal" class="success-modal show">
    <div class="modal-content">
      <div class="success-icon">✓</div>
      <h3>Registration Successful!</h3>
      <p>Your account has been created successfully. You can now log in with your email and password.</p>
      <div class="modal-actions">
        <a href="login-page.php" class="login-btn">Go to Login</a>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-info">
          <h1 class="footer-logo">Hotel Name</h1>
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
        <p>© 2025 Hotel Name. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Show modal immediately on page load
      const successModal = document.getElementById('successModal');
      if (successModal) {
        successModal.classList.add('show');
      }
    });
  </script>
</body>

</html>