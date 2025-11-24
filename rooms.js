// FILE: rooms.js (UPDATED)

// ROOM DATA (top-level so other pages can access it via window.rooms)
const rooms = [
    {
        name: "Single Room",
        category: "standard",
        description: "A cozy and thoughtfully designed space perfect for solo travelers and business professionals. The Single Room offers a warm, modern aesthetic, complete with all essential amenities for a restful stay. Each room features carefully curated furnishings that maximize comfort in an intimate setting, with soft ambient lighting and peaceful décor that creates a serene retreat. Ideal for short business trips, romantic getaways, or personal leisure. Natural light streams through soundproof windows, while the modern workstation ensures productivity. Premium bedding and blackout curtains guarantee uninterrupted rest.",
        size: "20 M²",
        maxPeople: 1,
        price: 89,
        imageUrl: "images/singleroom1.jpg",
        images: [
            "images/singleroom1.jpg",
            "images/singleroom2.jpg",
            "images/singleroom3.jpg"
        ],
        highlights: [
            "Modern, minimalist interior design",
            "Peaceful city view with natural lighting",
            "Ergonomic workspace and fast Wi-Fi",
            "Soft cotton bedding and blackout curtains"
        ],
        featuredAmenities: [
            "Mini Refrigerator", "Work Desk", "Smart TV",
            "Private Bathroom", "City View", "In-Room Safe"
        ],
        amenities: [
            "Mini Refrigerator", "Coffee & Tea Set", "Work Desk", "Smart TV",
            "Private Bathroom", "City View", "In-Room Safe",
            "Hair Dryer", "Iron (on request)", "Daily Housekeeping"
        ]
    },
    {
        name: "Double Room",
        category: "standard",
        description: "Ideal for couples and those seeking extra comfort and space. Our Double Room features a luxurious king-size bed with premium Egyptian cotton linens, creating the perfect sanctuary for relaxation. The room is thoughtfully appointed with contemporary furnishings, mood lighting, and elegant décor that creates an inviting atmosphere. Enjoy a spacious layout with a comfortable seating area, large work desk, and floor-to-ceiling windows with city views. The marble-finished private bathroom includes rainfall shower, premium toiletries, and heated towel racks. Perfect for romantic escapes or extended stays.",
        size: "30 M²",
        maxPeople: 2,
        price: 129,
        imageUrl: "images/double1.jpg",
         images: [
            "images/double1.jpg",
            "images/double2.jpg",
            "images/double3.jpg",
            "images/double4.jpg"
        ],
        featuredAmenities: [
            "Double Bed", "Mini Refrigerator", "Work Desk",
            "Smart TV", "Private Bathroom", "City View"
        ],
        amenities: [
            "Double Bed", "Mini Refrigerator", "Work Desk", "Smart TV",
            "Private Bathroom", "City View", "Wardrobe", "Hair Dryer",
            "Daily Housekeeping", "In-Room Safe"
        ]
    },
    {
        name: "Twin Room",
        category: "standard",
        description: "Perfect for two guests who prefer separate sleeping arrangements. Our Twin Room offers two comfortable single beds with luxury bedding, individual reading lights, and personal climate controls for optimal comfort. The spacious 35 m² layout provides ample room to move around, with a private balcony offering stunning city or garden views. The room features modern décor, contemporary furnishings, and a well-appointed bathroom with walk-in shower. Ideal for business colleagues, friends, or family members traveling together who appreciate personal space and comfort.",
        size: "35 M²",
        maxPeople: 2,
        price: 139,
        imageUrl: "images/twin1.jpg",
        images: [
            "images/twin1.jpg",
            "images/twin2.jpg",
            "images/twin3.jpg"
        ],
        featuredAmenities: [
            "Two Single Beds", "Private Balcony", "Desk",
            "Smart TV", "Mini Refrigerator", "Private Bathroom"
        ],
        amenities: [
            "Two Single Beds", "Private Balcony", "Desk", "Smart TV",
            "Mini Refrigerator", "Private Bathroom", "Hair Dryer", "Daily Housekeeping"
        ]
    },
    {
        name: "Triple Room",
        category: "family",
        description: "An excellent choice for small families or groups of three seeking comfortable accommodations. The Triple Room features flexible bed configurations: three single beds or one double bed with one single bed, allowing you to customize your sleeping arrangement. The spacious 40 m² space includes a dining area, abundant storage with a large closet, and separate living zones for privacy and relaxation. Modern amenities include air conditioning, flat-screen TV, mini refrigerator, and a work desk. The private bathroom features shower facilities and premium toiletries. Perfect for extended family stays or friend group travels.",
        size: "40 M²",
        maxPeople: 3,
        price: 189,
        imageUrl: "images/triple1.jpg",
        images: [
            "images/triple1.jpg",
            "images/triple2.jpg",
            "images/triple3.jpg"
        ],
        featuredAmenities: [
            "Three Beds", "Large Closet", "Extra Bed",
            "Private Bathroom", "Dining Area", "Mini Refrigerator"
        ],
        amenities: [
            "Three Beds", "Large Closet", "Extra Bed", "Private Bathroom",
            "Dining Area", "Mini Refrigerator", "Hair Dryer", "Daily Housekeeping"
        ]
    },
    {
        name: "Family Room",
        category: "family",
        description: "Designed specifically for families seeking comfort and space. The Family Room features two spacious double beds, ensuring everyone sleeps well throughout their stay. The 45 m² layout includes a separate living area with a sofa bed for additional sleeping or relaxing space, a full kitchenette for preparing light meals and snacks, and a dining area perfect for family bonding. Amenities include a microwave, mini refrigerator, and ample storage. The room is equipped with child-safe outlets and can accommodate a crib upon request. Modern entertainment options, high-speed Wi-Fi, and a marble bathroom with bath and shower combination ensure family comfort.",
        size: "45 M²",
        maxPeople: 4,
        price: 229,
        imageUrl: "images/family1.jpg",
        images: [
            "images/family1.jpg",
            "images/family2.jpg",
            "images/family3.jpg",
            "images/family4.jpg"
        ],
        featuredAmenities: [
            "Two Double Beds", "Kitchenette", "Sofa Bed",
            "Dining Area", "Connecting Rooms Available", "Private Bathroom"
        ],
        amenities: [
            "Two Double Beds", "Kitchenette", "Sofa Bed", "Dining Area",
            "Private Bathroom", "Connecting Rooms Available", "Microwave",
            "Child-Safe Outlets", "Crib on Request", "Daily Housekeeping"
        ]
    },
    {
        name: "Connected Room",
        category: "family",
        description: "The ultimate family accommodation offering two interconnected bedrooms with flexibility and privacy. This 50 m² suite features two separate bedrooms with double beds, two full private bathrooms, and a shared living area with sofa bed and dining space. The connecting door allows easy access between rooms while maintaining privacy for different family groups. Each bedroom has independent climate control, entertainment systems, and work areas. The kitchenette enables meal preparation, and connecting room amenities include microwave, mini refrigerator, and abundant storage. Perfect for extended family stays, multi-generational trips, or when multiple families travel together and desire both togetherness and personal space.",
        size: "50 M²",
        maxPeople: 4,
        price: 249,
        imageUrl: "images/connected1.jpg",
        images: [
            "images/connected1.jpg",
            "images/connected2.jpg"
        ],
        featuredAmenities: [
            "Two Bedrooms", "Two Bathrooms", "Connecting Door",
            "Private Balcony", "Sofa Bed", "Mini Refrigerator"
        ],
        amenities: [
            "Two Bedrooms", "Two Bathrooms", "Connecting Door",
            "Private Balcony", "Sofa Bed", "Mini Refrigerator",
            "Dining Area", "Kitchenette", "Crib on Request"
        ]
    },
    {
        name: "Executive Suite",
        category: "suite",
        description: "Experience luxury and sophistication in our magnificent Executive Suite. This 70 m² masterpiece features a lavish bedroom with king-size bed, premium bedding, and ambient lighting, combined with a sophisticated separate living room for entertaining or relaxing. Floor-to-ceiling windows frame panoramic city views, accessible from your private balcony. The marble bathroom is a spa-like retreat featuring a luxurious Jacuzzi bathtub, walk-in shower, heated towel racks, and premium amenities. The living area includes fine furnishings, entertainment system, work desk, and a fully stocked mini bar. Executive Suite guests enjoy 24-hour room service, premium toiletries, and personalized concierge services. Perfect for distinguished guests, special occasions, or those seeking the ultimate in hotel luxury.",
        size: "70 M²",
        maxPeople: 5,
        price: 399,
        imageUrl: "images/executive1.png",
        images: [
            "images/executive1.png",
            "images/executive2.png",
            "images/executive3.png"
        ],
        featuredAmenities: [
            "King Bed", "Living Area", "Private Balcony",
            "Bathtub / Jacuzzi", "Mini Bar", "Walk-In Closet"
        ],
        amenities: [
            "King Bed", "Living Area", "Private Balcony",
            "Bathtub / Jacuzzi", "Mini Bar", "Walk-In Closet",
            "Premium Toiletries", "24-Hour Room Service", "Separate Dining Area"
        ]
    },
    {
        name: "Presidential Suite",
        category: "suite",
        description: "The epitome of luxury and elegance awaits in our prestigious Presidential Suite. Spanning 90 m² of pure opulence, this extraordinary space features a grand master bedroom with a sumptuous king-size bed, a sophisticated living and dining area, and two lavishly appointed bathrooms. Each detail has been curated for discerning guests: Italian marble finishes, designer furnishings, state-of-the-art entertainment systems, and a fully equipped mini bar. The private balcony offers breathtaking panoramic views perfect for morning coffee or evening relaxation. Complimentary butler service attends to your every need, and 24-hour room service ensures unparalleled comfort. A wine chiller, premium toiletries, and personalized concierge services complete this extraordinary experience. Reserved for our most distinguished guests and special occasions.",
        size: "90 M²",
        maxPeople: 6,
        price: 599,
        imageUrl: "images/presidential1.avif",
        images: [
            "images/presidential1.avif",
            "images/presidential2.avif",
            "images/presidential3.avif",
            "images/presidential4.avif",
            "images/presidential5.avif",
            "images/presidential6.avif"
        ],
        featuredAmenities: [
            "Master Bedroom", "Dining Area", "2 Bathrooms",
            "Private Balcony", "Butler Service", "Mini Bar"
        ],
        amenities: [
            "Master Bedroom", "Dining Area", "2 Bathrooms",
            "Private Balcony", "Butler Service", "Mini Bar",
            "Walk-In Closet", "Jacuzzi", "Wine Chiller", "24-Hour Room Service"
        ]
    },
    {
        name: "Royal Suite",
        category: "suite",
        description: "Indulge in unparalleled magnificence with our ultimate Royal Suite, the crown jewel of our hotel collection. This expansive 100 m² sanctuary features multiple lavish spaces: a grand master bedroom with premium king-size bed, sophisticated living and entertainment areas, and a spa-inspired bathroom with a luxurious Jacuzzi, rainfall shower, and heated marble finishes. The exclusive private terrace provides a serene retreat with panoramic views perfect for relaxation or entertaining. Every element exudes refinement: hand-picked designer furnishings, premium linens, cutting-edge technology, and extensive amenities. A fully stocked premium bar, wine chiller, and butler service are at your disposal. Wake to welcome drinks, enjoy personalized concierge services, and experience 24-hour room service featuring gourmet cuisine. The Royal Suite is reserved exclusively for our most discerning guests seeking the absolute pinnacle of luxury hospitality.",
        size: "100 M²",
        maxPeople: 6,
        price: 749,
        imageUrl: "images/royal1.jpg",
        images: [
            "images/royal1.jpg",
            "images/royal4.jpg",
            "images/royal5.jpg",
            "images/royal6.jpg",
            "images/royal7.jpg",
            "images/royal8.webp",
            "images/royal9.webp"
        ],
        featuredAmenities: [
            "King Bed", "Private Terrace", "Living Area",
            "Jacuzzi", "Mini Bar", "Walk-In Closet"
        ],
        amenities: [
            "King Bed", "Private Terrace", "Living Area",
            "Jacuzzi", "Mini Bar", "Walk-In Closet",
            "Butler Service", "Premium Toiletries", "Welcome Drink",
            "24-Hour Room Service", "Wine Chiller"
        ]
    }
];

