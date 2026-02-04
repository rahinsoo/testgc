# Configuration de l'API INSEE SIRENE

## 🔧 Configuration requise

Pour utiliser la fonctionnalité d'auto-complétion SIRET, vous devez obtenir un token d'API INSEE SIRENE.

### Étape 1 : Obtenir un token API

1. Rendez-vous sur : https://api.insee.fr/
2. Créez un compte (inscription gratuite)
3. Accédez à votre espace développeur
4. Créez une nouvelle application
5. Souscrivez à l'API "Sirene V3"
6. Copiez votre **Consumer Key** et **Consumer Secret**

### Étape 2 : Générer un token Bearer

Utilisez vos identifiants pour obtenir un token d'accès :

```bash
curl -X POST "https://api.insee.fr/token" \
  -H "Authorization: Basic BASE64(consumer_key:consumer_secret)" \
  -d "grant_type=client_credentials"
```

**Note :** Encodez `consumer_key:consumer_secret` en Base64 avant d'utiliser cette commande.

La réponse contiendra un `access_token` que vous devrez utiliser.

### Étape 3 : Configurer le token dans le projet

Ouvrez le fichier `public/js/modal.js` et remplacez :

```javascript
const API_INSEE_TOKEN = 'VOTRE_TOKEN_ICI';
```

Par :

```javascript
const API_INSEE_TOKEN = 'votre_token_obtenu_etape_2';
```

## ✅ Vérification

Une fois configuré, testez la fonctionnalité :

1. Ouvrez la modal de création de client
2. Saisissez un SIRET valide : `81824197800035`
3. Attendez 800ms
4. Les champs **Nom** et **Adresse** doivent se remplir automatiquement

## ⚠️ Notes importantes

### Sécurité

**Cette solution expose le token côté client** (dans le navigateur). C'est acceptable pour :
- Un environnement de développement
- Un prototype
- Une application en intranet

**Pour la production**, il est **fortement recommandé** de :
1. Créer un proxy PHP côté serveur
2. Stocker le token dans une variable d'environnement
3. Faire transiter les appels API par votre backend

### Exemple de proxy PHP (recommandé en production)

```php
<?php
// api/sirene-proxy.php

$siret = $_GET['siret'] ?? '';

if (!preg_match('/^\d{14}$/', $siret)) {
    http_response_code(400);
    echo json_encode(['error' => 'SIRET invalide']);
    exit;
}

$token = getenv('INSEE_API_TOKEN'); // Depuis variable d'environnement
$url = "https://api.insee.fr/api-sirene/3.11/siret/{$siret}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$token}",
    "Accept: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
header('Content-Type: application/json');
echo $response;
```

Puis modifiez `modal.js` pour appeler `/api/sirene-proxy.php?siret=XXX` au lieu de l'API INSEE directement.

## 📚 Ressources

- Documentation API SIRENE : https://api.insee.fr/catalogue/
- Liste des endpoints : https://api.insee.fr/catalogue/site/themes/wso2/subthemes/insee/pages/item-info.jag?name=Sirene&version=V3&provider=insee
- Support : https://api.insee.fr/
