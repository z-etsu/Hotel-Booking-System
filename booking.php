<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title id="room-title">Room Details - Hotel Name</title>

  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="rooms.css" />
  <link rel="stylesheet" href="booking.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker@latest/dist/litepicker.css">
  <script src="https://cdn.jsdelivr.net/npm/litepicker@latest/dist/litepicker.js"></script>
  <script src="rooms.js"></script>
  <script src="booking.js" defer></script>
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
                <div class="summary-value" id="detail-price-small">$399</div>
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
              <span class="final-price" id="widget-price">$399</span>
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
          <div class="highlights-grid">
            <div class="highlight">
              <strong>Panoramic Views</strong>
              <p>Wake up to stunning city vistas from your private balcony.</p>
            </div>
            <div class="highlight">
              <strong>Spacious Living</strong>
              <p>Separate living area perfect for families or extended stays.</p>
            </div>
            <div class="highlight">
              <strong>Luxury Bathroom</strong>
              <p>Relax in a deep soaking tub or rain shower with premium toiletries.</p>
            </div>
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

  <!-- Optional footer -->
  <footer class="site-footer" aria-hidden="true" style="padding:40px 0; text-align:center; color:#666;">
    © <span id="currentYear"></span> Hotel Name. All rights reserved.
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
      observer.observe(document.getElementById('room-name'), { childList: true, subtree: true });
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

    capacityObserver.observe(document.getElementById('detail-capacity'), { childList: true, subtree: true });

    // Initialize with default capacity if already loaded
    const initialCapacity = document.getElementById('detail-capacity')?.textContent || '';
    if (initialCapacity) {
      const capacity = parseInt(initialCapacity);
      if (capacity > 0) {
        updateGuestOptions(capacity);
      }
    }
  </script>
</body>
</html>
