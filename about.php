<?php
session_start(); // Ensure session is available for navbar and messages
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Elegante</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="about.css" />
    <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition">

    <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="message-box <?php echo htmlspecialchars($_SESSION['message_type'] ?? 'success'); ?> show">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php include 'navbar.php'; ?>

    <section class="about-hero">
        <div class="overlay"></div>
        <div class="hero-inner">
            <h1>About Elegante</h1>
            <p>Blending warm Filipino hospitality with modern luxury, Elegante has been welcoming guests since 2012. Our commitment is to create memorable stays where comfort, care and attention to detail come standard.</p>
            <div class="about-hero-buttons">
                <a class="btn" href="rooms.php">Explore Rooms</a>
                <a class="btn btn-light" href="#contact">Contact Us</a>
            </div>
        </div>
    </section>

    <main class="about-main">
        <section class="about-section">
            <div class="section-title">
                <p class="subtitle">Our Story</p>
                <h2>Warmth, Luxury & Local Character</h2>
            </div>
            <div class="story-content">
                <div class="story-text">
                    <p>Founded by a family of travelers and hospitality professionals, Elegante began as a small boutique property and has grown into a full-service hotel while keeping its original values intact: personalized service, thoughtful design, and a love for our neighborhood. We pride ourselves on authentic service informed by local culture and modern comforts.</p>
                </div>
                <div class="story-image">
                    <img src="images/presidential2.avif" alt="Hotel lobby" />
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="section-title">
                <p class="subtitle">Mission & Vision</p>
                <h2>Our Promise To Guests</h2>
            </div>
            <div class="mission-vision-wrapper">
                <div class="mission-card">
                    <h3>Mission</h3>
                    <p>To provide warm, attentive service and comfortable, beautifully designed spaces that celebrate local culture while delivering modern convenience.</p>
                </div>
                <div class="vision-card">
                    <h3>Vision</h3>
                    <p>To be recognized as the preferred boutique hotel for guests seeking genuine hospitality and memorable experiences in Manila.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="section-title">
                <p class="subtitle">Meet The Team</p>
                <h2>People Who Make It Happen</h2>
            </div>
            <div class="team-grid">
                <div class="team-member">
                    <img src="images/ATUN_2X2_2.png" alt="General Manager" />
                    <h4>Jaspher Atun</h4>
                    <p>General Manager</p>
                </div>
                <div class="team-member">
                    <img src="images/kurt_1x1.png" alt="Head Chef" />
                    <h4>Kurt Jerald Emba</h4>
                    <p>Head Chef</p>
                </div>
                <div class="team-member">
                    <img src="images/jbetlog1x1.jpg" alt="Guest Relations" />
                    <h4>Jhon Benedict Gundan</h4>
                    <p>Guest Relations</p>
                </div>
                <div class="team-member">
                    <img src="images/jashertite.jpg" alt="Director of Sales" />
                    <h4>Jaspher Palangue</h4>
                    <p>Director of Sales</p>
                </div>
                <div class="team-member">
                    <img src="images/sdsdq11.png" alt="Hotdog" />
                    <h4>Elijah Gracio</h4>
                    <p>Hotdog</p>
                </div>
          
            </div>
        </section>

        <section class="about-section">
            <div class="section-title">
                <p class="subtitle">Our Values</p>
                <h2>Committed To Care</h2>
            </div>
            <div class="values-grid">
                <div>
                    <h4>Hospitality</h4>
                    <p>We treat every guest like family and value thoughtful service.</p>
                </div>
                <div>
                    <h4>Quality</h4>
                    <p>We maintain consistent standards across rooms, facilities, and experiences.</p>
                </div>
                <div>
                    <h4>Community</h4>
                    <p>We support local suppliers and celebrate our neighborhood's heritage.</p>
                </div>
            </div>
        </section>
    </main>

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
                    </div>
                </div>

                <div class="quick-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="rooms.php">Our Rooms</a></li>
                        <li><a href="facilities.php">Facilities</a></li>
                        <li><a href="booking.php">Book Now</a></li>
                    </ul>
                </div>

                <div class="contact-links">
                    <h3>Contact Info</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Masangkay Street, Binondo, Manila</li>
                        <li><i class="fas fa-phone"></i> +63 912 345 6789</li>
                        <li><i class="fas fa-envelope"></i> info@hotelname.com</li>
                    </ul>
                </div>

                <div class="newsletter">
                    <h3>Newsletter</h3>
                    <p>Subscribe to receive special offers and updates</p>
                    <form class="newsletter-form">
                        <div class="form-group">
                            <input type="email" placeholder="Your email" />
                        </div>
                        <button class="btn" type="button">Subscribe</button>
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

    <script>
        window.onload = function() {
            const msgBox = document.getElementById('alert-message');
            if (msgBox) {
                setTimeout(() => {
                    msgBox.style.opacity = '0';
                    setTimeout(() => msgBox.remove(), 500);
                }, 4000);
            }
        };
    </script>
</body>

</html>