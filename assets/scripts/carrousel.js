const track = document.querySelector(".carousel-track");
const slides = document.querySelectorAll(".carousel-track .card");
let currentSlide = 0;

function updateSlide() {
  const slideWidth = slides[0].clientWidth;
  track.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
}

document.getElementById("next").addEventListener("click", () => {
  currentSlide = (currentSlide + 1) % slides.length;
  updateSlide();
});

document.getElementById("prev").addEventListener("click", () => {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  updateSlide();
});
