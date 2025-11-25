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
                <p>The room data could not be loaded. Please go back to <a href="rooms.php">Rooms</a>.</p>
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
                <a href="rooms.php" class="btn book-final-btn" style="width:auto;">View All Rooms</a>
            </div>`;
        return;
    }

    // Populate page
    function renderRoomDetails(room) {
        document.getElementById('room-title').textContent = `${room.name} - Hotel Name`;
        document.getElementById('room-name').textContent = room.name;
        document.getElementById('room-category').textContent = (room.category || '').toUpperCase() + ' ROOM';
        document.getElementById('room-tagline').textContent = room.tagline || '';
        document.getElementById('room-description').textContent = room.description;

        document.getElementById('detail-size').textContent = room.size || '';
        document.getElementById('detail-capacity').textContent = `${room.maxPeople} people`;
        document.getElementById('detail-price').textContent = `$${room.price}`;

        document.getElementById('widget-price').textContent = `$${room.price}`;
        document.getElementById('detail-price-small').textContent = `$${room.price}`;

        // Populate guests dropdown based on room max capacity
        const guestsSelect = document.getElementById('guests');
        if (guestsSelect && room.maxPeople) {
            guestsSelect.innerHTML = '';
            for (let i = 1; i <= room.maxPeople; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i} Guest${i > 1 ? 's' : ''}`;
                guestsSelect.appendChild(option);
            }
        }

        // Amenities
        const amenitiesList = document.getElementById('full-amenities-list');
        amenitiesList.innerHTML = (room.amenities || []).map(a => `<li>${a}</li>`).join('');

        // Highlights (Why Guests Love This Room)
        const highlightsContainer = document.getElementById('highlights-container');
        if (highlightsContainer && room.highlights && room.highlights.length > 0) {
            highlightsContainer.innerHTML = room.highlights.map(highlight => {
                const parts = highlight.split(/:\s+/); // split on ": " to get title and description
                const title = parts[0] || highlight;
                const description = parts[1] || '';
                return `
                    <div class="highlight">
                        <strong>${title}</strong>
                        ${description ? `<p>${description}</p>` : ''}
                    </div>
                `;
            }).join('');
        }

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

        // DATE RANGE PICKER WITH LITEPICKR
        const checkInInput = document.getElementById('check-in');
        const checkOutInput = document.getElementById('check-out');
        const widgetPrice = document.getElementById('widget-price');

        // Get today's date
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Wait for Litepickr to be available
        function initializeDatePicker() {
            if (typeof Litepicker === 'undefined') {
                // Litepickr not loaded yet, try again
                setTimeout(initializeDatePicker, 100);
                return;
            }

            // Shared date range state
            let startDate = null;
            let endDate = null;

            // Check-in date picker
            const checkInPicker = new Litepicker({
                element: checkInInput,
                minDate: today,
                maxDate: new Date(today.getTime() + 365 * 24 * 60 * 60 * 1000),
                numberOfMonths: 2,
                numberOfColumns: 2,
                lang: 'en-US',
                format: 'YYYY-MM-DD',
                showTooltip: true,
                singleMonth: false,
                resetButton: true,
                autoApply: true,
                onSelect: function(date) {
                    startDate = date;
                    checkInInput.value = date ? date.format('YYYY-MM-DD') : '';
                    // If checkout is already selected and is before checkin, clear it
                    if (endDate && startDate && endDate < startDate) {
                        endDate = null;
                        checkOutInput.value = '';
                    }
                    if (startDate && endDate) {
                        calculatePrice();
                    }
                },
                onClose: function(date) {
                    if (startDate && endDate) {
                        calculatePrice();
                    }
                }
            });

            // Check-out date picker
            const checkOutPicker = new Litepicker({
                element: checkOutInput,
                minDate: startDate || today,
                maxDate: new Date(today.getTime() + 365 * 24 * 60 * 60 * 1000),
                numberOfMonths: 2,
                numberOfColumns: 2,
                lang: 'en-US',
                format: 'YYYY-MM-DD',
                showTooltip: true,
                singleMonth: false,
                resetButton: true,
                autoApply: true,
                onSelect: function(date) {
                    endDate = date;
                    checkOutInput.value = date ? date.format('YYYY-MM-DD') : '';
                    if (startDate && endDate) {
                        calculatePrice();
                    }
                },
                onClose: function(date) {
                    if (startDate && endDate) {
                        calculatePrice();
                    }
                }
            });

            // Update checkout min date when checkin changes
            checkInInput.addEventListener('change', function() {
                if (checkInInput.value) {
                    const newMinDate = new Date(checkInInput.value);
                    checkOutPicker.setOptions({ minDate: newMinDate });
                }
            });
        }

        // Initialize when DOM is ready
        initializeDatePicker();

        // Calculate and update price based on nights
        function calculatePrice() {
            const checkInVal = checkInInput.value;
            const checkOutVal = checkOutInput.value;

            if (!checkInVal || !checkOutVal) {
                widgetPrice.textContent = `$${room.price}`;
                return;
            }

            const checkIn = new Date(checkInVal);
            const checkOut = new Date(checkOutVal);

            if (checkOut <= checkIn) {
                widgetPrice.textContent = `$${room.price}`;
                return;
            }

            const msPerDay = 24 * 60 * 60 * 1000;
            const nights = Math.ceil((checkOut - checkIn) / msPerDay);

            if (nights > 0) {
                const total = nights * room.price;
                widgetPrice.innerHTML = `$${total.toLocaleString()}<br><small style="font-size: 0.85rem; color: #666;">${nights} night${nights !== 1 ? 's' : ''}</small>`;
            } else {
                widgetPrice.textContent = `$${room.price}`;
            }
        }

        // Booking button behaviour: validate and submit
        const bookBtn = document.getElementById('book-final-btn');
        if (bookBtn) {
            bookBtn.addEventListener('click', async () => {
                // Check if user is logged in
                const userLoggedIn = document.getElementById('user-logged-in').value === 'true';
                if (!userLoggedIn) {
                    showThemedAlert('Please log in first.');
                    return;
                }

                const checkInVal = checkInInput.value;
                const checkOutVal = checkOutInput.value;
                const guests = parseInt(document.getElementById('guests').value, 10);

                if (!checkInVal || !checkOutVal) {
                    showThemedAlert('Please select check-in and check-out dates.');
                    return;
                }

                const inDate = new Date(checkInVal);
                const outDate = new Date(checkOutVal);
                
                // Validate dates
                if (inDate < today) {
                    showThemedAlert('Check-in date cannot be in the past.');
                    return;
                }

                if (outDate <= inDate) {
                    showThemedAlert('Check-out must be after check-in.');
                    return;
                }

                if (guests > room.maxPeople) {
                    showThemedAlert(`The selected room allows up to ${room.maxPeople} guest(s). Please adjust the guest count or choose a different room.`);
                    return;
                }

                // calculate nights
                const msPerDay = 24 * 60 * 60 * 1000;
                const nights = Math.ceil((outDate - inDate) / msPerDay);
                const total = nights * room.price;

                // Show confirmation with themed modal
                showConfirmationModal(room.name, nights, room.price, total, async () => {
                    // Disable button while processing
                    bookBtn.disabled = true;
                    const originalText = bookBtn.textContent;
                    bookBtn.textContent = 'Processing...';

                    try {
                        // Submit booking to backend
                        const formData = new FormData();
                        formData.append('room_name', room.name);
                        formData.append('check_in', checkInVal);
                        formData.append('check_out', checkOutVal);
                        formData.append('price_per_night', room.price);
                        formData.append('number_of_nights', nights);
                        formData.append('total_price', total);
                        formData.append('number_of_guests', guests);

                        const response = await fetch('process_booking.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            showSuccessModal(result.order_id);
                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = 'bookings.php';
                            }, 2000);
                        } else {
                            showThemedAlert('Booking failed: ' + (result.message || 'Unknown error'));
                            bookBtn.disabled = false;
                            bookBtn.textContent = originalText;
                        }
                    } catch (error) {
                        console.error('Booking error:', error);
                        showThemedAlert('An error occurred while processing your booking. Please try again.');
                        bookBtn.disabled = false;
                        bookBtn.textContent = originalText;
                    }
                });
            });
        }

        // Themed Modal Functions
        function showThemedAlert(message) {
            const modal = document.getElementById('themed-alert-modal');
            const content = document.getElementById('alert-message-content');
            if (modal && content) {
                content.textContent = message;
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function showConfirmationModal(roomName, nights, pricePerNight, total, onConfirm) {
            const modal = document.getElementById('booking-confirmation-modal');
            if (modal) {
                document.getElementById('confirm-room-name').textContent = roomName;
                document.getElementById('confirm-nights').textContent = nights;
                document.getElementById('confirm-price-per-night').textContent = `$${pricePerNight.toLocaleString()}`;
                document.getElementById('confirm-total').textContent = `$${total.toLocaleString()}`;
                
                const confirmBtn = document.getElementById('confirm-booking-btn');
                const cancelBtn = document.getElementById('cancel-confirmation-btn');
                
                confirmBtn.onclick = () => {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    onConfirm();
                };
                
                cancelBtn.onclick = () => {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                };
                
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function showSuccessModal(orderId) {
            const modal = document.getElementById('booking-success-modal');
            if (modal) {
                document.getElementById('success-order-id').textContent = orderId;
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        // Close themed alert modal
        const alertModal = document.getElementById('themed-alert-modal');
        if (alertModal) {
            const closeBtn = alertModal.querySelector('.modal-close-btn');
            const okBtn = document.getElementById('alert-ok-btn');
            
            closeBtn?.addEventListener('click', () => {
                alertModal.classList.remove('show');
                alertModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
            
            okBtn?.addEventListener('click', () => {
                alertModal.classList.remove('show');
                alertModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }

    } // end renderRoomDetails

    renderRoomDetails(room);

});
