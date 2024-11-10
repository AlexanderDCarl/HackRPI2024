let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

function showNextSlide() {
    // Hide current slide
    slides[currentSlide].classList.remove('active');

    // Move to the next slide
    currentSlide = (currentSlide + 1) % totalSlides;

    // Show the next slide
    slides[currentSlide].classList.add('active');
}

// Automatically cycle through slides every 3 seconds
setInterval(showNextSlide, 5000);