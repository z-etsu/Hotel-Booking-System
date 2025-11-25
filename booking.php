<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title id="room-title">Room Details - Elegante</title>

  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="rooms.css" />
  <link rel="stylesheet" href="booking.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker@latest/dist/litepicker.css">
  <script src="https://cdn.jsdelivr.net/npm/litepicker@latest/dist/litepicker.js"></script>
  <script src="rooms.js"></script>
  <script src="booking.js" defer></script>
  <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition booking-page">
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Store login status for JavaScript -->
  <input type="hidden" id="user-logged-in" value="<?php echo isset($_SESSION['user_email']) ? 'true' : 'false'; ?>">

  <!-- Main booking content -->
  <main class="booking-page-main warm-bg">
    <div class="container">

      <!-- Breadcrumb -->
      <nav class="breadcrumb" aria-label="Breadcrumb" style="margin-top:18px;">
        <a href="index.php">Home</a>
        <span aria-hidden="true">›</span>
        <a href="rooms.php">Rooms</a>
        <span aria-hidden="true">›</span>
        <span id="breadcrumb-room" class="current">Room</span>
      </nav>

      <!-- Page header: Title + short tagline -->
      <header class="room-header" style="margin:18px 0 28px;">
        <h1 id="room-name" class="room-title">Room Name</h1>
        <p id="room-tagline" class="room-tagline">A short, inviting one-line summary of the room experience.</p>
        <p class="room-category-tag" id="room-category" aria-hidden="true"></p>
      </header>

      <!-- Top grid: Gallery (left) + Booking sidebar (right) -->
      <div class="booking-details-grid">

        <!-- LEFT: image gallery and overview -->
        <div class="main-content-column">
          <!-- Image gallery -->
          <section class="image-gallery-side" aria-label="Room photo gallery">
            <div class="gallery-label">Photo Gallery <span class="gallery-count">(3)</span></div>
            <div class="main-image-container" role="listbox" aria-live="polite"></div>
            <div class="gallery-thumbs" aria-hidden="true"></div>
            <button class="nav-arrow prev-arrow" aria-label="Previous photo">◀</button>
            <button class="nav-arrow next-arrow" aria-label="Next photo">▶</button>
          </section>

          <!-- OVERVIEW MOVED HERE -->
          <section class="overview">
            <h2>Overview</h2>
            <p id="room-description">Loading room description…</p>
          </section>
        </div>

        <!-- RIGHT: Sidebar -->
        <aside class="booking-sidebar-compact" aria-label="Booking widget and details">
          <!-- You're booking summary -->
          <div class="booking-summary-card">
            <div class="summary-top">
              <div class="summary-room">
                <div class="summary-room-name" id="summary-room-name">Executive Suite</div>
                <div class="summary-room-cat" id="summary-room-cat">SUITE</div>
              </div>
              <div class="summary-price">
                <div class="summary-from">From</div>
                <div class="summary-value" id="detail-price-small">₱16,800</div>
                <div class="summary-sub">/night</div>
              </div>
            </div>
            <div class="summary-note">Includes complimentary Wi-Fi & daily housekeeping</div>
          </div>

          <!-- Quick facts widget -->
          <div class="key-details-widget">
            <h2>Quick Facts</h2>
            <div class="key-details-grid">
              <div class="detail-item"><strong>Size:</strong> <span id="detail-size"></span></div>
              <div class="detail-item"><strong>Max Capacity:</strong> <span id="detail-capacity"></span></div>
              <div class="detail-item"><strong>Starting Price:</strong> <span id="detail-price"></span></div>
            </div>
          </div>

          <!-- Booking widget (sticky) -->
          <div class="booking-widget" id="booking-widget">
            <h3>Confirm Your Reservation</h3>
            <div class="price-summary">
              <span class="final-price" id="widget-price">₱16,800</span>
              <small>/ per night</small>
            </div>
            <form id="booking-form" autocomplete="off" novalidate>
              <div class="form-group">
                <label for="check-in">Check-in Date</label>
                <input type="date" id="check-in" name="checkin" />
              </div>
              <div class="form-group">
                <label for="check-out">Check-out Date</label>
                <input type="date" id="check-out" name="checkout" />
              </div>
              <div class="form-group">
                <label for="guests">Guests</label>
                <select id="guests" name="guests">
                  <option value="1">1 Guest</option>
                </select>
              </div>
              <button type="button" class="btn book-final-btn" id="book-final-btn">Reserve & Pay</button>
            </form>
            <!-- Trust indicators -->
            <div class="trust-indicators" aria-hidden="true">
              <div class="trust-item">🔒 Secure payment</div>
              <div class="trust-item">✅ Instant confirmation</div>
              <div class="trust-item">📞 24/7 Support</div>
            </div>
            <p class="policy-note">Booking is secured with a 10% non-refundable deposit. Taxes may apply.</p>
          </div>
        </aside>

      </div> <!-- end booking-details-grid -->

      <!-- INFO AREA: Why Guests Love This Room, Full Amenities, Good to Know -->
      <article class="room-text-block full-width-content">

        <!-- Why Guests Love This Room -->
        <section class="room-highlights" style="margin-top:20px;">
          <h2>Why Guests Love This Room</h2>
          <div class="highlights-grid" id="highlights-container">
            <!-- populated by JS -->
          </div>
        </section>

        <!-- Full Amenities -->
        <section class="amenities-section" style="margin-top:24px;">
          <h2>Full Amenities List</h2>
          <ul class="amenities-list" id="full-amenities-list" aria-live="polite">
            <!-- populated by JS -->
          </ul>
        </section>

      </article>
    </div> <!-- container -->
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

  <script>
    // Sync breadcrumb and summary with room data
    (function() {
      const observer = new MutationObserver(() => {
        const rn = document.getElementById('room-name')?.textContent || '';
        if (rn) {
          document.getElementById('breadcrumb-room').textContent = rn;
          const summaryName = document.getElementById('summary-room-name');
          if (summaryName) summaryName.textContent = rn;
        }
        const cat = document.getElementById('room-category')?.textContent || '';
        if (cat) {
          const sCat = document.getElementById('summary-room-cat');
          if (sCat) sCat.textContent = cat;
        }
      });
      observer.observe(document.getElementById('room-name'), {
        childList: true,
        subtree: true
      });
    })();

    // Set footer year
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Dynamic guest dropdown based on room capacity
    function updateGuestOptions(maxCapacity) {
      const guestsSelect = document.getElementById('guests');
      guestsSelect.innerHTML = ''; // Clear existing options

      for (let i = 1; i <= maxCapacity; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i === 1 ? '1 Guest' : `${i} Guests`;
        guestsSelect.appendChild(option);
      }

      // Set default to first option
      guestsSelect.value = 1;
    }

    // Observer to watch for capacity changes
    const capacityObserver = new MutationObserver(() => {
      const capacityText = document.getElementById('detail-capacity')?.textContent || '';
      const capacity = parseInt(capacityText);
      if (capacity && capacity > 0) {
        updateGuestOptions(capacity);
      }
    });

    capacityObserver.observe(document.getElementById('detail-capacity'), {
      childList: true,
      subtree: true
    });

    // Initialize with default capacity if already loaded
    const initialCapacity = document.getElementById('detail-capacity')?.textContent || '';
    if (initialCapacity) {
      const capacity = parseInt(initialCapacity);
      if (capacity > 0) {
        updateGuestOptions(capacity);
      }
    }
  </script>

  <!-- Themed Alert Modal -->
  <div id="themed-alert-modal" class="themed-modal">
    <div class="themed-modal-content">
      <button class="modal-close-btn">&times;</button>
      <div class="modal-icon alert-icon">⚠️</div>
      <h2>Alert</h2>
      <p id="alert-message-content"></p>
      <button id="alert-ok-btn" class="btn btn-modal-primary">OK</button>
    </div>
  </div>

  <!-- Booking Confirmation Modal -->
  <div id="booking-confirmation-modal" class="themed-modal">
    <div class="themed-modal-content">
      <button class="modal-close-btn">&times;</button>
      <div class="modal-icon confirm-icon">✓</div>
      <h2>Confirm Your Reservation</h2>
      <div class="confirmation-details">
        <div class="detail-row">
          <span class="detail-label">Room:</span>
          <span id="confirm-room-name" class="detail-value"></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Duration:</span>
          <span id="confirm-nights" class="detail-value"></span> nights
        </div>
        <div class="detail-row">
          <span class="detail-label">Price per Night:</span>
          <span id="confirm-price-per-night" class="detail-value"></span>
        </div>
        <div class="detail-row highlight">
          <span class="detail-label">Total Price:</span>
          <span id="confirm-total" class="detail-value total-price"></span>
        </div>
      </div>
      <div class="modal-actions">
        <button id="cancel-confirmation-btn" class="btn btn-modal-secondary">Cancel</button>
        <button id="confirm-booking-btn" class="btn btn-modal-primary">Confirm Booking</button>
      </div>
    </div>
  </div>

  <!-- Booking Success Modal -->
  <div id="booking-success-modal" class="themed-modal">
    <div class="themed-modal-content">
      <div class="modal-icon success-icon">✓</div>
      <h2>Booking Confirmed!</h2>
      <p>Your reservation has been successfully created.</p>
      <div class="order-id-box">
        <p class="order-id-label">Your Order ID:</p>
        <p class="order-id-value">#<span id="success-order-id"></span></p>
      </div>
      <p class="redirect-message">Redirecting to your bookings page...</p>
    </div>
  </div>

  <style>
    /* Themed Modal Styles */
    .themed-modal {
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

    .themed-modal.show {
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

    .themed-modal-content {
      background-color: #fdf8f4;
      padding: 40px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
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
        filter: blur(0px);
      }
    }

    .modal-close-btn {
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

    .modal-close-btn:hover {
      color: #333;
    }

    .modal-icon {
      font-size: 56px;
      margin-bottom: 15px;
      display: block;
      height: 60px;
      line-height: 60px;
      text-align: center;
      color: #111;
    }

    .themed-modal-content h2 {
      font-size: 1.6rem;
      color: #111;
      margin-bottom: 15px;
      font-weight: 500;
      letter-spacing: -0.01em;
      font-family: 'Cinzel', 'Georgia', serif;
    }

    .themed-modal-content p {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .confirmation-details {
      background-color: #fff;
      border-radius: 6px;
      padding: 20px;
      margin: 20px 0;
      text-align: left;
      border: 1px solid #e6e1d8;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      font-size: 0.95rem;
    }

    .detail-row:last-child {
      margin-bottom: 0;
    }

    .detail-row.highlight {
      padding-top: 12px;
      border-top: 2px solid #e6e1d8;
    }

    .detail-label {
      color: #666;
      font-weight: 500;
    }

    .detail-value {
      color: #111;
      font-weight: 500;
    }

    .total-price {
      color: #b8860b;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .order-id-box {
      background-color: #fff;
      border: 2px solid #b8860b;
      border-radius: 8px;
      padding: 20px;
      margin: 20px 0;
    }

    .order-id-label {
      font-size: 0.9rem;
      color: #666;
      margin: 0 0 10px 0;
    }

    .order-id-value {
      font-size: 1.8rem;
      color: #b8860b;
      font-weight: 700;
      margin: 0;
      letter-spacing: 2px;
      font-family: 'Courier New', monospace;
    }

    .redirect-message {
      font-size: 0.85rem;
      color: #999;
      font-style: italic;
      margin-top: 15px;
    }

    .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 25px;
      justify-content: center;
    }

    .modal-actions .btn {
      flex: 1;
      max-width: 200px;
    }

    .btn-modal-primary {
      background-color: #532200;
      color: #fff;
      padding: 12px 24px;
      border: none;
      border-radius: 1px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    .btn-modal-primary:hover {
      background-color: #3e1a00;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(83, 34, 0, 0.3);
    }

    .btn-modal-secondary {
      background-color: #ddd;
      color: #333;
      padding: 12px 24px;
      border: none;
      border-radius: 1px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    .btn-modal-secondary:hover {
      background-color: #ccc;
      transform: translateY(-2px);
    }

    @media (max-width: 480px) {
      .themed-modal-content {
        width: 95%;
        padding: 25px;
      }

      .modal-actions {
        flex-direction: column;
      }

      .modal-actions .btn {
        max-width: 100%;
      }
    }
  </style>
</body>

</html>