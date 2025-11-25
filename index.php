<?php
session_start(); // **START SESSION: Must be at the very top**
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Booking System</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>" />

  <style>
    .message-box {
      position: fixed;
      top: 80px; /* Below the navbar */
      left: 50%;
      transform: translateX(-50%);
      padding: 15px 30px;
      border-radius: 5px;
      z-index: 1000;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }
    .message-box.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .message-box.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .message-box.show {
        opacity: 1;
    }
  </style>
</head>

<body class="page-transition">
  <?php if (isset($_SESSION['message'])): ?>
    <div id="alert-message" class="message-box <?php echo $_SESSION['message_type']; ?> show">
        <?php echo $_SESSION['message']; ?>
    </div>
    <?php 
    // Clear the session message immediately after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>
  <?php endif; ?>

  <!-- Include the navbar -->
  <?php include 'navbar.php'; ?>

  <section class="hero">
    <div id="slideshow">
      <img src="slide2.jpg" class="slide active" />
      <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b" class="slide" />
      <img src="slide3.jpg" class="slide" />
    </div>

    <div class="overlay"></div>

    <div class="hero-content">
      <h2 id="quote">Feel Relaxed & Enjoy Your Luxuriousness</h2>
      <a href="#rooms" class="btn">See Our Rooms</a>
    </div>
  </section>



  <section id="rooms" class="rooms">
    <div class="container">
      <div class="section-title">
        <p class="subtitle">Hotel & Spa Adina</p>
        <h2>Room & Suites</h2>
      </div>

      <h3 class="category-title">Standard Rooms</h3>
      <div class="rooms-grid">
        <article class="room-card">
          <div class="card-image">
            <img src="images/singleroom1.jpg" alt="Single Room" />
            <div class="room-badge">
              <span class="badge-item">Size 25m²</span>
              <span class="badge-item">Max People 1</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Single Room</h3>
            <p>Cozy room perfect for solo travelers with essential amenities.</p>
            <a class="btn book-btn" href="booking.php?room=single-room">Book Now from $89</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/double1.jpg" alt="Double Room" />
            <div class="room-badge">
              <span class="badge-item">Size 30m²</span>
              <span class="badge-item">Max People 2</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Double Room</h3>
            <p>Comfortable room with a double bed and modern amenities.</p>
            <a class="btn book-btn" href="booking.php?room=double-room">Book Now from $129</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/twin1.jpg" alt="Twin Room" />
            <div class="room-badge">
              <span class="badge-item">Size 35m²</span>
              <span class="badge-item">Max People 2</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Twin Room</h3>
            <p>Spacious room with two single beds, perfect for sharing.</p>
            <a class="btn book-btn" href="booking.php?room=twin-room">Book Now from $139</a>
          </div>
        </article>
      </div>

      <h3 class="category-title">Family Rooms</h3>
      <div class="rooms-grid">
        <article class="room-card">
          <div class="card-image">
            <img src="images/triple1.jpg" alt="Triple Room" />
            <div class="room-badge">
              <span class="badge-item">Size 40m²</span>
              <span class="badge-item">Max People 3</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Triple Room</h3>
            <p>Spacious room with three single beds or one double and one single bed.</p>
            <a class="btn book-btn" href="booking.php?room=triple-room">Book Now from $189</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/family1.jpg" alt="Family Room" />
            <div class="room-badge">
              <span class="badge-item">Size 45m²</span>
              <span class="badge-item">Max People 4</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Family Room</h3>
            <p>Perfect for families with two double beds and extra living space.</p>
            <a class="btn book-btn" href="booking.php?room=family-room">Book Now from $229</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/connected1.jpg" alt="Connected Room" />
            <div class="room-badge">
              <span class="badge-item">Size 50m²</span>
              <span class="badge-item">Max People 4</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Connected Room</h3>
            <p>Two interconnected rooms perfect for families needing extra privacy.</p>
            <a class="btn book-btn" href="booking.php?room=connected-room">Book Now from $249</a>
          </div>
        </article>
      </div>

      <h3 class="category-title">Luxury Suites</h3>
      <div class="rooms-grid">
        <article class="room-card">
          <div class="card-image">
            <img src="images/executive2.png" alt="Executive Suite" />
            <div class="room-badge">
              <span class="badge-item">Size 70m²</span>
              <span class="badge-item">Max People 5</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Executive Suite</h3>
            <p>Luxurious suite with separate living room and premium amenities.</p>
            <a class="btn book-btn" href="booking.php?room=executive-suite">Book Now from $299</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/presidential1.avif" alt="Presidential Suite" />
            <div class="room-badge">
              <span class="badge-item">Size 90m²</span>
              <span class="badge-item">Max People 6</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Presidential Suite</h3>
            <p>Our finest suite with panoramic views and exclusive services.</p>
            <a class="btn book-btn" href="booking.php?room=presidential-suite">Book Now from $399</a>
          </div>
        </article>

        <article class="room-card">
          <div class="card-image">
            <img src="images/royal1.jpg" alt="Royal Suite" />
            <div class="room-badge">
              <span class="badge-item">Size 100m²</span>
              <span class="badge-item">Max People 6</span>
            </div>
          </div>
          <div class="card-content">
            <h3>Royal Suite</h3>
            <p>Ultimate luxury with multiple rooms and private terrace.</p>
            <a class="btn book-btn" href="booking.php?room=royal-suite">Book Now from $449</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section id="facilities" class="facilities">
    <div class="container">
      <div class="section-title">
        <p class="subtitle">What We Offer</p>
        <h2>Room Features & Amenities</h2>
      </div>

      <div class="facilities-grid">
        <div class="facility-card">
          <div class="facility-image">
            <img src="facilities_img/prem.png" alt="Premium Bedding" />
            <div class="facility-overlay"></div>
          </div>
          <div class="facility-content">
            <h3>Premium Bedding</h3>
            <p>Luxurious Egyptian cotton sheets and hypoallergenic pillows</p>
            <a href="facilities.php" class="facility-link">Details</a>
          </div>
        </div>

        <div class="facility-card">
          <div class="facility-image">
            <img src="facilities_img/tv.jpg" alt="Smart Entertainment" />
            <div class="facility-overlay"></div>
          </div>
          <div class="facility-content">
            <h3>Smart Entertainment</h3>
            <p>Modern TV with streaming services and cable channels</p>
            <a href="facilities.php" class="facility-link">Details</a>
          </div>
        </div>

        <div class="facility-card">
          <div class="facility-image">
            <img src="facilities_img/wifi.avif" alt="High-Speed WiFi" />
            <div class="facility-overlay"></div>
          </div>
          <div class="facility-content">
            <h3>High-Speed WiFi</h3>
            <p>Complimentary internet throughout the room</p>
            <a href="facilities.php" class="facility-link">Details</a>
          </div>
        </div>

        <div class="facility-card">
          <div class="facility-image">
            <img src="facilities_img/luxurybath.jpg" alt="Luxury Bathroom" />
            <div class="facility-overlay"></div>
          </div>
          <div class="facility-content">
            <h3>Luxury Bathroom</h3>
            <p>Premium toiletries and rainfall shower</p>
            <a href="facilities.php" class="facility-link">Details</a>
          </div>
        </div>

        <div class="facility-card">
          <div class="facility-image">
            <img src="facilities_img/bevbar.webp" alt="In-Room Bar" />
            <div class="facility-overlay"></div>
          </div>
          <div class="facility-content">
            <h3>In-Room Beverage Bar</h3>
            <p>Mini fridge, coffee maker, and 24/7 refreshments</p>
            <a href="facilities.php" class="facility-link">Details</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="testimonials" class="testimonials">
    <div class="container">
      <div class="section-title">
        <h2>TESTIMONIALS</h2>
      </div>

      <div class="testimonials-slider">
        <button class="nav-arrow prev-arrow"><</button>
        <button class="nav-arrow next-arrow">></button>

        <div class="testimonials-track">
          <div class="testimonial-card">
            <div class="testimonial-profile">
              <img src="https://i.pravatar.cc/150?img=1" alt="Customer 1" class="profile-img">
              <h3>Harvey Poge</h3>
            </div>
            <p class="testimonial-text">
              My stay at Elegante was absolutely incredible! The service was impeccable, the rooms were luxurious and immaculate, and the attention to detail was extraordinary. I felt completely pampered throughout my visit. Highly recommend!
            </p>
            <div class="rating">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-profile">
              <img src="https://i.pravatar.cc/150?img=2" alt="Customer 2" class="profile-img">
              <h3>Jhon Gundam</h3>
            </div>
            <p class="testimonial-text">
              The Presidential Suite exceeded all my expectations. Every amenity was thoughtfully provided, from the premium toiletries to the stunning panoramic views. The staff went above and beyond to make our anniversary celebration unforgettable. We'll definitely return!
            </p>
            <div class="rating">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-profile">
              <img src="https://i.pravatar.cc/150?img=3" alt="Customer 3" class="profile-img">
              <h3>Jasher Maangas</h3>
            </div>
            <p class="testimonial-text">
              Perfect location with world-class accommodations. The Family Room was spacious and comfortable for all of us. The kids loved the safe and friendly environment. Excellent dining options and the front desk staff was incredibly helpful. Worth every penny!
            </p>
            <div class="rating">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-profile">
              <img src="https://i.pravatar.cc/150?img=4" alt="Customer 4" class="profile-img">
              <h3>Jaspher Aton</h3>
            </div>
            <p class="testimonial-text">
              This is my third stay and it keeps getting better! The attention to detail is remarkable. The concierge helped arrange everything I needed for my business meetings. The room amenities and Wi-Fi speed are exceptional. Management truly cares about guest satisfaction.
            </p>
            <div class="rating">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="testimonial-profile">
              <img src="https://i.pravatar.cc/150?img=5" alt="Customer 5" class="profile-img">
              <h3>Kurt Loverboy123</h3>
            </div>
            <p class="testimonial-text">
              Absolutely stunning property with impeccable service. The luxury is evident in every corner, from the elegant lobby to the sophisticated room design. Breakfast was delicious and the spa facilities were divine. This is truly a gem for anyone seeking a premium hotel experience.
            </p>
            <div class="rating">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>
        </div>

        <div class="slider-nav">
          <button class="nav-dot active"></button>
          <button class="nav-dot"></button>
          <button class="nav-dot"></button>
          <button class="nav-dot"></button>
          <button class="nav-dot"></button>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Initialize testimonials carousel - ensure first card is visible
    document.addEventListener('DOMContentLoaded', () => {
      const firstCard = document.querySelector('.testimonial-card');
      const firstDot = document.querySelector('.nav-dot');
      if (firstCard && firstDot) {
        firstCard.classList.add('active');
        firstDot.classList.add('active');
      }
    });
  </script>

  <section id="contact" class="contact">
    <div class="container">
      <div class="section-title">
        <p class="subtitle">Get in Touch</p>
        <h2>Contact Us</h2>
      </div>
      
      <div class="contact-grid">
        <div class="contact-info">
          <div class="info-item">
            <h3>Location</h3>
            <p><i class="fas fa-map-marker-alt"></i> 123 Masangkay Street, Binondo, Manila</p>
          </div>
          <div class="info-item">
            <h3>Phone</h3>
            <p><i class="fas fa-phone"></i> +63 912 345 6789</p>
          </div>
          <div class="info-item">
            <h3>Email</h3>
            <p><i class="fas fa-envelope"></i> info@hotelname.com</p>
          </div>
          <div class="map">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.2222483619123!2d120.97288427586673!3d14.583197177125003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ca21c6d57d6f%3A0x6f05cad108533e4a!2sMasangkay%20St%2C%20Binondo%2C%20Manila%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1699519129061!5m2!1sen!2sph" 
              width="100%" 
              height="300" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>

        <form class="contact-form">
          <div class="form-group">
            <input type="text" placeholder="Your Name" required>
          </div>
          <div class="form-group">
            <input type="email" placeholder="Your Email" required>
          </div>
          <div class="form-group">
            <input type="text" placeholder="Subject">
          </div>
          <div class="form-group">
            <textarea placeholder="Message" required rows="5"></textarea>
          </div>
          <button type="submit" class="btn">Send Message</button>
        </form>
      </div>
    </div>
  </section>

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
            <li><a href="rooms.php">Our Rooms</a></li>
            <li><a href="facilities.php">Facilities</a></li>
            <li><a href="index.php#testimonials">Testimonials</a></li>
            <li><a href="about.php">About Us</a></li>
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
  <script src="script.js?v=<?php echo time(); ?>"></script>

  <script>
    window.onload = function() {
        const msgBox = document.getElementById('alert-message');
        if (msgBox) {
            setTimeout(() => {
                msgBox.style.opacity = '0';
                setTimeout(() => msgBox.remove(), 500); // Remove after fade out
            }, 4000); 
        }
    };
  </script>
</body>
</html>