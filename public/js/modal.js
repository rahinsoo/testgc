// Récupération des éléments DOM
const modal = document.getElementById('customerModal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.querySelector('.modal__close');
const form = document.getElementById('customerForm');
const modalTitle = document.getElementById('modalTitle');
const submitBtn = document.getElementById('submitBtn');
const clientIdInput = document.getElementById('clientId');

// Éléments pour l'API SIRENE
const siretInput = document.getElementById('numero_SIRET');
const nomInput = document.getElementById('nom');
const adresseInput = document.getElementById('adresse');
const siretLoader = document.getElementById('siretLoader');
const siretError = document.getElementById('siretError');
const siretSuccess = document.getElementById('siretSuccess');

// Configuration API INSEE (⚠️ vous devrez obtenir un token)--> modification pour le mettre dans le header
// -> X-INSEE-Api-Key-Integration: f03b71b1-35dc-4291-bb71-b135dcd2911a
const API_INSEE_BASE_URL = 'https://api.insee.fr/api-sirene/3.11';
const API_INSEE_TOKEN = 'VOTRE_TOKEN_ICI'; // À remplacer par votre token --> système dans le header -> mettre la clé

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

// Fermeture si appuie touche échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        modal.style.display = 'none';
    }
});

// Fermeture si clic en dehors du modal
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Fonction pour rechercher les informations via l'API SIRENE

async function rechercherEntrepriseBySiret(siret) {
    // Validation basique du SIRET (14 chiffres)
    if (!/^\d{14}$/.test(siret.replace(/\s/g, ''))) {
        throw new Error('Le SIRET doit contenir 14 chiffres');
    }

    const siretClean = siret.replace(/\s/g, '');
    // Appel vers l'API backend au lieu de l'API INSEE directement
    const url = `/api/sirene/siret/${siretClean}`;

    const response = await fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    });

    if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        if (response.status === 404) {
            throw new Error('Entreprise non trouvée');
        }
        throw new Error(errorData.error || 'Erreur lors de la recherche');
    }

    const data = await response.json();

    return {
        nom: data.etablissement.uniteLegale.denominationUniteLegale ||
            `${data.etablissement.uniteLegale.prenom1UniteLegale} ${data.etablissement.uniteLegale.nomUniteLegale}`,
        adresse: formatAdresse(data.etablissement.adresseEtablissement),
        siren: data.etablissement.siren,
        siret: data.etablissement.siret
    };
}

// Fonction pour formater l'adresse depuis l'API
function formatAdresse(adresseObj) {
    if (!adresseObj) return '';

    const parts = [
        adresseObj.numeroVoieEtablissement,
        adresseObj.indiceRepetitionEtablissement,
        adresseObj.typeVoieEtablissement,
        adresseObj.libelleVoieEtablissement,
        adresseObj.complementAdresseEtablissement,
        adresseObj.codePostalEtablissement,
        adresseObj.libelleCommuneEtablissement
    ];

    return parts
        .filter(part => part && part.trim() !== '')
        .join(' ')
        .replace(/\s+/g, ' ')
        .trim();
}

// Écouter les changements du champ SIRET
let siretTimeout;
siretInput.addEventListener('input', (e) => {
    const siret = e.target.value.trim();

    // Annuler le précédent timeout
    clearTimeout(siretTimeout);

    // Réinitialiser les messages
    siretError.style.display = 'none';
    siretSuccess.style.display = 'none';

    // Attendre que l'utilisateur ait fini de taper (debounce de 800ms)
    siretTimeout = setTimeout(async () => {
        if (siret.length === 14) {
            const data = await rechercherEntrepriseBySiret(siret);

            if (data) {
                // Auto-complétion des champs
                nomInput.value = data.nom;
                adresseInput.value = data.adresse;

                siretSuccess.textContent = `✅ Entreprise trouvée: ${data.nom}`;
                siretSuccess.style.display = 'block';
            }
        }
    }, 800);
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
        document.getElementById('numero_SIRET').value = client.numero_SIRET || '';
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