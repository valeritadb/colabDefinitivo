document.addEventListener('DOMContentLoaded', () => {
    /* ── Lightbox galería ── */
    const galItems = document.querySelectorAll('.gal-item');
    if (!galItems.length) return;

    const galImages = Array.from(galItems).map(item => {
        const img = item.querySelector('img');
        return { src: img.src, alt: img.alt };
    });

    let lbIndex = 0;
    let lbEl = null;
    let lbImg = null;
    let lbCounter = null;

    function openLightbox(index) {
        lbIndex = index;

        lbEl = document.createElement('div');
        lbEl.className = 'gal-lightbox';
        lbEl.setAttribute('role', 'dialog');
        lbEl.setAttribute('aria-modal', 'true');
        lbEl.setAttribute('aria-label', 'Galería de imágenes');

        const inner = document.createElement('div');
        inner.className = 'gal-lightbox-inner';

        const closeBtn = document.createElement('button');
        closeBtn.className = 'gal-lb-close';
        closeBtn.setAttribute('aria-label', 'Cerrar galería');
        closeBtn.textContent = '×';
        closeBtn.addEventListener('click', closeLightbox);

        const prevBtn = document.createElement('button');
        prevBtn.className = 'gal-lb-arrow gal-lb-prev';
        prevBtn.setAttribute('aria-label', 'Imagen anterior');
        prevBtn.innerHTML = '&#8592;';
        prevBtn.addEventListener('click', () => navigateLightbox(-1));

        const nextBtn = document.createElement('button');
        nextBtn.className = 'gal-lb-arrow gal-lb-next';
        nextBtn.setAttribute('aria-label', 'Imagen siguiente');
        nextBtn.innerHTML = '&#8594;';
        nextBtn.addEventListener('click', () => navigateLightbox(1));

        lbImg = document.createElement('img');
        lbImg.src = galImages[lbIndex].src;
        lbImg.alt = galImages[lbIndex].alt;

        lbCounter = document.createElement('div');
        lbCounter.className = 'gal-lb-counter';
        updateCounter();

        inner.appendChild(prevBtn);
        inner.appendChild(lbImg);
        inner.appendChild(nextBtn);
        lbEl.appendChild(closeBtn);
        lbEl.appendChild(inner);
        lbEl.appendChild(lbCounter);

        document.body.appendChild(lbEl);
        document.body.style.overflow = 'hidden';

        lbEl.addEventListener('click', e => { if (e.target === lbEl) closeLightbox(); });
        document.addEventListener('keydown', handleKeydown);
    }

    function closeLightbox() {
        if (!lbEl) return;
        document.body.removeChild(lbEl);
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleKeydown);
        lbEl = null; lbImg = null; lbCounter = null;
    }

    function navigateLightbox(dir) {
        if (!lbImg) return;
        const exitClass = dir === 1 ? 'lb-exit-left' : 'lb-exit-right';
        lbImg.classList.add(exitClass);
        setTimeout(() => {
            lbIndex = ((lbIndex + dir) % galImages.length + galImages.length) % galImages.length;
            lbImg.classList.remove(exitClass);
            lbImg.classList.add('lb-enter');
            lbImg.src = galImages[lbIndex].src;
            lbImg.alt = galImages[lbIndex].alt;
            updateCounter();
            lbImg.getBoundingClientRect(); // forzar reflow
            lbImg.classList.remove('lb-enter');
        }, 280);
    }

    function updateCounter() {
        if (lbCounter) lbCounter.textContent = `${lbIndex + 1} / ${galImages.length}`;
    }

    function handleKeydown(e) {
        if (e.key === 'ArrowRight') navigateLightbox(1);
        if (e.key === 'ArrowLeft')  navigateLightbox(-1);
        if (e.key === 'Escape')     closeLightbox();
    }

    galItems.forEach((item, i) => {
        item.addEventListener('click', () => openLightbox(i));
    });

});