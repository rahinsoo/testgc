# Documentation de Sécurité - Formulaire de Création de Client

## Résumé des Modifications

Ce document décrit les mesures de sécurité et les améliorations apportées au formulaire de création de client.

## 1. Architecture des Fichiers JavaScript

### Problème Résolu
- Le fichier JavaScript `modal.js` est correctement placé dans `public/js/modal.js`
- Il est accessible publiquement via le chemin `/js/modal.js`
- Le serveur web utilise le dossier `public/` comme document root

### Vérification
Le fichier est chargé via la balise dans `views/partials/header.php`:
```html
<script src="/js/modal.js" defer></script>
```

## 2. Validation Côté Client (JavaScript)

### Fonctionnalités Implémentées
- **Validation en temps réel** lors de la soumission du formulaire
- **Messages d'erreur clairs** et contextuels pour chaque champ
- **Styles visuels** pour les champs en erreur

### Règles de Validation Client

#### Nom de l'entreprise
- **Longueur**: 2 à 100 caractères
- **Format**: Lettres (avec accents), espaces, tirets et apostrophes uniquement
- **Expression régulière**: `/^[a-zA-ZÀ-ÿ\s\-']+$/`

#### Numéro SIRET
- **Format**: Exactement 14 chiffres
- **Expression régulière**: `/^\d{14}$/`

#### Type d'entreprise
- **Longueur**: 2 à 50 caractères
- **Requis**: Oui

#### Adresse
- **Longueur**: 5 à 200 caractères
- **Requis**: Oui

#### Information (optionnel)
- **Longueur maximale**: 500 caractères
- **Requis**: Non

### Expérience Utilisateur
- La modale se rouvre automatiquement si des erreurs serveur sont détectées
- Les erreurs du serveur persistent jusqu'à correction
- Les erreurs client sont effacées avant chaque nouvelle validation
- Fermeture avec touche Escape ou clic en dehors de la modale

## 3. Validation Côté Serveur (PHP)

### Classe Validator (`src/Helper/Validator.php`)

Fournit des méthodes de validation robustes:

#### Méthodes Principales
- `validateString()`: Valide les chaînes de caractères avec longueur et pattern
- `validateSiret()`: Valide spécifiquement les numéros SIRET
- `sanitize()`: Échappe les caractères HTML pour prévenir XSS
- `getErrors()`: Récupère tous les messages d'erreur
- `hasErrors()`: Vérifie s'il y a des erreurs

### Règles de Validation Serveur

Identiques aux règles client pour cohérence:
- **Nom**: 2-100 caractères, format alphanumérique avec accents
- **SIRET**: 14 chiffres exactement
- **Type**: 2-50 caractères
- **Adresse**: 5-200 caractères

## 4. Protection CSRF

### Classe Csrf (`src/Helper/Csrf.php`)

#### Fonctionnalités
- **Génération de tokens**: Tokens aléatoires de 64 caractères (32 bytes en hexadécimal)
- **Stockage sécurisé**: Dans la session PHP
- **Vérification**: Utilise `hash_equals()` pour prévenir les attaques de timing
- **Gestion du cycle de vie**: Génération, vérification, suppression

#### Implémentation
1. Token généré lors de l'affichage du formulaire
2. Token inclus comme champ caché dans le formulaire
3. Vérification du token lors de la soumission POST
4. Rejet de la requête si le token est invalide

### Code d'Exemple
```php
// Génération
$token = Csrf::generateToken();

// Dans le formulaire HTML
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

// Vérification
if (!Csrf::verifyToken($token)) {
    // Rejet de la requête
}
```

## 5. Protection XSS (Cross-Site Scripting)

### Mesures Implémentées

#### Sanitization des Entrées
Toutes les données POST sont nettoyées avec `Validator::sanitize()`:
```php
$nom = Validator::sanitize($this->request->post('nom'));
```

Cette méthode utilise `htmlspecialchars()` avec les drapeaux:
- `ENT_QUOTES`: Encode les guillemets simples et doubles
- `ENT_HTML5`: Compatible HTML5
- Encodage UTF-8

#### Échappement en Sortie
Toutes les données affichées dans les vues sont échappées:
```php
<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
```

## 6. Protection contre l'Injection SQL

### Requêtes Préparées
Utilisation exclusive de requêtes préparées PDO dans `CustomerRepository::createClient()`:

