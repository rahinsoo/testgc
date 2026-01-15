document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createCustomerModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.modal__close');

    // Ouvrir la modale
    openBtn.addEventListener('click', function() {
        modal.classList.add('modal--active');
    });

    // Fermer avec le bouton X
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('modal--active');
    });

    // Fermer en cliquant en dehors
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.remove('modal--active');
        }
    });

    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('modal--active')) {
            modal.classList.remove('modal--active');
        }
    });
});