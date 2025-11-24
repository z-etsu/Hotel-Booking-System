<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities - Hotel Name</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
    <style>
        /* Facilities Page Specific Styles */
        .facilities-hero {
            background: linear-gradient(135deg, #2c1810 0%, #5d4037 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
            margin-top: 0;
            padding-top: 120px;
        }

        .facilities-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-family: var(--brand-font);
            letter-spacing: 0.1em;
        }

        .facilities-hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.95;
        }

        .facilities-section {
            padding: 40px 20px;
            background-color: #f9f7f4;
        }

        .facilities-section h2 {
            font-size: 2.3rem;
            color: #2c1810;
            text-align: center;
            margin-bottom: 30px;
            font-family: var(--brand-font);
            letter-spacing: 0.05em;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .facilities-grid.business-grid {
            grid-template-columns: repeat(3, 1fr);
            max-width: 1000px;
        }

        .facility-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            
        }

        .facility-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .facility-card-icon {
            width: 100%;
            height: 320px;
            background: linear-gradient(135deg, #b8860b 0%, #daa520 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
            background-size: cover;
            background-position: center;
        }

        .facility-card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .facility-card h3 {
            color: #2c1810;
            font-size: 1.4rem;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .facility-card p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .facility-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .facility-card li {
            color: #555;
            padding: 3px 0;
            padding-left: 20px;
            position: relative;
            font-size: 0.85rem;
        }

        .facility-card li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #b8860b;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .amenities-section {
            background-color: #ffffff;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .amenity-item {
            padding: 20px;
            background-color: #f9f7f4;
            border-radius: 10px;
            border-left: 4px solid #b8860b;
            transition: all 0.3s ease;
        }

        .amenity-item:hover {
            background-color: #efe9e3;
            transform: translateX(5px);
        }

        .amenity-item strong {
            color: #2c1810;
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .amenity-item p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .contact-section {
            background: linear-gradient(135deg, #2c1810 0%, #5d4037 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .contact-section h2 {
            color: white;
            margin-bottom: 30px;
        }

        .contact-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .contact-btn {
            padding: 12px 30px;
            border: 2px solid white;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 600;
            cursor: pointer;
        }

        .contact-btn:hover {
            background-color: white;
            color: #2c1810;
        }

        .contact-btn.primary {
            background-color: #b8860b;
            border-color: #b8860b;
        }

        @media (max-width: 768px) {
            .facilities-hero h1 {
                font-size: 2.2rem;
            }

            .facilities-grid {
                grid-template-columns: 1fr;
            }

            .facilities-grid.business-grid {
                grid-template-columns: 1fr;
            }

            .amenities-grid {
                grid-template-columns: 1fr;
            }

            .contact-buttons {
                flex-direction: column;
            }

            .contact-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body class="page-transition">
    <!-- Include the navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Facilities Hero Section -->
    <section class="facilities-hero">
        <div class="container">
            <h1>Our World-Class Facilities</h1>
            <p>Experience luxury at every corner with our comprehensive amenities designed for your comfort and convenience</p>
        </div>
    </section>

    <!-- Dining & Beverages -->
    <section class="facilities-section">
        <div class="container">
            <h2>Dining & Beverages</h2>
            <div class="facilities-grid">
                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/fine-dining.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Fine Dining Restaurant</h3>
                        <p>Our award-winning restaurant offers international cuisine prepared by world-class chefs using the finest ingredients.</p>
                        <ul>
                            <li>À la carte menu</li>
                            <li>Wine pairing selection</li>
                            <li>Open for breakfast, lunch & dinner</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Casual Café & Bistro</h3>
                        <p>Perfect for a quick bite or relaxing coffee break with friends and family in a comfortable atmosphere.</p>
                        <ul>
                            <li>Light meals & snacks</li>
                            <li>Premium coffee & pastries</li>
                            <li>Outdoor seating available</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/bar.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Bar & Lounge</h3>
                        <p>Unwind at our sophisticated bar with an extensive selection of cocktails, wines, and spirits.</p>
                        <ul>
                            <li>Signature cocktails</li>
                            <li>Live music entertainment</li>
                            <li>Private VIP lounge</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/room-services.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Room Service</h3>
                        <p>24/7 in-room dining service with full menu delivery to your room whenever you need it.</p>
                        <ul>
                            <li>24-hour availability</li>
                            <li>Full restaurant menu</li>
                            <li>Quick delivery service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wellness & Recreation -->
    <section class="facilities-section" style="background-color: #ffffff;">
        <div class="container">
            <h2>Wellness & Recreation</h2>
            <div class="facilities-grid">
                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Fitness Center</h3>
                        <p>State-of-the-art gym with modern equipment, personal trainers, and wellness programs available.</p>
                        <ul>
                            <li>Cardio & strength equipment</li>
                            <li>Personal training sessions</li>
                            <li>Early morning classes</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/swimming-pool.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Swimming Pool</h3>
                        <p>Olympic-sized heated pool perfect for swimming, relaxation, and water activities throughout the year.</p>
                        <ul>
                            <li>Indoor & outdoor pools</li>
                            <li>Heated water</li>
                            <li>Swim lessons available</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/spa.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Spa & Massage</h3>
                        <p>Rejuvenate your mind and body with our professional spa treatments and therapeutic massage services.</p>
                        <ul>
                            <li>Full-body massage</li>
                            <li>Facial treatments</li>
                            <li>Aromatherapy sessions</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Yoga & Meditation</h3>
                        <p>Find inner peace with daily yoga and meditation classes led by certified instructors.</p>
                        <ul>
                            <li>Morning & evening classes</li>
                            <li>Beginner to advanced levels</li>
                            <li>Private sessions available</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business & Meetings -->
    <section class="facilities-section">
        <div class="container">
            <h2>Business & Meetings</h2>
            <div class="facilities-grid business-grid">
                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Conference Rooms</h3>
                        <p>Fully equipped meeting spaces for corporate events, presentations, and business conferences.</p>
                        <ul>
                            <li>Capacity up to 200 guests</li>
                            <li>AV equipment included</li>
                            <li>High-speed internet</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Banquet Halls</h3>
                        <p>Elegant spaces for corporate events, galas, and celebrations with full catering services.</p>
                        <ul>
                            <li>Multiple hall sizes</li>
                            <li>Professional catering</li>
                            <li>Event coordination included</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Business Center</h3>
                        <p>Dedicated workspace with all necessary facilities for productive business operations.</p>
                        <ul>
                            <li>Private office spaces</li>
                            <li>Printing & copying services</li>
                            <li>Secure internet connection</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Guest Services -->
    <section class="facilities-section" style="background-color: #ffffff;">
        <div class="container">
            <h2>Guest Services</h2>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <strong>24/7 Front Desk</strong>
                    <p>Our dedicated staff is available round the clock to assist with any inquiries or requests.</p>
                </div>
                <div class="amenity-item">
                    <strong>Concierge Services</strong>
                    <p>Expert recommendations for dining, attractions, transportation, and special arrangements.</p>
                </div>
                <div class="amenity-item">
                    <strong>Luggage Storage</strong>
                    <p>Secure storage for your luggage before check-in or after check-out.</p>
                </div>
                <div class="amenity-item">
                    <strong>Housekeeping</strong>
                    <p>Daily room cleaning and turndown service for your comfort and convenience.</p>
                </div>
                <div class="amenity-item">
                    <strong>Laundry Service</strong>
                    <p>Professional laundry and dry cleaning services with quick turnaround times.</p>
                </div>
                <div class="amenity-item">
                    <strong>Wake-up Call Service</strong>
                    <p>Reliable wake-up calls to ensure you never miss an important appointment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Entertainment & Recreation -->
    <section class="facilities-section">
        <div class="container">
            <h2>Entertainment & Recreation</h2>
            <div class="facilities-grid">
                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/kids-club.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Kids Club</h3>
                        <p>Safe and fun environment for children with supervised activities and entertainment programs.</p>
                        <ul>
                            <li>Age-appropriate activities</li>
                            <li>Trained staff supervision</li>
                            <li>Daily schedule</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=500&h=300&fit=crop');"></div>
                    <div class="facility-card-content">
                        <h3>Game Room</h3>
                        <p>Entertainment space with billiards, table tennis, arcade games, and more for all ages.</p>
                        <ul>
                            <li>Various gaming options</li>
                            <li>Family-friendly</li>
                            <li>Open daily</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/library.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Library & Reading Room</h3>
                        <p>Quiet retreat with an extensive collection of books and comfortable seating areas.</p>
                        <ul>
                            <li>Large book collection</li>
                            <li>Peaceful atmosphere</li>
                            <li>WiFi available</li>
                        </ul>
                    </div>
                </div>

                <div class="facility-card">
                    <div class="facility-card-icon" style="background-image: url('facilities_img/movie-theater.jpg');"></div>
                    <div class="facility-card-content">
                        <h3>Movie Theater</h3>
                        <p>Private screening room with comfortable seating and latest films available for viewing.</p>
                        <ul>
                            <li>Latest movie releases</li>
                            <li>Premium sound system</li>
                            <li>Snacks available</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Amenities -->
    <section class="facilities-section" style="background-color: #ffffff;">
        <div class="container">
            <h2>Room Amenities</h2>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <strong>High-Speed WiFi</strong>
                    <p>Complimentary premium internet throughout your room and all hotel premises.</p>
                </div>
                <div class="amenity-item">
                    <strong>Smart TV with Cable</strong>
                    <p>55" Smart TV with streaming services and international channels.</p>
                </div>
                <div class="amenity-item">
                    <strong>Air Conditioning</strong>
                    <p>Individual climate control to maintain your ideal room temperature.</p>
                </div>
                <div class="amenity-item">
                    <strong>Premium Bedding</strong>
                    <p>Egyptian cotton sheets, luxury pillows, and high-quality mattresses for restful sleep.</p>
                </div>
                <div class="amenity-item">
                    <strong>Marble Bathroom</strong>
                    <p>Rain shower, soaking tub, luxury toiletries, and premium towels.</p>
                </div>
                <div class="amenity-item">
                    <strong>Work Desk</strong>
                    <p>Ergonomic workspace with desk lamp, power outlets, and comfortable chair.</p>
                </div>
                <div class="amenity-item">
                    <strong>Mini Bar & Safe</strong>
                    <p>Complimentary refreshments and secure safe for valuables.</p>
                </div>
                <div class="amenity-item">
                    <strong>In-Room Entertainment</strong>
                    <p>Sound system, streaming services, and extensive entertainment options.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Accessibility Features -->
    <section class="facilities-section">
        <div class="container">
            <h2>Accessibility & Special Features</h2>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <strong>Wheelchair Accessible Rooms</strong>
                    <p>Specially designed rooms with wheelchair access, grab bars, and accessible bathrooms.</p>
                </div>
                <div class="amenity-item">
                    <strong>Elevators & Ramps</strong>
                    <p>Multiple elevators and ramps throughout the hotel for easy accessibility.</p>
                </div>
                <div class="amenity-item">
                    <strong>Accessible Parking</strong>
                    <p>Dedicated wheelchair-accessible parking spaces near the entrance.</p>
                </div>
                <div class="amenity-item">
                    <strong>Pet-Friendly Policy</strong>
                    <p>Welcome your furry friends with our pet-friendly rooms and amenities.</p>
                </div>
                <div class="amenity-item">
                    <strong>Smoke-Free Rooms</strong>
                    <p>Dedicated non-smoking rooms throughout the hotel for health-conscious guests.</p>
                </div>
                <div class="amenity-item">
                    <strong>Secure Parking</strong>
                    <p>24-hour monitored parking garage with security cameras and valet service.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Green Initiatives -->
    <section class="facilities-section" style="background-color: #ffffff;">
        <div class="container">
            <h2>Sustainability & Green Initiatives</h2>
            <div class="amenities-grid">
                <div class="amenity-item">
                    <strong>Energy Efficient Systems</strong>
                    <p>LED lighting, smart HVAC systems, and solar panels to reduce energy consumption.</p>
                </div>
                <div class="amenity-item">
                    <strong>Water Conservation</strong>
                    <p>Low-flow fixtures and rainwater harvesting systems throughout the hotel.</p>
                </div>
                <div class="amenity-item">
                    <strong>Eco-Friendly Materials</strong>
                    <p>Recycled and sustainable materials used in construction and furnishings.</p>
                </div>
                <div class="amenity-item">
                    <strong>Waste Management</strong>
                    <p>Comprehensive recycling and composting programs to minimize waste.</p>
                </div>
                <div class="amenity-item">
                    <strong>Green Procurement</strong>
                    <p>Locally sourced products and sustainable suppliers for all hotel operations.</p>
                </div>
                <div class="amenity-item">
                    <strong>Environmental Certification</strong>
                    <p>Certified green hotel with commitment to environmental sustainability.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h2>Interested in Our Facilities?</h2>
            <p style="margin-bottom: 30px; font-size: 1.1rem;">Contact us to learn more or book your stay with us today</p>
            <div class="contact-buttons">
                <a href="tel:+639123456789" class="contact-btn primary">Call Us</a>
                <a href="mailto:info@hotelname.com" class="contact-btn primary">Email Us</a>
                <a href="index.php" class="contact-btn">Back to Home</a>
                <a href="rooms.php" class="contact-btn">View Rooms</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <li><a href="#">Contact Us</a></li>
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
</body>
</html>