```php
$sql = "INSERT INTO ENTREPRISE (nom, numero_SIRET, type, information, adresse) 
        VALUES (:nom, :numero_siret, :type, :information, :adresse)";
$stmt = $this->pdo->prepare($sql);
$stmt->execute([...]);
```

### Correction du Bug
Le bug précédent dans la méthode `createClient()` a été corrigé:
- **Avant**: Les placeholders ne correspondaient pas aux paramètres
- **Après**: Correspondance exacte entre SQL et paramètres

## 7. Gestion des Erreurs

### Affichage des Erreurs
- Messages d'erreur clairs et spécifiques
- Erreurs groupées par champ
- Styles CSS distincts pour les erreurs (fond rouge léger, bordure rouge)

### Préservation des Données
En cas d'erreur de validation, les données saisies sont préservées via `old_input`:
```php
'old_input' => [
    'nom' => $nom,
    'numero_SIRET' => $numero_siret,
    // ...
]
```

## 8. Flux de Traitement Sécurisé

### Processus Complet

1. **Affichage du Formulaire**
   - Génération du token CSRF
   - Insertion dans le formulaire

2. **Validation Client**
   - Validation en temps réel avant soumission
   - Empêche les soumissions invalides

3. **Soumission POST**
   - Vérification du token CSRF
   - Sanitization de toutes les entrées
   - Validation côté serveur

4. **Traitement**
   - Si valide: Insertion en base avec requête préparée
   - Si invalide: Réaffichage avec erreurs et données préservées

5. **Redirection**
   - En cas de succès: Redirection POST-Redirect-GET
   - Empêche la resoumission accidentelle du formulaire

## 9. Améliorations de l'Interface Utilisateur

### CSS Ajouté (`public/assets/modal.css`)
```css
.field-error {
    border-color: #dc3545 !important;
    background-color: #fff5f5;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
}

.alert-success, .alert-error {
    /* Styles pour les messages de feedback */
}
```

## 10. Tests Recommandés

### Tests Manuels à Effectuer

1. **Test de Validation Client**
   - Soumettre avec champs vides
   - Soumettre avec SIRET invalide
   - Soumettre avec caractères spéciaux dans le nom

2. **Test de Validation Serveur**
   - Désactiver JavaScript et soumettre des données invalides
   - Vérifier que les erreurs sont détectées

3. **Test CSRF**
   - Soumettre le formulaire sans token
   - Soumettre avec un token invalide
   - Vérifier le rejet de la requête

4. **Test XSS**
   - Insérer `<script>alert('XSS')</script>` dans les champs
   - Vérifier que le contenu est échappé à l'affichage

5. **Test d'Injection SQL**
   - Insérer `'; DROP TABLE ENTREPRISE; --` dans les champs
   - Vérifier que la requête préparée empêche l'injection

## 11. Bonnes Pratiques Respectées

- ✅ **Defense in Depth**: Validation à plusieurs niveaux
- ✅ **Separation of Concerns**: Classes dédiées (Validator, Csrf)
- ✅ **DRY Principle**: Réutilisation du code de validation
- ✅ **Secure by Default**: Protection activée par défaut
- ✅ **User Experience**: Messages clairs, données préservées
- ✅ **Standards Web**: HTML5, UTF-8, ENT_QUOTES

## 12. Fichiers Modifiés et Créés

### Fichiers Créés
- `src/Helper/Csrf.php` - Gestion des tokens CSRF
- `src/Helper/Validator.php` - Validation et sanitization

### Fichiers Modifiés
- `public/js/modal.js` - Validation client JavaScript
- `public/assets/modal.css` - Styles pour erreurs et alertes
- `src/Controller/AppController.php` - Méthode createCustomer()
- `src/Repository/CustomerRepository.php` - Correction du bug SQL
- `views/pages/customer/listCustomer.php` - Token CSRF et affichage des erreurs
- `config/routes.php` - Route POST pour création
- `public/index.php` - Injection de la dépendance Request

## Conclusion

Le formulaire de création de client est maintenant sécurisé contre:
- ✅ Cross-Site Scripting (XSS)
- ✅ Cross-Site Request Forgery (CSRF)
- ✅ Injection SQL
- ✅ Données invalides
- ✅ Erreurs de saisie utilisateur

Les validations côté client et serveur garantissent l'intégrité des données tout en offrant une excellente expérience utilisateur.
