/* =====================================================
   SERVICIOS — Desplegables de opciones
===================================================== */

document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.srv-dropdown-btn').forEach(btn => {

        const listId = btn.getAttribute('aria-controls');
        const list   = document.getElementById(listId);

        btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';

            btn.setAttribute('aria-expanded', !expanded);
            btn.classList.toggle('srv-dropdown-btn--abierto', !expanded);
            list.classList.toggle('srv-dropdown-list--visible', !expanded);
        });
    });

});