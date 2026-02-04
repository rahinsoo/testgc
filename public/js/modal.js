// Récupération des éléments DOM
const modal = document.getElementById('customerModal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.querySelector('.modal__close');
const form = document.getElementById('customerForm');
const modalTitle = document.getElementById('modalTitle');
const submitBtn = document.getElementById('submitBtn');
const clientIdInput = document.getElementById('clientId');

// Ouverture du modal en mode CRÉATION
openBtn.addEventListener('click', () => {
    resetForm();
    modalTitle.textContent = 'Créer un nouveau client';
    submitBtn.textContent = 'Créer';
    form.action = '/customer/createCustomer';
    clientIdInput.value = '';
    modal.style.display = 'block';
});

// Fermeture du modal
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Fermeture si clic en dehors du modal
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Fonction pour ouvrir le modal en mode ÉDITION
async function openEditModal(id) {
    try {
        const response = await fetch(`/customer/get/${id}`);

        if (!response.ok) {
            throw new Error('Impossible de récupérer les données');
        }

        const client = await response.json();

        // Pré-remplir le formulaire
        document.getElementById('nom').value = client.nom || '';
        document.getElementById('numero_SIREN').value = client.numero_SIREN || '';
        document.getElementById('type').value = client.type || '';
        document.getElementById('information').value = client.information || '';
        document.getElementById('adresse').value = client.adresse || '';
        document.getElementById('is_facturable').checked = client.is_facturable == 1;

        // Configurer le formulaire en mode édition
        clientIdInput.value = id;
        form.action = `/customer/update/${id}`;
        modalTitle.textContent = 'Modifier le client';
        submitBtn.textContent = 'Mettre à jour';

        modal.style.display = 'block';

    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement des données du client');
    }
}

// Fonction pour supprimer un client
function deleteClient(id, nom) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer "${nom}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/customer/delete/${id}`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Réinitialiser le formulaire
function resetForm() {
    form.reset();
    document.getElementById('is_facturable').checked = true;
}