// 🌄 Slideshow + Quotes
const slides = document.querySelectorAll(".slide");
const quotes = [
  "Feel Relax & Enjoy Your Luxuriousness",
  "Escape the Ordinary, Stay Extraordinary",
  "Where Comfort Meets Elegance"
];
const quoteElement = document.getElementById("quote");
let current = 0;

function changeSlide() {
  if (slides.length === 0 || !quoteElement) return; // Skip if not on homepage
  slides[current].classList.remove("active");
  current = (current + 1) % slides.length;
  slides[current].classList.add("active");

  // Fade quote text
  quoteElement.style.opacity = 0;
  setTimeout(() => {
    quoteElement.textContent = quotes[current];
    quoteElement.style.opacity = 1;
  }, 800);
}

if (slides.length > 0) {
  setInterval(changeSlide, 5000);
}
// 🌿 Navbar scroll effect is handled in navbar.php
// No need for duplicate code here

// Testimonials Slider
document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector('.testimonials-track');
  const cards = document.querySelectorAll('.testimonial-card');
  const dots = document.querySelectorAll('.nav-dot');
  
  // Skip if testimonials not on this page
  if (!track || cards.length === 0) return;
  
  let isDragging = false;
  let startPos = 0;
  let currentTranslate = 0;
  let prevTranslate = 0;
  let currentIndex = 0;
  let animationID = 0;

  // Prevent default behavior on mouse down
  cards.forEach(card => {
    card.addEventListener('dragstart', e => e.preventDefault());
  });

  // Touch events
  track.addEventListener('touchstart', touchStart);
  track.addEventListener('touchmove', touchMove);
  track.addEventListener('touchend', touchEnd);

  // Mouse events
  track.addEventListener('mousedown', touchStart);
  track.addEventListener('mousemove', touchMove);
  track.addEventListener('mouseup', touchEnd);
  track.addEventListener('mouseleave', touchEnd);

  // Navigation dots
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      currentIndex = index;
      updateSliderPosition();
      updateDots();
    });
  });

  // Navigation arrows
  const prevArrow = document.querySelector('.prev-arrow');
  const nextArrow = document.querySelector('.next-arrow');

  prevArrow.addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex--;
      updateSliderPosition();
      updateDots();
    }
  });

  nextArrow.addEventListener('click', () => {
    if (currentIndex < cards.length - 1) {
      currentIndex++;
      updateSliderPosition();
      updateDots();
    }
  });

  function touchStart(event) {
    isDragging = true;
    startPos = getPositionX(event);
    animationID = requestAnimationFrame(animation);
    track.style.cursor = 'grabbing';
  }

  function touchMove(event) {
    if (!isDragging) return;
    const currentPosition = getPositionX(event);
    currentTranslate = prevTranslate + currentPosition - startPos;
  }

  function touchEnd() {
    isDragging = false;
    cancelAnimationFrame(animationID);
    track.style.cursor = 'grab';

    const moveBy = currentTranslate - prevTranslate;
    if (Math.abs(moveBy) > 100) {
      if (moveBy < 0 && currentIndex < cards.length - 1) currentIndex += 1;
      if (moveBy > 0 && currentIndex > 0) currentIndex -= 1;
    }

    updateSliderPosition();
    updateDots();
  }

  function getPositionX(event) {
    return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
  }

  function animation() {
    setSliderPosition();
    if (isDragging) requestAnimationFrame(animation);
  }

  function setSliderPosition() {
    track.style.transform = `translateX(${currentTranslate}px)`;
  }

  function updateSliderPosition() {
    cards.forEach((card, index) => {
      card.classList.remove('active', 'prev', 'next');
      if (index === currentIndex) {
        card.classList.add('active');
      } else if (index === currentIndex - 1) {
        card.classList.add('prev');
      } else if (index === currentIndex + 1) {
        card.classList.add('next');
      }
    });
  }

  function updateDots() {
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
  }
  
  // Initialize first card as active
  updateSliderPosition();
  updateDots();

  // Handle window resize
  window.addEventListener('resize', () => {
    updateSliderPosition();
  });
});

// Book Now button handlers for index page - redirect to booking page
document.addEventListener('DOMContentLoaded', function() {
    // Handle both on-page clicks and direct href navigation
    document.querySelectorAll('.book-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // If button is disabled, prevent navigation
            if (this.disabled || this.classList.contains('disabled')) {
                e.preventDefault();
                return;
            }
            
            e.preventDefault();
            // Get the room name from the card
            const roomName = this.closest('.room-card').querySelector('h3').textContent;
            // Convert room name to URL slug
            const roomSlug = roomName.toLowerCase().replace(/\s+/g, '-');
            // Navigate to booking page with room parameter
            window.location.href = `booking.php?room=${encodeURIComponent(roomSlug)}`;
        });
    });
});

// Add this to your script.js
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.room-slide');
    const prevBtn = document.querySelector('.prev-slide');
    const nextBtn = document.querySelector('.next-slide');
    
    // Only initialize if these elements exist
    if (prevBtn && nextBtn && slides.length > 0) {
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }

        prevBtn.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        });

        nextBtn.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        });
    }

    // Price range slider
    const priceSlider = document.querySelector('.price-slider');
    const priceDisplay = document.querySelector('.price-display');
    
    if (priceSlider && priceDisplay) {
        priceSlider.addEventListener('input', (e) => {
            priceDisplay.textContent = `$0 - $${e.target.value}`;
        });
    }
});

// ✨ Smooth Page Transition Effect - Blur Out on Link Click
document.addEventListener('DOMContentLoaded', () => {
    // Handle all internal navigation links with smooth blur transition
    document.querySelectorAll('a:not(.amenity-item)').forEach(link => {
        link.addEventListener('click', (e) => {
            // Skip if link has special attributes (target="_blank", mailto, etc.)
            if (link.target === '_blank' || link.href.includes('mailto:') || link.href.includes('tel:')) {
                return;
            }

            // Skip anchor links (hash links on same page)
            if (link.href.startsWith('#') || link.href.endsWith('#') || link.href.includes('#')) {
                return;
            }

            // Check if it's an internal link (same domain)
            const currentDomain = window.location.origin;
            if (!link.href.startsWith(currentDomain)) {
                return;
            }

            // Prevent default navigation
            e.preventDefault();

            const targetUrl = link.href;
            const body = document.body;

            // Apply blur-out animation
            body.style.transition = 'opacity 0.4s ease-out, filter 0.4s ease-out';
            body.style.opacity = '0';
            body.style.filter = 'blur(10px)';

            // Navigate after animation completes
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 400);
        });
    });

    // Handle form submissions with transition
    document.querySelectorAll('form').forEach(form => {
        // Skip review form - it handles its own submission
        if (form.id === 'reviewForm') return;
        
        form.addEventListener('submit', () => {
            const body = document.body;
            body.style.transition = 'opacity 0.4s ease-out, filter 0.4s ease-out';
            body.style.opacity = '0';
            body.style.filter = 'blur(10px)';
        });
    });
});