// expose globally
window.rooms = rooms;

// Only the DOM-related logic runs when the page actually contains the expected elements
document.addEventListener('DOMContentLoaded', () => {
    // === SELECTORS ===
    const roomCardsDisplay = document.querySelector('.room-cards-display');
    const categoryLabels = document.querySelectorAll('.category-label');
    const applyFiltersBtn = document.querySelector('.apply-filters-btn');

    // === HELPER FUNCTION ===
    function parseRange(value) {
        if (value.includes('+')) {
            const min = parseInt(value.slice(0, -1));
            return { min: min, max: Infinity };
        }
        if (value.includes('-')) {
            const parts = value.split('-');
            return { min: parseInt(parts[0]), max: parseInt(parts[1]) };
        }
        // Single value like '1', '2', etc.
        const num = parseInt(value);
        return { min: num, max: num };
    }

    // If we're on the rooms listing page (roomCardsDisplay exists) then run the listing/filtering code
    if (roomCardsDisplay) {
        // Example of the "Book Now" handler
        roomCardsDisplay.addEventListener('click', (e) => {
            const target = e.target.closest('.book-now-btn');

            if (target) {
                e.preventDefault();
                const roomName = target.closest('.room-card').querySelector('h3').textContent;
                // Convert room name to a URL-safe slug (e.g., "Executive Suite" -> "executive-suite")
                const roomSlug = roomName.toLowerCase().replace(/\s+/g, '-');

                // Redirect to booking page with slug param
                window.location.href = `booking.php?room=${encodeURIComponent(roomSlug)}`;
            }
        });

        // === CARD GENERATOR ===
        function createRoomCard(room) {
            const moreCount = room.amenities.length - (room.featuredAmenities?.length || 0);
            const moreLink = moreCount > 0 ? `<span class="more-amenities" data-room="${room.name}">+${moreCount} more</span>` : '';

            return `
                <div class="room-card ${room.category}" data-price="${room.price}" data-id="${room.name.toLowerCase().replace(/\s+/g, '-')}">
                    <div class="room-card-image"><img src="${room.imageUrl}" alt="${room.name}"></div>
                    <div class="room-card-content">
                        <h3>${room.name}</h3>
                        <p>${room.description}</p>
                        <div class="room-card-details">
                            <span>SIZE: ${room.size}</span>
                            <span>MAX PEOPLE: ${room.maxPeople}</span>
                        </div>
                        <div class="room-card-amenities">
                            ${(room.featuredAmenities || []).map(a => `<span>${a}</span>`).join(' ')} ${moreLink}
                        </div>
                        <div class="room-card-footer">
                            <span class="room-card-price">From $${room.price}<small>/night</small></span>
                            <a href="#" class="btn book-now-btn">Book Now</a>
                        </div>
                    </div>
                </div>
            `;
        }

        // === RENDER FUNCTION ===
        function renderRoomCards(list, category = 'all') {
            roomCardsDisplay.innerHTML = '';
            const categories = ['standard', 'family', 'suite'];

            const visibleRooms = category === 'all'
                ? list
                : list.filter(r => r.category === category);

            if (!visibleRooms.length) {
                roomCardsDisplay.innerHTML = `<p class="no-results">No rooms match your current filters.</p>`;
                return;
            }

            if (category === 'all') {
                categories.forEach(cat => {
                    const catRooms = visibleRooms.filter(r => r.category === cat);
                    if (!catRooms.length) return;
                    const heading = `<h2 class="category-heading">| ${cat.charAt(0).toUpperCase() + cat.slice(1)} Rooms</h2>`;
                    const grid = `<div class="room-grid">${catRooms.map(createRoomCard).join('')}</div>`;
                    roomCardsDisplay.innerHTML += heading + grid;
                });
            } else {
                roomCardsDisplay.innerHTML =
                    `<h2 class="category-heading">| ${category.charAt(0).toUpperCase() + category.slice(1)} Rooms</h2>
                     <div class="room-grid">${visibleRooms.map(createRoomCard).join('')}</div>`;
            }
        }

        // === CORE FILTER EXECUTION FUNCTION ===
        function filterRooms() {
            const activeCategoryLabel = document.querySelector('.category-label.active');
            const activeCategory = activeCategoryLabel ? activeCategoryLabel.dataset.category : 'all';

            const selectedPriceRanges = Array.from(document.querySelectorAll('input[name="price"]:checked')).map(cb => cb.value);
            const selectedCapacityRanges = Array.from(document.querySelectorAll('input[name="capacity"]:checked')).map(cb => cb.value);
            const selectedAmenities = Array.from(document.querySelectorAll('input[name="amenity"]:checked')).map(cb => cb.value);

            let filteredList = rooms;

            if (selectedPriceRanges.length > 0) {
                filteredList = filteredList.filter(room => {
                    return selectedPriceRanges.some(rangeValue => {
                        const { min, max } = parseRange(rangeValue);
                        return room.price >= min && room.price <= max;
                    });
                });
            }

            if (selectedCapacityRanges.length > 0) {
                filteredList = filteredList.filter(room => {
                    return selectedCapacityRanges.some(rangeValue => {
                        const { min, max } = parseRange(rangeValue);
                        return room.maxPeople >= min && room.maxPeople <= max;
                    });
                });
            }

            if (selectedAmenities.length > 0) {
                filteredList = filteredList.filter(room => {
                    return selectedAmenities.every(amenity => room.amenities.includes(amenity) || room.amenities.includes(amenity.split(' / ')[0]));
                });
            }

            renderRoomCards(filteredList, activeCategory);
        }

        // === EVENT LISTENERS ===
        filterRooms();

        categoryLabels.forEach(label => {
            label.addEventListener('click', () => {
                categoryLabels.forEach(l => l.classList.remove('active'));
                label.classList.add('active');
                const radio = label.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                filterRooms();
            });
        });

        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', filterRooms);
        }

        // HERO SLIDESHOW (only if slides exist on this page)
        const heroSlides = document.querySelectorAll('.slide');
        if (heroSlides.length > 0) {
            let heroIndex = 0;
            const heroCount = heroSlides.length;
            function nextHeroSlide() {
                heroSlides[heroIndex].classList.remove('active');
                heroIndex = (heroIndex + 1) % heroCount;
                heroSlides[heroIndex].classList.add('active');
            }
            setInterval(nextHeroSlide, 5000);
        }

    } // end if(roomCardsDisplay)

});



