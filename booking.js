// FILE: booking.js (UPDATED)

document.addEventListener('DOMContentLoaded', () => {
    // 1. Read room slug from URL
    const params = new URLSearchParams(window.location.search);
    const roomSlug = params.get('room') || '';

    // Basic helper to convert slug to comparable name
    function slugToName(slug) {
        return slug.replace(/-/g, ' ').trim().toLowerCase();
    }

    // Find room data: ensure rooms array is available
    const roomsList = Array.isArray(window.rooms) ? window.rooms : null;

    if (!roomsList) {
        console.error('rooms data not found. Make sure rooms.js is included before booking.js and exposes window.rooms');
        // Show user-friendly message
        document.querySelector('.booking-page-main').innerHTML = `
            <div class="container" style="text-align:center; padding: 100px;">
                <h1>Room Data Missing</h1>
                <p>The room data could not be loaded. Please go back to <a href="rooms.html">Rooms</a>.</p>
            </div>`;
        return;
    }

    const normalized = slugToName(roomSlug);
    const room = roomsList.find(r => r.name.toLowerCase() === normalized);

    if (!room) {
        document.querySelector('.booking-page-main').innerHTML = `
            <div class="container" style="text-align:center; padding: 100px;">
                <h1>404: Room Not Found</h1>
                <p>The selected room details could not be loaded.</p>
                <a href="rooms.html" class="btn book-final-btn" style="width:auto;">View All Rooms</a>
            </div>`;
        return;
    }

    // Populate page
    function renderRoomDetails(room) {
        document.getElementById('room-title').textContent = `${room.name} - Hotel Name`;
        document.getElementById('room-name').textContent = room.name;
        document.getElementById('room-category').textContent = (room.category || '').toUpperCase() + ' ROOM';
        document.getElementById('room-description').textContent = room.description;

        document.getElementById('detail-size').textContent = room.size || '';
        document.getElementById('detail-capacity').textContent = `${room.maxPeople} people`;
        document.getElementById('detail-price').textContent = `$${room.price}`;

        document.getElementById('widget-price').textContent = `$${room.price}`;
        document.getElementById('detail-price-small').textContent = `$${room.price}`;

        // Amenities
        const amenitiesList = document.getElementById('full-amenities-list');
        amenitiesList.innerHTML = (room.amenities || []).map(a => `<li>${a}</li>`).join('');

        // Photo gallery: prefer room.images array, fallback to imageUrl
        const galleryImages = (room.images && room.images.length > 0) ? room.images : [room.imageUrl];
        const mainImageContainer = document.querySelector('.main-image-container');
        const galleryThumbs = document.querySelector('.gallery-thumbs');
        const galleryCount = document.querySelector('.gallery-count');

        galleryCount && (galleryCount.textContent = `(${galleryImages.length})`);

        mainImageContainer.innerHTML = galleryImages.map((src, i) => `
            <div class="room-slide-image" data-index="${i}" ${i === 0 ? 'aria-hidden="false"' : 'aria-hidden="true"'}>
                <img src="${src}" alt="${room.name} — photo ${i + 1}" />
            </div>
        `).join('');

        // Thumbs (optional)
        galleryThumbs.innerHTML = galleryImages.map((src, i) => `
            <button class="thumb-btn" data-index="${i}" aria-label="View photo ${i + 1}">
                <img src="${src}" alt="thumb ${i + 1}" />
            </button>
        `).join('');

        // --- SLIDESHOW SETUP ---
        const slides = mainImageContainer.querySelectorAll('.room-slide-image');
        let currentSlide = 0;

        function updateSlideshow() {
            mainImageContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        document.querySelector('.next-arrow')?.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlideshow();
        });
        document.querySelector('.prev-arrow')?.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlideshow();
        });

        // optional: auto-slide every 5s
        // setInterval(() => { currentSlide = (currentSlide + 1) % slides.length; updateSlideshow(); }, 5000);

        updateSlideshow();

        // Add hidden inputs to booking form so submission includes selected room & price
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
            // remove existing if present
            ['roomName', 'roomPrice'].forEach(name => {
                const ex = bookingForm.querySelector(`input[name="${name}"]`);
                if (ex) ex.remove();
            });

            const roomInput = document.createElement('input');
            roomInput.type = 'hidden';
            roomInput.name = 'roomName';
            roomInput.value = room.name;
            bookingForm.appendChild(roomInput);

            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'roomPrice';
            priceInput.value = room.price;
            bookingForm.appendChild(priceInput);
        }

        // Booking button behaviour: validate and simulate
        const bookBtn = document.getElementById('book-final-btn');
        if (bookBtn) {
            bookBtn.addEventListener('click', () => {
                const checkIn = document.getElementById('check-in').value;
                const checkOut = document.getElementById('check-out').value;
                const guests = parseInt(document.getElementById('guests').value, 10);

                if (!checkIn || !checkOut) {
                    alert('Please select check-in and check-out dates.');
                    return;
                }

                const inDate = new Date(checkIn);
                const outDate = new Date(checkOut);
                if (outDate <= inDate) {
                    alert('Check-out must be after check-in.');
                    return;
                }

                if (guests > room.maxPeople) {
                    alert(`The selected room allows up to ${room.maxPeople} guest(s). Please adjust the guest count or choose a different room.`);
                    return;
                }

                // calculate nights
                const msPerDay = 24 * 60 * 60 * 1000;
                const nights = Math.round((outDate - inDate) / msPerDay);
                const total = nights * room.price;

                // Here you'd normally send bookingForm via fetch or standard form submit
                // We'll simulate a confirmation modal/alert
                if (confirm(`Reserve ${room.name} for ${nights} night(s) at $${room.price}/night? Total: $${total}`)) {
                    // simulate success
                    alert('Booking simulated — success! (Replace with real payment/submit flow)');
                }
            });
        }

    } // end renderRoomDetails

    renderRoomDetails(room);

});
