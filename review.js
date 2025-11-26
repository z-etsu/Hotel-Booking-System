// review.js - Elegant Review Modal Logic

document.addEventListener('DOMContentLoaded', function () {
    console.log('=== REVIEW.JS LOADED ===');
    
    const modal = document.getElementById('reviewModal');
    const closeBtn = document.getElementById('closeReviewModal');
    const cancelBtn = document.getElementById('cancelReviewBtn');
    const reviewForm = document.getElementById('reviewForm');
    const starRating = document.getElementById('starRating');
    const reviewDesc = document.getElementById('reviewDescription');
    const reviewBookingId = document.getElementById('reviewBookingId');
    const feedback = document.getElementById('reviewFeedback');
    
    console.log('Modal elements found:', {
        modal: !!modal,
        reviewForm: !!reviewForm,
        starRating: !!starRating,
        reviewDesc: !!reviewDesc,
        reviewBookingId: !!reviewBookingId,
        feedback: !!feedback
    });
    
    let selectedStars = 0;

    // Render 5 stars
    function renderStars(rating = 0) {
        starRating.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.className = 'star' + (i <= rating ? ' filled' : '');
            star.innerHTML = '&#9733;'; // ★
            star.dataset.value = i;
            star.addEventListener('click', () => {
                selectedStars = i;
                renderStars(selectedStars);
                updateStarCounter();
            });
            starRating.appendChild(star);
        }
    }
    
    function updateStarCounter() {
        let counter = document.getElementById('starCounter');
        if (!counter) {
            counter = document.createElement('div');
            counter.id = 'starCounter';
            counter.className = 'star-counter';
            starRating.parentNode.insertBefore(counter, starRating.nextSibling);
        }
        if (selectedStars === 0) {
            counter.textContent = 'No rating selected';
            counter.style.color = '#999';
        } else {
            counter.textContent = `${selectedStars}/5 stars`;
            counter.style.color = '#b8860b';
        }
    }

    // Open modal
    document.querySelectorAll('.rate-review-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            selectedStars = 0;
            renderStars(0);
            updateStarCounter();
            reviewDesc.value = '';
            reviewBookingId.value = btn.dataset.bookingId;
            feedback.textContent = '';
            feedback.className = 'elegant-feedback';
            modal.classList.add('show');
        });
    });

    // Close modal
    function closeModal() {
        modal.classList.remove('show');
    }
    closeBtn.onclick = closeModal;
    cancelBtn.onclick = closeModal;
    window.onclick = function(event) {
        if (event.target === modal) closeModal();
    };

    // Submit review
    reviewForm.onsubmit = function (e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Form submitted');
        console.log('selectedStars:', selectedStars);
        console.log('reviewBookingId.value:', reviewBookingId.value);
        console.log('reviewDesc.value:', reviewDesc.value);
        
        if (selectedStars === 0) {
            console.log('ERROR: No stars selected');
            feedback.textContent = 'Please select a star rating.';
            feedback.className = 'elegant-feedback';
            return false;
        }
        
        const bookingId = reviewBookingId.value;
        const description = reviewDesc.value.trim();
        
        console.log('Submitting review:', { bookingId, stars: selectedStars, description: description.substring(0, 20) });
        
        if (!description || description.length < 5) {
            console.log('ERROR: Description too short, length:', description.length);
            feedback.textContent = 'Description must be at least 5 characters.';
            feedback.className = 'elegant-feedback';
            feedback.style.display = 'block';
            return false;
        }
        
        console.log('Validation passed, preparing fetch');
        feedback.textContent = 'Submitting your review...';
        feedback.className = 'elegant-feedback';
        feedback.style.display = 'block';
        
        console.log('About to fetch submit_review.php');
        console.log('Fetch body:', JSON.stringify({
            booking_id: bookingId,
            stars: selectedStars,
            description: description
        }));
        
        fetch('submit_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_id: bookingId,
                stars: selectedStars,
                description: description
            })
        })
        .then(res => {
            console.log('=== FETCH SUCCEEDED ===');
            console.log('Response status:', res.status);
            console.log('Response headers:', res.headers.get('content-type'));
            if (!res.ok) {
                return res.text().then(text => {
                    console.error('Error response text:', text);
                    throw new Error('HTTP ' + res.status + ': ' + text);
                });
            }
            return res.json().catch(err => {
                console.error('Failed to parse JSON:', err);
                throw new Error('Invalid response format');
            });
        })
        .then(data => {
            console.log('=== DATA RECEIVED ===');
            console.log('Response data:', data);
            if (data.success) {
                feedback.textContent = '✨ Thank you for your review!';
                feedback.className = 'elegant-feedback success';
                setTimeout(() => {
                    closeModal();
                    // Hide the button and show the review info
                    const btn = document.querySelector('.rate-review-btn[data-booking-id="' + bookingId + '"]');
                    if (btn) {
                        const parent = btn.parentElement;
                        btn.remove();
                        const reviewInfo = document.createElement('div');
                        reviewInfo.className = 'review-info';
                        reviewInfo.innerHTML = `<span class="review-stars">${'★'.repeat(selectedStars)}${'☆'.repeat(5-selectedStars)}</span> <span class="already-reviewed">Already reviewed</span>`;
                        parent.appendChild(reviewInfo);
                    }
                }, 1200);
            } else {
                feedback.textContent = data.message || 'Failed to submit review.';
                feedback.className = 'elegant-feedback';
            }
        })
        .catch(err => {
            console.error('=== FETCH ERROR ===');
            console.error('Error:', err);
            console.error('Error message:', err.message);
            feedback.textContent = 'Error: ' + err.message;
            feedback.className = 'elegant-feedback';
        });
        
        return false;
    };

    // Initial render
    renderStars(0);
    updateStarCounter();
});


// Optional: Add some CSS for the modal and stars if not already present
