document.addEventListener('DOMContentLoaded', () => {
  // Récupération des éléments du carrousel
  const track = document.querySelector(".carousel-track");
  const slides = document.querySelectorAll(".carousel-track .card");
  let currentSlide = 0;
  const slidesToShow = 3;

  // Vérification de l'existence des éléments du carrousel
  if (!track || slides.length === 0) {
    console.error('Carousel elements not found');
    return;
  }

  // Calcul du nombre maximum de slides
  let maxSlides = Math.max(0, slides.length - slidesToShow);

  // Fonction pour mettre à jour la position du carrousel
  function updateSlide() {
    // Calcul de la largeur totale d'un slide (incluant les marges)
    const slideWidth = slides[0].offsetWidth + parseInt(getComputedStyle(slides[0]).marginLeft) +
      parseInt(getComputedStyle(slides[0]).marginRight);
    // Mise à jour de la transformation CSS pour déplacer le carrousel
    track.style.transform = `translateX(-${currentSlide * slideWidth}px)`;

    // Mise à jour de l'état des boutons (précédent/suivant) après le déplacement du carrousel
    updateButtonStates();
  }

  // Fonction pour mettre à jour l'état des boutons
  function updateButtonStates() {
    const prevBtn = document.getElementById("prev");
    const nextBtn = document.getElementById("next");

    // Désactivation des boutons si nécessaire
    if (prevBtn) {
      // Désactivation du bouton "prev" si on est au début
      if (currentSlide === 0) {
        prevBtn.classList.add('disabled');
      } else {
        prevBtn.classList.remove('disabled');
      }
    }

    if (nextBtn) {
      // Désactivation du bouton "next" si on est à la fin
      if (currentSlide >= maxSlides) {
        nextBtn.classList.add('disabled');
      } else {
        nextBtn.classList.remove('disabled');
      }
    }
  }

  const nextBtn = document.getElementById("next");
  if (nextBtn) {
    // Ajout de l'événement click pour le bouton "next"
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
