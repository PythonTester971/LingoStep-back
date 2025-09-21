document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector(".carousel-track");
  const slides = document.querySelectorAll(".carousel-track .card");
  let currentSlide = 0;
  const slidesToShow = 3; // Nombre de cartes visibles à la fois

  if (!track || slides.length === 0) {
    console.error('Carousel elements not found');
    return;
  }

  // Calculer le nombre maximum de décalages possibles
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

  // function adjustCarouselForScreenSize() {
  //   const container = document.querySelector(".carousel-container");
  //   const windowWidth = window.innerWidth;

  //   if (windowWidth < 768) {

  //     slides.forEach(slide => {
  //       slide.style.flex = "0 0 calc(100% - 1rem)";
  //       slide.style.minWidth = "calc(100% - 1rem)";
  //     });
  //   } else if (windowWidth < 992) {

  //     slides.forEach(slide => {
  //       slide.style.flex = "0 0 calc(50% - 1rem)";
  //       slide.style.minWidth = "calc(50% - 1rem)";
  //     });
  //   } else {

  //     slides.forEach(slide => {
  //       slide.style.flex = "0 0 calc(33.333% - 1rem)";
  //       slide.style.minWidth = "calc(33.333% - 1rem)";
  //     });
  //   }


  //   const visibleSlides = windowWidth < 768 ? 1 : (windowWidth < 992 ? 2 : 3);
  //   return Math.max(0, slides.length - visibleSlides);
  // }


  // window.addEventListener('resize', () => {
  //   currentSlide = 0; // Réinitialiser la position
  //   const newMaxSlides = adjustCarouselForScreenSize();
  //   if (newMaxSlides !== maxSlides) {
  //     maxSlides = newMaxSlides;
  //   }
  //   updateSlide();
  // });


  // adjustCarouselForScreenSize();
  // updateButtonStates();
});
