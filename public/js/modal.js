document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createCustomerModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.modal__close');
    const form = document.getElementById('createCustomerForm');

    // Reopen modal if there are server-side validation errors
    const hasServerErrors = document.querySelectorAll('.form-group .error-message').length > 0;
    if (hasServerErrors) {
        modal.classList.add('modal--active');
    }

    // Ouvrir la modale
    openBtn.addEventListener('click', function() {
        modal.classList.add('modal--active');
    });

    // Fermer avec le bouton X
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('modal--active');
        clearErrors();
    });

    // Fermer en cliquant en dehors
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.remove('modal--active');
            clearErrors();
        }
    });

    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('modal--active')) {
            modal.classList.remove('modal--active');
            clearErrors();
        }
    });

    // Validation du formulaire
    form.addEventListener('submit', function(event) {
        clearClientErrors();
        
        let isValid = true;
        const nom = document.getElementById('nom').value.trim();
        const siret = document.getElementById('numero_SIRET').value.trim();
        const type = document.getElementById('type').value.trim();
        const adresse = document.getElementById('adresse').value.trim();

        // Validation du nom (2-100 caractères, lettres, espaces, tirets)
        if (!nom || nom.length < 2 || nom.length > 100) {
            showError('nom', 'Le nom doit contenir entre 2 et 100 caractères');
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(nom)) {
            showError('nom', 'Le nom ne peut contenir que des lettres, espaces et tirets');
            isValid = false;
        }

        // Validation SIRET (14 chiffres)
        if (!siret || !/^\d{14}$/.test(siret)) {
            showError('numero_SIRET', 'Le numéro SIRET doit contenir exactement 14 chiffres');
            isValid = false;
        }

        // Validation du type (non vide, 2-50 caractères)
        if (!type || type.length < 2 || type.length > 50) {
            showError('type', 'Le type doit contenir entre 2 et 50 caractères');
            isValid = false;
        }

        // Validation de l'adresse (5-200 caractères)
        if (!adresse || adresse.length < 5 || adresse.length > 200) {
            showError('adresse', 'L\'adresse doit contenir entre 5 et 200 caractères');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const formGroup = field.closest('.form-group');
        
        // Ajouter une classe d'erreur au champ
        field.classList.add('field-error');
        
        // Check if error message doesn't already exist
        const existingError = formGroup.querySelector('.error-message.client-error');
        if (!existingError) {
            // Créer et ajouter le message d'erreur
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message client-error';
            errorDiv.textContent = message;
            formGroup.appendChild(errorDiv);
        }
    }

    function clearClientErrors() {
        // Supprimer uniquement les messages d'erreur client (pas ceux du serveur)
        const errorMessages = document.querySelectorAll('.error-message.client-error');
        errorMessages.forEach(msg => msg.remove());
        
        // Retirer la classe d'erreur de tous les champs
        const errorFields = document.querySelectorAll('.field-error');
        errorFields.forEach(field => field.classList.remove('field-error'));
    }

    function clearErrors() {
        // Supprimer tous les messages d'erreur (client et serveur)
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());
        
        // Retirer la classe d'erreur de tous les champs
        const errorFields = document.querySelectorAll('.field-error');
        errorFields.forEach(field => field.classList.remove('field-error'));
    }
});