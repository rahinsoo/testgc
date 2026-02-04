// Fonction pour formater l'adresse depuis la réponse API
function formatAdresse(adresseEtablissement) {
    const parts = [
        adresseEtablissement.numeroVoieEtablissement,
        adresseEtablissement.indiceRepetitionEtablissement,
        adresseEtablissement.typeVoieEtablissement,
        adresseEtablissement.libelleVoieEtablissement,
        adresseEtablissement.complementAdresseEtablissement
    ].filter(Boolean).join(' ');
    
    const ville = [
        adresseEtablissement.codePostalEtablissement,
        adresseEtablissement.libelleCommuneEtablissement
    ].filter(Boolean).join(' ');
    
    return `${parts}, ${ville}`;
}

// Fonction pour rechercher l'entreprise par SIRET
async function rechercherEntrepriseBySiret(siret) {
    // Validation basique du SIRET (14 chiffres)
    if (!/^\d{14}$/.test(siret.replace(/\s/g, ''))) {
        throw new Error('Le SIRET doit contenir 14 chiffres');
    }
    
    const siretClean = siret.replace(/\s/g, '');
    const url = `https://api.insee.fr/api-sirene/3.11/siret/${siretClean}`;
    
    const response = await fetch(url, {
        headers: {
            'Authorization': 'Bearer VOTRE_TOKEN_API', // À configurer
            'Accept': 'application/json'
        }
    });
    
    if (!response.ok) {
        if (response.status === 404) {
            throw new Error('Entreprise non trouvée');
        }
        throw new Error('Erreur lors de la recherche');
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

// Gestionnaire d'événement pour le champ SIRET
document.addEventListener('DOMContentLoaded', function() {
    const siretInput = document.getElementById('client_siret');
    const nomInput = document.getElementById('client_nom');
    const adresseInput = document.getElementById('client_adresse');
    const searchBtn = document.getElementById('siret_search_btn');
    const loader = document.getElementById('siret_loader');
    const errorMsg = document.getElementById('siret_error');
    
    async function handleSiretSearch() {
        const siret = siretInput.value.trim();
        
        if (!siret) return;
        
        // Afficher le loader, masquer les erreurs
        loader?.classList.remove('d-none');
        errorMsg?.classList.add('d-none');
        searchBtn?.setAttribute('disabled', 'disabled');
        
        try {
            const entreprise = await rechercherEntrepriseBySiret(siret);
            
            // Remplir les champs
            nomInput.value = entreprise.nom;
            adresseInput.value = entreprise.adresse;
            
            // Feedback visuel positif
            siretInput.classList.remove('is-invalid');
            siretInput.classList.add('is-valid');
            
        } catch (error) {
            // Afficher l'erreur
            if (errorMsg) {
                errorMsg.textContent = error.message;
                errorMsg.classList.remove('d-none');
            }
            siretInput.classList.add('is-invalid');
            
        } finally {
            loader?.classList.add('d-none');
            searchBtn?.removeAttribute('disabled');
        }
    }
    
    // Événements
    searchBtn?.addEventListener('click', handleSiretSearch);
    siretInput?.addEventListener('blur', handleSiretSearch);
    
    // Nettoyer les états lors de la modification manuelle
    siretInput?.addEventListener('input', function() {
        this.classList.remove('is-valid', 'is-invalid');
        errorMsg?.classList.add('d-none');
    });
});