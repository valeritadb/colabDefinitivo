/* =====================================================
   FICHA DE PRODUCTO — Slider de fotos
===================================================== */

document.addEventListener('DOMContentLoaded', () => {

    const slides = document.querySelectorAll('.ficha-slide');
    const dots   = document.querySelectorAll('.ficha-dot');
    const prev   = document.getElementById('fichaPrev');
    const next   = document.getElementById('fichaNext');
    let current  = 0;

    function goTo(index) {
        slides[current].classList.remove('ficha-slide--active');
        dots[current].classList.remove('ficha-dot--active');
        current = (index + slides.length) % slides.length;
        slides[current].classList.add('ficha-slide--active');
        dots[current].classList.add('ficha-dot--active');
    }

    prev.addEventListener('click', () => goTo(current - 1));
    next.addEventListener('click', () => goTo(current + 1));

    /* Navegación por teclado */
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft')  goTo(current - 1);
        if (e.key === 'ArrowRight') goTo(current + 1);
    });

});
