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

setInterval(changeSlide, 5000);

// 🌿 Navbar scroll effect
const navbar = document.getElementById("navbar");
window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

// Testimonials Slider
document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector('.testimonials-track');
  const cards = document.querySelectorAll('.testimonial-card');
  const dots = document.querySelectorAll('.nav-dot');
  
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

  // Handle window resize
  window.addEventListener('resize', () => {
    updateSliderPosition();
  });
});

// Add this to your script.js
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.room-slide');
    const prevBtn = document.querySelector('.prev-slide');
    const nextBtn = document.querySelector('.next-slide');
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

    // Price range slider
    const priceSlider = document.querySelector('.price-slider');
    const priceDisplay = document.querySelector('.price-display');
    
    priceSlider.addEventListener('input', (e) => {
        priceDisplay.textContent = `$0 - $${e.target.value}`;
    });
});
