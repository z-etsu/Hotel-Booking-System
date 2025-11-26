<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Room Filters - Elegante</title>
    <link rel="stylesheet" href="style.css">
    <script src="rooms.js" defer></script>
    <script src="script.js?v=<?php echo time(); ?>"></script>
    <style>
        /* Advanced Filters Page Styles */
        .filters-container {
            padding-top: 100px;
            min-height: 100vh;
            background-color: var(--bg-color);
            padding-bottom: 4rem;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .filters-page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .filters-page-header h1 {
            font-size: 2.5rem;
            color: #532200;
            font-family: var(--brand-font);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
        }

        .filters-page-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .filter-form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .filter-form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .filter-form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-form-section h2 {
            font-size: 1.4rem;
            color: #532200;
            margin-bottom: 1.5rem;
            font-family: var(--brand-font);
            letter-spacing: 0.05em;
        }

        .price-input-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .price-input-group > div {
            display: flex;
            flex-direction: column;
        }

        .price-input-group label {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .price-input-group input {
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            color: #333;
            transition: border-color 0.3s ease;
        }

        .price-input-group input:focus {
            outline: none;
            border-color: #532200;
            box-shadow: 0 0 0 3px rgba(83, 34, 0, 0.1);
        }

        .guests-input-group {
            display: flex;
            flex-direction: column;
            max-width: 300px;
        }

        .guests-input-group label {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .guests-input-group input {
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            color: #333;
            transition: border-color 0.3s ease;
        }

        .guests-input-group input:focus {
            outline: none;
            border-color: #532200;
            box-shadow: 0 0 0 3px rgba(83, 34, 0, 0.1);
        }

        .amenities-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
        }

        .amenity-checkbox-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #f9f9f9;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .amenity-checkbox-item:hover {
            background: #f0f0f0;
            border-color: #532200;
        }

        .amenity-checkbox-item input[type="checkbox"] {
            margin-right: 12px;
            accent-color: #532200;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .amenity-checkbox-item label {
            cursor: pointer;
            margin: 0;
            color: #333;
            font-size: 0.95rem;
            flex: 1;
        }

        .filter-form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 12px 32px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: var(--brand-font);
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-btn-apply {
            background: #532200;
            color: white;
        }

        .filter-btn-apply:hover {
            background: #693711;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(83, 34, 0, 0.2);
        }

        .filter-btn-reset {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #ddd;
        }

        .filter-btn-reset:hover {
            background: #e0e0e0;
            border-color: #999;
        }

        .filter-btn-back {
            background: transparent;
            color: #532200;
            border: 2px solid #532200;
        }

        .filter-btn-back:hover {
            background: #532200;
            color: white;
        }

        .no-results-message {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.1rem;
        }

        .filtered-results {
            margin-top: 3rem;
            padding-top: 3rem;
            border-top: 2px solid #f0f0f0;
        }

        .filtered-results h2 {
            font-size: 1.5rem;
            color: #532200;
            margin-bottom: 2rem;
            font-family: var(--brand-font);
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .filter-form-container {
                padding: 1.5rem;
            }

            .price-input-group {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .amenities-grid-container {
                grid-template-columns: 1fr;
            }

            .filter-form-actions {
                flex-direction: column;
            }

            .filter-btn {
                width: 100%;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="page-transition">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="filters-container">
        <div class="container">
            <div class="filters-page-header">
                <h1>Advanced Room Filters</h1>
                <p>Customize your search to find the perfect room for your stay</p>
            </div>

            <div class="filter-form-container">
                <!-- Price Range Section -->
                <div class="filter-form-section">
                    <h2>💵 Price Range (Per Night)</h2>
                    <div class="price-input-group">
                        <div>
                            <label for="pageMinPrice">Minimum Price ($)</label>
                            <input type="number" id="pageMinPrice" placeholder="0" min="0">
                        </div>
                        <div>
                            <label for="pageMaxPrice">Maximum Price ($)</label>
                            <input type="number" id="pageMaxPrice" placeholder="999" min="0">
                        </div>
                    </div>
                </div>

                <!-- Guests Section -->
                <div class="filter-form-section">
                    <h2>👨‍👩‍👧‍👦 Minimum Number of Guests</h2>
                    <div class="guests-input-group">
                        <label for="pageGuests">Guest Capacity</label>
                        <input type="number" id="pageGuests" placeholder="1" min="1" max="10">
                    </div>
                </div>

                <!-- All Amenities Section -->
                <div class="filter-form-section">
                    <h2>✨ Amenities & Features</h2>
                    <div class="amenities-grid-container" id="pageAmenitiesGrid">
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="filter-form-actions">
                    <button class="filter-btn filter-btn-reset" id="pageResetBtn">Reset Filters</button>
                    <button class="filter-btn filter-btn-back" onclick="history.back();">Back to Rooms</button>
                    <button class="filter-btn filter-btn-apply" id="pageApplyBtn">Apply & View Results</button>
                </div>
            </div>

            <!-- Filtered Results Section -->
            <div id="resultsSection" class="filtered-results" style="display: none;">
                <h2>Search Results</h2>
                <div id="pageResultsGrid" class="results-grid">
                    <!-- Results will be displayed here -->
                </div>
            </div>
        </div>
    </div>

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

    <script>
        // Get reference to rooms data from rooms.js
        document.addEventListener('DOMContentLoaded', () => {
            // Function to get all unique amenities
            function getAllUniqueAmenities() {
                const amenitiesSet = new Set();
                if (window.rooms) {
                    window.rooms.forEach(room => {
                        room.amenities.forEach(amenity => amenitiesSet.add(amenity));
                    });
                }
                return Array.from(amenitiesSet).sort();
            }

            // Populate amenities grid
            const pageAmenitiesGrid = document.getElementById('pageAmenitiesGrid');
            if (pageAmenitiesGrid) {
                const allAmenities = getAllUniqueAmenities();
                pageAmenitiesGrid.innerHTML = allAmenities.map(amenity => `
                    <div class="amenity-checkbox-item">
                        <input type="checkbox" id="page-amenity-${amenity.replace(/\s+/g, '-').toLowerCase()}" value="${amenity}">
                        <label for="page-amenity-${amenity.replace(/\s+/g, '-').toLowerCase()}">${amenity}</label>
                    </div>
                `).join('');
            }

            // Reset button functionality
            const pageResetBtn = document.getElementById('pageResetBtn');
            if (pageResetBtn) {
                pageResetBtn.addEventListener('click', () => {
                    document.getElementById('pageMinPrice').value = '';
                    document.getElementById('pageMaxPrice').value = '';
                    document.getElementById('pageGuests').value = '';
                    document.querySelectorAll('#pageAmenitiesGrid input[type="checkbox"]').forEach(cb => cb.checked = false);
                    document.getElementById('resultsSection').style.display = 'none';
                });
            }

            // Apply filters button functionality
            const pageApplyBtn = document.getElementById('pageApplyBtn');
            if (pageApplyBtn) {
                pageApplyBtn.addEventListener('click', () => {
                    const minPrice = parseFloat(document.getElementById('pageMinPrice').value) || 0;
                    const maxPrice = parseFloat(document.getElementById('pageMaxPrice').value) || Infinity;
                    const minGuests = parseInt(document.getElementById('pageGuests').value) || 0;
                    const selectedAmenities = Array.from(document.querySelectorAll('#pageAmenitiesGrid input[type="checkbox"]:checked')).map(cb => cb.value);

                    let filteredRooms = window.rooms;

                    // Filter by price
                    filteredRooms = filteredRooms.filter(room => room.price >= minPrice && room.price <= maxPrice);

                    // Filter by guest capacity
                    if (minGuests > 0) {
                        filteredRooms = filteredRooms.filter(room => room.maxPeople >= minGuests);
                    }

                    // Filter by amenities
                    if (selectedAmenities.length > 0) {
                        filteredRooms = filteredRooms.filter(room => {
                            return selectedAmenities.every(amenity => room.amenities.includes(amenity));
                        });
                    }

                    // Display results
                    const resultsSection = document.getElementById('resultsSection');
                    const pageResultsGrid = document.getElementById('pageResultsGrid');

                    if (filteredRooms.length === 0) {
                        pageResultsGrid.innerHTML = '<div class="no-results-message">No rooms match your filters. Please try adjusting your criteria.</div>';
                    } else {
                        pageResultsGrid.innerHTML = filteredRooms.map(room => `
                            <div class="room-card ${room.category}">
                                <div class="room-card-image"><img src="${room.imageUrl}" alt="${room.name}"></div>
                                <div class="room-card-content">
                                    <h3>${room.name}</h3>
                                    <p>${room.description}</p>
                                    <div class="room-card-details">
                                        <span>SIZE: ${room.size}</span>
                                        <span>MAX PEOPLE: ${room.maxPeople}</span>
                                    </div>
                                    <div class="room-card-amenities">
                                        ${(room.featuredAmenities || []).map(a => `<span>${a}</span>`).join(' ')}
                                    </div>
                                    <div class="room-card-footer">
                                        <span class="room-card-price">From $${room.price}<small>/night</small></span>
                                        <a href="booking.php?room=${room.name.toLowerCase().replace(/\s+/g, '-')}" class="btn book-now-btn">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }

                    resultsSection.style.display = 'block';
                    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
        });
    </script>
</body>

</html>
