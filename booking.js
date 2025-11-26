// FILE: booking.js

document.addEventListener('DOMContentLoaded', () => {
    try {
        // 1. Read room slug from URL
        const params = new URLSearchParams(window.location.search);
        const roomSlug = params.get('room') || '';
        console.log('Booking page loaded, room slug:', roomSlug);

        // Basic helper to convert slug to comparable name
        function slugToName(slug) {
            return slug.replace(/-/g, ' ').trim().toLowerCase();
        }

        // Find room data: ensure rooms array is available
        const roomsList = Array.isArray(window.rooms) ? window.rooms : null;
        console.log('Rooms array available:', !!roomsList, roomsList ? `(${roomsList.length} rooms)` : 'null');

        if (!roomsList) {
            console.error('rooms data not found. Make sure rooms.js is included before booking.js');
            document.querySelector('.booking-page-main').innerHTML = `
                <div class="container" style="text-align:center; padding: 100px;">
                    <h1>Room Data Missing</h1>
                    <p>The room data could not be loaded. Please go back to <a href="rooms.php">Rooms</a>.</p>
                </div>`;
            return;
        }

        const normalized = slugToName(roomSlug);
        const room = roomsList.find(r => r.name.toLowerCase() === normalized);
        console.log('Room lookup - normalized slug:', normalized, '- found room:', room ? room.name : 'NOT FOUND');

        if (!room) {
            document.querySelector('.booking-page-main').innerHTML = `
                <div class="container" style="text-align:center; padding: 100px;">
                    <h1>404: Room Not Found</h1>
                    <p>The selected room details could not be loaded.</p>
                    <a href="rooms.php" class="btn book-final-btn" style="width:auto;">View All Rooms</a>
                </div>`;
            return;
        }

        // Safe setter function to prevent null errors
        function safeSetText(elementId, value) {
            const el = document.getElementById(elementId);
            if (el) {
                el.textContent = value;
            } else {
                console.warn(`Element with id '${elementId}' not found`);
            }
        }

        // Populate page details
        safeSetText('room-title', `${room.name} - Elegante`);
        safeSetText('room-name', room.name);
        safeSetText('room-category', (room.category || '').toUpperCase() + ' ROOM');
        safeSetText('room-tagline', room.tagline || '');
        safeSetText('room-description', room.description);

        safeSetText('detail-size', room.size || '');
        safeSetText('detail-capacity', `${room.maxPeople} people`);
        safeSetText('detail-price', `₱${room.price.toLocaleString()}`);
        safeSetText('widget-price', `₱${room.price.toLocaleString()}`);
        safeSetText('detail-price-small', `₱${room.price.toLocaleString()}`);

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
        if (amenitiesList) {
            amenitiesList.innerHTML = (room.amenities || []).map(a => `<li>${a}</li>`).join('');
        }

        // Highlights (Why Guests Love This Room)
        const highlightsContainer = document.getElementById('highlights-container');
        if (highlightsContainer && room.highlights && room.highlights.length > 0) {
            highlightsContainer.innerHTML = room.highlights.map(highlight => {
                const parts = highlight.split(/:\s+/);
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

        // Photo gallery
        const galleryImages = (room.images && room.images.length > 0) ? room.images : [room.imageUrl];
        const mainImageContainer = document.querySelector('.main-image-container');
        const galleryThumbs = document.querySelector('.gallery-thumbs');
        const galleryCount = document.querySelector('.gallery-count');

        if (galleryCount) {
            galleryCount.textContent = `(${galleryImages.length})`;
        }

        if (mainImageContainer) {
            mainImageContainer.innerHTML = galleryImages.map((src, i) => `
                <div class="room-slide-image" data-index="${i}" ${i === 0 ? 'aria-hidden="false"' : 'aria-hidden="true"'}>
                    <img src="${src}" alt="${room.name} — photo ${i + 1}" />
                </div>
            `).join('');

            // Thumbs
            if (galleryThumbs) {
                galleryThumbs.innerHTML = galleryImages.map((src, i) => `
                    <button class="thumb-btn" data-index="${i}" aria-label="View photo ${i + 1}">
                        <img src="${src}" alt="thumb ${i + 1}" />
                    </button>
                `).join('');
            }

            // Slideshow setup
            const slides = mainImageContainer.querySelectorAll('.room-slide-image');
            let currentSlide = 0;

            function updateSlideshow() {
                mainImageContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            }

            const nextArrow = document.querySelector('.next-arrow');
            const prevArrow = document.querySelector('.prev-arrow');

            if (nextArrow) {
                nextArrow.addEventListener('click', () => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    updateSlideshow();
                });
            }

            if (prevArrow) {
                prevArrow.addEventListener('click', () => {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    updateSlideshow();
                });
            }

            updateSlideshow();
        }

        // Add hidden inputs to booking form
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
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

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        console.log('Date inputs found:', {
            checkIn: !!checkInInput,
            checkOut: !!checkOutInput,
            widgetPrice: !!widgetPrice
        });

        function initializeDatePicker() {
            console.log('initializeDatePicker called');
            if (!checkInInput || !checkOutInput) {
                console.error('Date input elements not found in DOM');
                return;
            }

            if (typeof Litepicker === 'undefined') {
                console.log('Litepicker not loaded, retrying in 100ms...');
                setTimeout(initializeDatePicker, 100);
                return;
            }

            console.log('Initializing Litepicker date pickers');
            let startDate = null;
            let endDate = null;

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

            checkInInput.addEventListener('change', function() {
                if (checkInInput.value) {
                    const newMinDate = new Date(checkInInput.value);
                    checkOutPicker.setOptions({ minDate: newMinDate });
                }
            });
        }

        initializeDatePicker();

        function calculatePrice() {
            if (!checkInInput || !checkOutInput) {
                return;
            }

            const checkInVal = checkInInput.value;
            const checkOutVal = checkOutInput.value;

            if (!checkInVal || !checkOutVal) {
                safeSetText('widget-price', `₱${room.price}`);
                return;
            }

            const checkIn = new Date(checkInVal);
            const checkOut = new Date(checkOutVal);

            if (checkOut <= checkIn) {
                safeSetText('widget-price', `₱${room.price}`);
                return;
            }

            const msPerDay = 24 * 60 * 60 * 1000;
            const nights = Math.ceil((checkOut - checkIn) / msPerDay);

            if (nights > 0) {
                const total = nights * room.price;
                const priceEl = document.getElementById('widget-price');
                if (priceEl) {
                    priceEl.innerHTML = `₱${total.toLocaleString()}<br><small style="font-size: 0.85rem; color: #666;">${nights} night${nights !== 1 ? 's' : ''}</small>`;
                }
            } else {
                safeSetText('widget-price', `₱${room.price}`);
            }
        }

        // Booking button
        const bookBtn = document.getElementById('book-final-btn');
        if (bookBtn) {
            bookBtn.addEventListener('click', async () => {
                if (!checkInInput || !checkOutInput) {
                    showThemedAlert('Booking form is not properly loaded.');
                    return;
                }

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

                const msPerDay = 24 * 60 * 60 * 1000;
                const nights = Math.ceil((outDate - inDate) / msPerDay);
                const total = nights * room.price;

                showConfirmationModal(room.name, nights, room.price, total, async () => {
                    bookBtn.disabled = true;
                    const originalText = bookBtn.textContent;
                    bookBtn.textContent = 'Processing...';

                    try {
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

                        console.log('Booking response status:', response.status);
                        const result = await response.json();
                        console.log('Booking result:', result);

                        if (result.success) {
                            showSuccessModal(result.order_id);
                            setTimeout(() => {
                                window.location.href = 'bookings.php';
                            }, 2000);
                        } else {
                            console.error('Booking failed:', result.message);
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

        // Modal Functions
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
                safeSetText('confirm-room-name', roomName);
                safeSetText('confirm-nights', nights);
                safeSetText('confirm-price-per-night', `₱${pricePerNight.toLocaleString()}`);
                safeSetText('confirm-total', `₱${total.toLocaleString()}`);

                const confirmBtn = document.getElementById('confirm-booking-btn');
                const cancelBtn = document.getElementById('cancel-confirmation-btn');

                if (confirmBtn) {
                    confirmBtn.onclick = () => {
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                        onConfirm();
                    };
                }

                if (cancelBtn) {
                    cancelBtn.onclick = () => {
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    };
                }

                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function showSuccessModal(orderId) {
            const modal = document.getElementById('booking-success-modal');
            if (modal) {
                safeSetText('success-order-id', orderId);
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        // Close alert modal
        const alertModal = document.getElementById('themed-alert-modal');
        if (alertModal) {
            const closeBtn = alertModal.querySelector('.modal-close-btn');
            const okBtn = document.getElementById('alert-ok-btn');

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    alertModal.classList.remove('show');
                    alertModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            }

            if (okBtn) {
                okBtn.addEventListener('click', () => {
                    alertModal.classList.remove('show');
                    alertModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            }
        }

    } catch (error) {
        console.error('Error initializing booking page:', error);
        console.error('Error stack:', error.stack);
        const mainContent = document.querySelector('.booking-page-main');
        if (mainContent) {
            mainContent.innerHTML = `
                <div class="container" style="text-align:center; padding: 100px;">
                    <h1>Error Loading Page</h1>
                    <p>An error occurred while loading the booking page. Please try again or contact support.</p>
                    <p style="font-size:0.9em; color:#666;">Error: ${error.message}</p>
                    <a href="rooms.php" class="btn" style="display:inline-block; margin-top:20px;">Back to Rooms</a>
                </div>`;
        }
    }
});
