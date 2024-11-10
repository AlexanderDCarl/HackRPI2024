let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

const showNextSlide = () => {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % totalSlides;
    slides[currentSlide].classList.add('active');
}
setInterval(showNextSlide, 5000);

window.addEventListener('scroll', () => {
    document.getElementById('info').querySelectorAll('*').forEach((card) => {
        const divRect = card.getBoundingClientRect();
        let offset = Math.atan(4 - (divRect.top/100));
        card.style.boxShadow = `#111 1rem ${offset}rem 15px `;
    });
});

window.querySearchBar = (str) => window.location.href = `./html/search?q=${str}`;

$(document).ready(() => {
    $('#search-input').on('focus', () => $('#dropdown-menu').fadeIn(200));
    $('#search-input').on('blur', () => $('#dropdown-menu').fadeOut(400));
});