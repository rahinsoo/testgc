# Guide de Test - Formulaire de Création de Client

## Comment tester l'application

### Prérequis
- PHP 8.3 ou supérieur
- MySQL 8.0 ou supérieur
- Docker et Docker Compose (pour la base de données)

### Configuration de la Base de Données

1. Démarrer la base de données avec Docker:
```bash
docker-compose up -d
```

2. Configurer les identifiants de connexion dans `config/db.local.php`:
```php
<?php
return [
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'db' => 'data_punch',
        'user' => 'app',
        'pass' => 'app',
        'charset' => 'utf8mb4',
    ]
];
```

3. Vérifier que la table ENTREPRISE existe avec la structure suivante:
```sql
CREATE TABLE ENTREPRISE (
    id_entreprise INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    numero_SIRET VARCHAR(14) NOT NULL,
    type VARCHAR(50) NOT NULL,
    information TEXT,
    adresse VARCHAR(200) NOT NULL
);
```

### Démarrer le Serveur Web

Utiliser le serveur PHP intégré:
```bash
cd public
php -S localhost:8000
```

L'application sera accessible à: http://localhost:8000

### Tests Unitaires

Exécuter les tests de validation et sécurité:
```bash
php tests/ValidationTest.php
```

Tous les tests doivent passer avec succès (16/16).

## Scénarios de Test Manuel

### 1. Test de Validation Côté Client

**Test 1.1: Soumettre un formulaire vide**
- Ouvrir http://localhost:8000/customer/listCustomer
- Cliquer sur "Création entreprise"
- Cliquer directement sur "Créer"
- **Résultat attendu**: Messages d'erreur en rouge sous chaque champ requis

**Test 1.2: SIRET invalide**
- Entrer "ABC123" dans le champ SIRET
- Soumettre le formulaire
- **Résultat attendu**: Message "Le numéro SIRET doit contenir exactement 14 chiffres"

**Test 1.3: Nom avec caractères spéciaux**
- Entrer "Jean@Doe#123" dans le champ Nom
- Soumettre le formulaire
- **Résultat attendu**: Message "Le nom ne peut contenir que des lettres, espaces et tirets"

**Test 1.4: Données valides**
- Nom: "Entreprise Test"
- SIRET: "12345678901234"
- Type: "SARL"
- Information: "Une description optionnelle"
- Adresse: "123 Rue de Test, 75001 Paris"
- Soumettre le formulaire
- **Résultat attendu**: Formulaire soumis, client créé, message de succès affiché

### 2. Test de Validation Côté Serveur

**Test 2.1: Bypass de validation client**
- Ouvrir la console développeur (F12)
- Désactiver JavaScript
- Soumettre des données invalides
- **Résultat attendu**: Validation serveur rejette les données, messages d'erreur affichés

**Test 2.2: Vérifier la préservation des données**
- Soumettre un formulaire avec une erreur (ex: SIRET trop court)
- **Résultat attendu**: 
  - Modale se rouvre automatiquement
  - Données précédemment saisies sont toujours présentes
  - Messages d'erreur sont affichés

### 3. Test de Protection CSRF

**Test 3.1: Soumission sans token**
- Ouvrir les outils développeur
- Supprimer l'input hidden "csrf_token" du formulaire
- Soumettre le formulaire
- **Résultat attendu**: Erreur "Token de sécurité invalide"

**Test 3.2: Soumission avec token invalide**
- Modifier la valeur du token CSRF dans le formulaire
- Soumettre le formulaire
- **Résultat attendu**: Erreur "Token de sécurité invalide"

### 4. Test de Protection XSS

**Test 4.1: Injection de script dans le nom**
- Nom: `<script>alert('XSS')</script>`
- Compléter les autres champs valides
- Soumettre le formulaire
- Vérifier dans la liste des clients
- **Résultat attendu**: Le script est affiché comme texte, pas exécuté

**Test 4.2: Injection HTML dans l'adresse**
- Adresse: `<img src=x onerror=alert('XSS')>`
- Soumettre le formulaire
- Vérifier dans la liste
- **Résultat attendu**: Le code HTML est affiché comme texte

### 5. Test de Protection SQL Injection

**Test 5.1: Tentative d'injection SQL**
- Nom: `'; DROP TABLE ENTREPRISE; --`
- SIRET: `12345678901234`
- Type: `SARL`
- Adresse: `123 Test Street`
- Soumettre le formulaire
- **Résultat attendu**: 
  - Entrée créée normalement
  - Le texte est stocké tel quel
  - Aucune commande SQL n'est exécutée

### 6. Test de l'Expérience Utilisateur

**Test 6.1: Fermeture de la modale**
- Ouvrir la modale
- Appuyer sur "Escape"
- **Résultat attendu**: Modale se ferme

**Test 6.2: Clic en dehors de la modale**
- Ouvrir la modale
- Cliquer sur le fond sombre
- **Résultat attendu**: Modale se ferme

**Test 6.3: Message de succès**
- Créer un client valide
- **Résultat attendu**: 
  - Redirection vers la liste
  - Message vert "Client créé avec succès!"
  - Nouveau client visible dans la liste

## Vérification des Fichiers

### Structure des Fichiers JavaScript

Vérifier que les fichiers sont au bon endroit:
```
public/
  js/
    modal.js              ✓ Accessible publiquement
    validation-config.js  ✓ Accessible publiquement
```

### Accès aux Fichiers

Tester l'accès direct:
- http://localhost:8000/js/modal.js - Doit afficher le code JavaScript
- http://localhost:8000/js/validation-config.js - Doit afficher le code JavaScript

## Checklist de Vérification

- [ ] La modale s'ouvre correctement
- [ ] La validation client fonctionne
- [ ] La validation serveur fonctionne
- [ ] Le token CSRF est vérifié
- [ ] Les entrées sont sanitizées (XSS)
- [ ] Les requêtes SQL utilisent des requêtes préparées
- [ ] Les messages d'erreur sont clairs
- [ ] Les données sont préservées en cas d'erreur
- [ ] Le message de succès s'affiche
- [ ] Le client apparaît dans la liste après création
- [ ] Tous les tests unitaires passent

## Dépannage

### Le JavaScript ne se charge pas

Vérifier:
1. Le serveur web utilise bien le dossier `public/` comme document root
2. Les fichiers existent dans `public/js/`
3. La console du navigateur pour les erreurs

### Erreur de base de données

Vérifier:
1. Docker Compose est lancé
2. `config/db.local.php` existe et contient les bons identifiants
3. La table ENTREPRISE existe

### Les tests unitaires échouent

Vérifier:
1. PHP 8.3+ est installé
2. L'autoloader fonctionne
3. Toutes les classes Helper existent

## Documentation Complète

Pour plus de détails sur les mesures de sécurité implémentées, consulter:
- `SECURITY_DOCUMENTATION.md` - Documentation complète de sécurité
- Code source avec commentaires dans les classes Helper
