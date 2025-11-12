// FILE: rooms.js (UPDATED)

// ROOM DATA (top-level so other pages can access it via window.rooms)
const rooms = [
    {
        name: "Single Room",
        category: "standard",
        description: "A cozy and thoughtfully designed space perfect for solo travelers. The Single Room offers a warm, modern aesthetic, complete with all essential amenities for a restful stay. Ideal for short business trips or personal getaways.",
        size: "20 M²",
        maxPeople: 1,
        price: 89,
        imageUrl: "images/singleroom1.jpg",
        images: [
            "images/singleroom1.jpg",
            "images/singleroom2.jpg",
            "images/singleroom3.jpg",
            "slide2.jpg"
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
        ],
        extraAmenities: [
            "Room Service", "Hair Dryer", "In-room Safe",
            "Complimentary Toiletries", "24-hour Front Desk Support",
            "Laundry Service (on request)"
        ]
    },
    {
        name: "Double Room",
        category: "standard",
        description: "Comfortable room with a double bed and modern amenities.",
        size: "30 M²",
        maxPeople: 2,
        price: 129,
        imageUrl: "https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=600",
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
        description: "Spacious room with two single beds, perfect for sharing.",
        size: "35 M²",
        maxPeople: 2,
        price: 139,
        imageUrl: "https://images.unsplash.com/photo-1549294413-26f195200c82?auto=format&fit=crop&w=600",
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
        description: "Spacious room with three single beds or one double and one single bed.",
        size: "40 M²",
        maxPeople: 3,
        price: 189,
        imageUrl: "https://images.unsplash.com/photo-1596394331432-8e7275811c03?auto=format&fit=crop&w=600",
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
        description: "Perfect for families with two double beds and extra living space.",
        size: "45 M²",
        maxPeople: 4,
        price: 229,
        imageUrl: "https://images.unsplash.com/photo-1582719478250-c8fbe21396c1?auto=format&fit=crop&w=600",
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
        description: "Two interconnected rooms perfect for families needing extra privacy.",
        size: "50 M²",
        maxPeople: 4,
        price: 249,
        imageUrl: "https://images.unsplash.com/photo-1582736340176-0f72390f7a6a?auto=format&fit=crop&w=600",
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
        description: "Luxurious suite with separate living room and premium amenities.",
        size: "70 M²",
        maxPeople: 5,
        price: 399,
        imageUrl: "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600",
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
        description: "Our finest suite with panoramic views and exclusive services.",
        size: "90 M²",
        maxPeople: 6,
        price: 599,
        imageUrl: "https://images.unsplash.com/photo-1608198399988-341a0f484828?auto=format&fit=crop&w=600",
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
        description: "Ultimate luxury with multiple rooms and private terrace.",
        size: "100 M²",
        maxPeople: 6,
        price: 749,
        imageUrl: "https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=600",
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
        const parts = value.split('-');
        return { min: parseInt(parts[0]), max: parseInt(parts[1]) };
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
                window.location.href = `booking.html?room=${encodeURIComponent(roomSlug)}`;
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



