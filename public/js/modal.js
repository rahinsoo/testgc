// Récupération des éléments DOM
const modal = document.getElementById('customerModal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.querySelector('.modal__close');
const form = document.getElementById('customerForm');
const modalTitle = document.getElementById('modalTitle');
const submitBtn = document.getElementById('submitBtn');
const clientIdInput = document.getElementById('clientId');

// ===================================
// Configuration API INSEE SIRENE
// ===================================
const API_INSEE_BASE_URL = 'https://api.insee.fr/api-sirene/3.11';
const API_INSEE_TOKEN = 'VOTRE_TOKEN_ICI'; // ⚠️ À configurer par l'utilisateur

// Variable pour gérer le debounce
let debounceTimer = null;

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
    // Réinitialiser le message de feedback SIRET
    const feedbackElement = document.getElementById('siret-feedback');
    if (feedbackElement) {
        feedbackElement.textContent = '';
        feedbackElement.className = 'feedback-message';
    }
}

// ===================================
// Fonctions pour l'API INSEE SIRENE
// ===================================

/**
 * Formate l'objet adresse retourné par l'API INSEE en une chaîne lisible
 * @param {Object} adresseObj - Objet adresse de l'API INSEE
 * @returns {string} Adresse formatée
 */
function formatAdresse(adresseObj) {
    const parties = [];
    
    // Numéro de voie
    if (adresseObj.numeroVoieEtablissement) {
        parties.push(adresseObj.numeroVoieEtablissement);
    }
    
    // Type de voie (RUE, AVENUE, etc.)
    if (adresseObj.typeVoieEtablissement) {
        parties.push(adresseObj.typeVoieEtablissement);
    }
    
    // Libellé de voie (nom de la rue)
    if (adresseObj.libelleVoieEtablissement) {
        parties.push(adresseObj.libelleVoieEtablissement);
    }
    
    // Code postal
    if (adresseObj.codePostalEtablissement) {
        parties.push(adresseObj.codePostalEtablissement);
    }
    
    // Commune
    if (adresseObj.libelleCommuneEtablissement) {
        parties.push(adresseObj.libelleCommuneEtablissement);
    }
    
    return parties.join(' ');
}

/**
 * Récupère les données d'un établissement via l'API INSEE SIRENE
 * @param {string} siret - Numéro SIRET (14 chiffres)
 * @returns {Promise<Object>} Objet contenant nom, adresse et siren
 */
async function fetchSiretData(siret) {
    // Validation du format SIRET (14 chiffres)
    if (!/^\d{14}$/.test(siret)) {
        throw new Error('Le SIRET doit contenir exactement 14 chiffres');
    }
    
    // Vérifier que le token est configuré
    if (API_INSEE_TOKEN === 'VOTRE_TOKEN_ICI') {
        throw new Error('Le token API INSEE n\'est pas configuré. Veuillez modifier la variable API_INSEE_TOKEN dans modal.js');
    }
    
    // Appel à l'API INSEE
    const url = `${API_INSEE_BASE_URL}/siret/${siret}`;
    const response = await fetch(url, {
        headers: {
            'Authorization': `Bearer ${API_INSEE_TOKEN}`,
            'Accept': 'application/json'
        }
    });
    
    // Gestion des erreurs HTTP
    if (response.status === 404) {
        throw new Error('SIRET introuvable dans la base INSEE');
    }
    
    if (response.status === 401) {
        throw new Error('Token API invalide ou expiré');
    }
    
    if (!response.ok) {
        throw new Error(`Erreur API INSEE (${response.status})`);
    }
    
    // Extraction des données
    const data = await response.json();
    const etablissement = data.etablissement;
    
    if (!etablissement) {
        throw new Error('Données d\'établissement manquantes dans la réponse');
    }
    
    // Construction de l'objet de retour
    return {
        nom: etablissement.uniteLegale?.denominationUniteLegale || '',
        adresse: formatAdresse(etablissement.adresseEtablissement || {})
    };
}

/**
 * Affiche un message de feedback à l'utilisateur
 * @param {string} message - Message à afficher
 * @param {string} type - Type de message ('loading', 'success', 'error')
 * @param {boolean} showLoader - Afficher un loader animé
 */
function showFeedback(message, type = '', showLoader = false) {
    const feedbackElement = document.getElementById('siret-feedback');
    if (!feedbackElement) return;
    
    feedbackElement.className = `feedback-message ${type}`;
    
    if (showLoader) {
        feedbackElement.innerHTML = `${message} <span class="loader"></span>`;
    } else {
        feedbackElement.textContent = message;
    }
}

/**
 * Gère la saisie du SIRET et déclenche l'auto-complétion
 */
async function handleSiretInput() {
    const siretInput = document.getElementById('numero_SIRET');
    const siret = siretInput.value.trim();
    
    // Réinitialiser le feedback si le champ est vide ou incomplet
    if (siret.length < 14) {
        showFeedback('', '');
        return;
    }
    
    // Validation du format (14 chiffres)
    if (!/^\d{14}$/.test(siret)) {
        showFeedback('❌ Le SIRET doit contenir exactement 14 chiffres', 'error');
        return;
    }
    
    // Afficher le loader
    showFeedback('🔍 Recherche en cours...', 'loading', true);
    
    try {
        // Appel à l'API INSEE
        const data = await fetchSiretData(siret);
        
        // Auto-remplir les champs
        if (data.nom) {
            document.getElementById('nom').value = data.nom;
        }
        
        if (data.adresse) {
            document.getElementById('adresse').value = data.adresse;
        }
        
        // Afficher le message de succès
        showFeedback(`✅ Entreprise trouvée: ${data.nom}`, 'success');
        
    } catch (error) {
        console.error('Erreur lors de la récupération des données SIRET:', error);
        showFeedback(`❌ ${error.message}`, 'error');
    }
}

// ===================================
// Écouteur d'événement sur le champ SIRET
// ===================================

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
    const siretInput = document.getElementById('numero_SIRET');
    
    if (siretInput) {
        // Écouter les changements avec debounce de 800ms
        siretInput.addEventListener('input', () => {
            // Annuler le timer précédent
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }
            
            // Créer un nouveau timer
            debounceTimer = setTimeout(() => {
                handleSiretInput();
            }, 800);
        });
    }
});