/* ── SLIDER PROXIMAMENTE ── */
  const slides = document.querySelectorAll('#calendario .proximamente-track .slide');
  const btnPrev = document.querySelector('#calendario #prevSlide');
  const btnNext = document.querySelector('#calendario #nextSlide');

  if (slides.length && btnPrev && btnNext) {
    let current = 0;
    const total = slides.length;

    function goTo(newIndex, direction) {
      const outgoing = slides[current];
      outgoing.classList.remove('slide--active');
      outgoing.style.opacity = '0';
      outgoing.style.transform = `translateX(${-30 * direction}px)`;

      current = ((newIndex % total) + total) % total;
      const incoming = slides[current];

      incoming.style.transition = 'none';
      incoming.style.opacity = '0';
      incoming.style.transform = `translateX(${30 * direction}px)`;

      incoming.getBoundingClientRect(); // Trigger reflow

      incoming.style.transition = '';
      incoming.classList.add('slide--active');
      incoming.style.transform = 'translateX(0)';
      incoming.style.opacity = '1';
    }

    btnNext.addEventListener('click', () => goTo(current + 1, 1));
    btnPrev.addEventListener('click', () => goTo(current - 1, -1));
    
    // Auto-play opcional (descomenta si quieres)
    // setInterval(() => goTo(current + 1, 1), 5000);
  }