document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.question-slide');
  const progress = document.getElementById('progress');
  let current = 0;

  function updateProgress(index) {
    progress.style.width = ((index) / slides.length * 100) + '%';
  }

  function showSlide(index) {
    slides.forEach((s, i) => {
      s.classList.toggle('hidden', i !== index);
    });
    updateProgress(index);
  }

  slides.forEach((slide, idx) => {
    const nextBtn = slide.querySelector('.next-btn');
    nextBtn.addEventListener('click', () => {
      const checked = slide.querySelector('input[type="radio"]:checked');
      if (!checked) {
        alert('Veuillez sélectionner une réponse.');
        return;
      }

      current++;
      if (current < slides.length) {
        showSlide(current);
      } else {
        document.getElementById('quiz-form').submit();
      }
    });
  });

  // Init
  showSlide(0);
});
