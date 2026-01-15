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

        // Validation du nom (using validation config)
        if (!nom || nom.length < VALIDATION_LENGTHS.name.min || nom.length > VALIDATION_LENGTHS.name.max) {
            showError('nom', `Le nom doit contenir entre ${VALIDATION_LENGTHS.name.min} et ${VALIDATION_LENGTHS.name.max} caractères`);
            isValid = false;
        } else if (!VALIDATION_PATTERNS.name.test(nom)) {
            showError('nom', 'Le nom ne peut contenir que des lettres, espaces et tirets');
            isValid = false;
        }

        // Validation SIRET (using validation config)
        if (!siret || !VALIDATION_PATTERNS.siret.test(siret)) {
            showError('numero_SIRET', 'Le numéro SIRET doit contenir exactement 14 chiffres');
            isValid = false;
        }

        // Validation du type (using validation config)
        if (!type || type.length < VALIDATION_LENGTHS.type.min || type.length > VALIDATION_LENGTHS.type.max) {
            showError('type', `Le type doit contenir entre ${VALIDATION_LENGTHS.type.min} et ${VALIDATION_LENGTHS.type.max} caractères`);
            isValid = false;
        }

        // Validation de l'adresse (using validation config)
        if (!adresse || adresse.length < VALIDATION_LENGTHS.adresse.min || adresse.length > VALIDATION_LENGTHS.adresse.max) {
            showError('adresse', `L'adresse doit contenir entre ${VALIDATION_LENGTHS.adresse.min} et ${VALIDATION_LENGTHS.adresse.max} caractères`);
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