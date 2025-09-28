document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector(".carousel-track");
  const slides = document.querySelectorAll(".carousel-track .card");
  let currentSlide = 0;
  const slidesToShow = 3;

  if (!track || slides.length === 0) {
    console.error('Carousel elements not found');
    return;
  }

  let maxSlides = Math.max(0, slides.length - slidesToShow);

  function updateSlide() {
    const slideWidth = slides[0].offsetWidth + parseInt(getComputedStyle(slides[0]).marginLeft) +
      parseInt(getComputedStyle(slides[0]).marginRight);
    track.style.transform = `translateX(-${currentSlide * slideWidth}px)`;

    updateButtonStates();
  }

  function updateButtonStates() {
    const prevBtn = document.getElementById("prev");
    const nextBtn = document.getElementById("next");

    if (prevBtn) {
      if (currentSlide === 0) {
        prevBtn.classList.add('disabled');
      } else {
        prevBtn.classList.remove('disabled');
      }
    }

    if (nextBtn) {
      if (currentSlide >= maxSlides) {
        nextBtn.classList.add('disabled');
      } else {
        nextBtn.classList.remove('disabled');
      }
    }
  }

  const nextBtn = document.getElementById("next");
  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      if (currentSlide < maxSlides) {
        currentSlide++;
        updateSlide();
      }
    });
  }

  const prevBtn = document.getElementById("prev");
  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      if (currentSlide > 0) {
        currentSlide--;
        updateSlide();
      }
    });
  }
});
