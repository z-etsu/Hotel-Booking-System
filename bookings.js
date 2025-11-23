// FILE: bookings.js
// Handles booking page interactions and cancellations

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('cancelConfirmModal');
    const modalClose = document.querySelector('.modal-close');
    const cancelModalCancelBtn = document.getElementById('cancelModalCancelBtn');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    const cancelRoomNameElement = document.getElementById('cancelRoomName');
    
    let currentBookingId = null;
    let currentBookingRoom = null;

    // Get all cancel booking buttons
    const cancelBookingBtns = document.querySelectorAll('.cancel-booking-btn');

    // Open modal when cancel button is clicked
    cancelBookingBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            currentBookingId = btn.getAttribute('data-booking-id');
            currentBookingRoom = btn.getAttribute('data-room-name');
            
            if (currentBookingId && currentBookingRoom) {
                cancelRoomNameElement.textContent = currentBookingRoom;
                openModal(modal);
            }
        });
    });

    // Close modal when close button is clicked
    modalClose.addEventListener('click', () => {
        closeModal(modal);
    });

    // Close modal when cancel button is clicked
    cancelModalCancelBtn.addEventListener('click', () => {
        closeModal(modal);
        currentBookingId = null;
        currentBookingRoom = null;
    });

    // Confirm cancellation
    confirmCancelBtn.addEventListener('click', async () => {
        if (!currentBookingId) {
            alert('Error: Booking ID not found.');
            return;
        }

        // Disable button and show loading state
        confirmCancelBtn.disabled = true;
        const originalText = confirmCancelBtn.textContent;
        confirmCancelBtn.textContent = 'Processing...';

        try {
            // Send cancellation request to backend
            const formData = new FormData();
            formData.append('booking_id', currentBookingId);

            const response = await fetch('process_cancel_booking.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Close modal
                closeModal(modal);

                // Find the booking card and update it
                const bookingCard = document.querySelector(`[data-booking-id="${currentBookingId}"]`);
                if (bookingCard) {
                    // Update status in the card
                    bookingCard.setAttribute('data-status', 'cancelled');
                    
                    // Update status badge
                    const statusBadge = bookingCard.querySelector('.booking-status-badge');
                    if (statusBadge) {
                        statusBadge.setAttribute('data-status', 'cancelled');
                        statusBadge.textContent = 'Cancelled';
                    }

                    // Replace cancel button with cancelled note
                    const footer = bookingCard.querySelector('.booking-card-footer');
                    if (footer) {
                        footer.innerHTML = '<div class="booking-cancelled-note">This booking has been cancelled.</div>';
                    }

                    // Add fade and slide animation
                    bookingCard.classList.add('cancelled-animation');
                }

                // Show success notification
                showSuccessNotification(`Booking #${currentBookingId} has been cancelled successfully.`);

                // Reset variables
                currentBookingId = null;
                currentBookingRoom = null;
                confirmCancelBtn.disabled = false;
                confirmCancelBtn.textContent = originalText;
            } else {
                alert('Failed to cancel booking: ' + (result.message || 'Unknown error'));
                confirmCancelBtn.disabled = false;
                confirmCancelBtn.textContent = originalText;
            }
        } catch (error) {
            console.error('Cancellation error:', error);
            alert('An error occurred while cancelling the booking. Please try again.');
            confirmCancelBtn.disabled = false;
            confirmCancelBtn.textContent = originalText;
        }
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });

    // Modal functions
    function openModal(modalElement) {
        modalElement.classList.add('show');
        modalElement.style.display = 'flex';
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalElement) {
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        // Re-enable body scroll
        document.body.style.overflow = 'auto';
    }

    // Show success notification
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'success-notification';
        notification.textContent = '✓ ' + message;
        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
