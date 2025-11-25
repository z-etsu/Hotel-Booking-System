<?php
session_start();
require_once 'db_connect.php';

// Get room availability data
$roomAvailability = [];
$bookingsQuery = "
    SELECT room_name, COUNT(*) as booked_count
    FROM bookings
    WHERE status = 'active'
    AND check_out >= CURDATE()
    GROUP BY room_name
";
$bookingsResult = $conn->query($bookingsQuery);
if ($bookingsResult) {
    while ($row = $bookingsResult->fetch_assoc()) {
        $roomAvailability[$row['room_name']] = $row['booked_count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms & Suites - Elegante</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="rooms.css">
    <script src="rooms.js" defer></script>
    <script src="script.js?v=<?php echo time(); ?>"></script>
</head>

<body class="page-transition">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section with Slideshow -->
    <section class="rooms-hero">
        <div id="slideshow">
            <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200"
                class="slide active" alt="Luxury Suite" />
            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200"
                class="slide" alt="Presidential Suite" />
            <img src="https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1200"
                class="slide" alt="Royal Suite" />
        </div>
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Our Rooms & Suites</h1>
            <p>Experience luxury and comfort in every stay</p>
        </div>
    </section>

    <!-- Filter Bar -->



    <!-- Category Navigation -->
    <section class="room-categories">
        <div class="container">
            <div class="category-labels">
                <label class="category-label active" data-category="all">
                    <input type="radio" name="category" value="all" checked>
                    <span>All Rooms</span>
                </label>
                <label class="category-label" data-category="standard">
                    <input type="radio" name="category" value="standard">
                    <span>Standard</span>
                </label>
                <label class="category-label" data-category="family">
                    <input type="radio" name="category" value="family">
                    <span>Family</span>
                </label>
                <label class="category-label" data-category="suite">
                    <input type="radio" name="category" value="suite">
                    <span>Suites</span>
                </label>
            </div>
        </div>
    </section>

    <section class="room-listings">
        <div class="container main-content-grid">

            <aside class="room-sidebar">
                <h2>Filter Your Stay</h2>

                <div class="filter-group">
                    <h3>💵 Price Range (per night)</h3>
                    <label class="filter-checkbox"><input type="checkbox" name="price" value="0-5000"> ₱0 - ₱5,000</label>
                    <label class="filter-checkbox"><input type="checkbox" name="price" value="5001-12000"> ₱5,001 - ₱12,000</label>
                    <label class="filter-checkbox"><input type="checkbox" name="price" value="12001-20000"> ₱12,001 - ₱20,000</label>
                    <label class="filter-checkbox"><input type="checkbox" name="price" value="20001-50000"> ₱20,001+</label>
                </div>

                <div class="filter-group">
                    <h3>👨‍👩‍👧‍👦 Max Capacity</h3>
                    <label class="filter-checkbox"><input type="checkbox" name="capacity" value="1"> 1 Person</label>
                    <label class="filter-checkbox"><input type="checkbox" name="capacity" value="2"> 2 People</label>
                    <label class="filter-checkbox"><input type="checkbox" name="capacity" value="3-4"> 3 - 4
                        People</label>
                    <label class="filter-checkbox"><input type="checkbox" name="capacity" value="5+"> 5+ People</label>
                </div>

                <div class="filter-group">
                    <h3>✨ Key Amenities</h3>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Private Balcony">
                        Private Balcony</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Kitchenette">
                        Kitchenette</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Jacuzzi"> Jacuzzi /
                        Bathtub</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Connecting Door">
                        Connecting Rooms</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Butler Service"> Butler
                        Service</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Sofa Bed"> Sofa
                        Bed</label>
                    <label class="filter-checkbox"><input type="checkbox" name="amenity" value="Dining Area"> Dining
                        Area</label>
                </div>

                <button class="btn apply-filters-btn">Apply Filters</button>
                <button class="btn advanced-filters-btn" id="advancedFiltersBtn">⚙️ Advanced Filters</button>
            </aside>

            <div class="room-cards-display">
            </div> <!-- End of room-cards-display -->

        </div>
    </section>

    <!-- Advanced Filter Modal is loaded dynamically from advance-filter.php -->

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
    <script>
        // Pass room availability from PHP to JavaScript
        window.roomAvailability = <?php 
            // Calculate available rooms for each room type
            $roomQuantities = [
                "Single Room" => 12,
                "Double Room" => 15,
                "Twin Room" => 10,
                "Triple Room" => 8,
                "Family Room" => 7,
                "Connected Room" => 5,
                "Executive Suite" => 6,
                "Presidential Suite" => 3,
                "Royal Suite" => 2
            ];
            
            $availability = [];
            foreach ($roomQuantities as $roomName => $totalQuantity) {
                $bookedCount = isset($roomAvailability[$roomName]) ? $roomAvailability[$roomName] : 0;
                $availability[$roomName] = $totalQuantity - $bookedCount;
            }
            echo json_encode($availability);
        ?>;
    </script>
    <script src="script.js"></script>



</body>

</html>