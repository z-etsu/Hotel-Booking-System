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
