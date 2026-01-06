# TP — Backend API PHP & Front Angular (consommation JSON)

![Youpi](https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExaTI1Zm1kZW1yaDVkbjN6NGV3eXQ2NTZ3a3pvMmJibzVod3dvcHVrZSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/i456dLugvvZPunX8ZH/giphy.gif)

## Contexte

Vous disposez d’un **backend PHP** (mini-framework maison) avec :
- un système de routing
- des controllers
- un repository connecté à une base de données MySQL
- une gestion du JSON et du CORS déjà en place

---

## Objectifs pédagogiques

À la fin de ce TP, vous devez être capable de :

- concevoir une **API REST simple**
- séparer clairement **backend HTML** et **backend API**
- créer un **controller API dédié**
- enrichir un **Repository** avec de nouvelles méthodes
- consommer une API PHP depuis **Angular**
- structurer un projet Angular (models, services, pages)

---

## Partie A — Backend API PHP

### A1) Créer un controller API

Créer un nouveau controller dédié à l’API (ex. `GameApiController`).

Règles :
- ce controller ne doit **jamais** renvoyer de HTML
- toutes les réponses sont au **format JSON**
- le controller utilise le `GamesRepository`

---

### A2) Ajouter 3 nouvelles méthodes dans le Repository

Dans le repository des jeux, ajouter **trois nouvelles méthodes** :

1. Une méthode permettant de récupérer les **jeux les mieux notés**
2. Une méthode permettant de récupérer les **jeux les plus récents**
3. Une méthode permettant de compter le nombre de **jeux pour chaque note**

Objectifs :
- écrire des requêtes SQL adaptées
- utiliser le tri (`ORDER BY`)
- utiliser l’agrégation (`GROUP BY`)

---

### A3) Exposer les routes API

Déclarer les routes API suivantes :

- `/api/games/top`
- `/api/games/recent`
- `/api/stats/ratings`

Chaque route :
- appelle le controller API
- renvoie une réponse JSON valide
- utilise un code HTTP approprié

---

### A4) Tester l’API

Avant toute intégration Angular :
- tester chaque endpoint via le navigateur ou `curl`
- vérifier que le JSON retourné est valide
- vérifier qu’aucune vue HTML n’est utilisée

## INFO CREATION XAVIER
--> vérification OK avec

http://localhost:8080/api/games/top

http://localhost:8080/api/games/recent

http://localhost:8080/api/stats/ratings

Les tests ont été fait en ligne de commande et avec Postman.

---

## Partie B — Front Angular

Le front Angular doit **consommer exclusivement l’API PHP**.

Aucune donnée ne doit être simulée ou mockée.

---

### B1) Préparer Angular

Configurer Angular pour :
- utiliser `HttpClient`
- gérer les routes
- organiser le projet par responsabilités

---

### B2) Créer les modèles TypeScript

Créer les interfaces correspondant :
- aux jeux
- aux statistiques de notes

Objectif :
- typer correctement les données reçues depuis l’API

---

### B3) Créer un service API

Créer un service Angular responsable :
- des appels HTTP vers le backend
- de la centralisation des URLs API
- de la récupération des données JSON

---

### B4) Créer une page Dashboard

Créer une page unique affichant trois blocs :

1. **Top Rated**
    - affiche les jeux les mieux notés

2. **Recent Releases**
    - affiche les jeux les plus récents

3. **Ratings Stats**
    - affiche la répartition des notes

La page doit :
- appeler l’API au chargement
- afficher un état de chargement
- afficher un message d’erreur si l’API échoue

---

## Résultat attendu

### Backend
- API fonctionnelle
- routes claires et documentées
- controller API séparé du HTML
- repository enrichi avec 3 nouvelles méthodes

### Front Angular
- données chargées depuis l’API PHP
- affichage structuré et lisible
- aucune erreur CORS ou réseau
- code organisé (models, services, pages)

---

## Règles importantes

- le SQL est uniquement dans le Repository
- le controller API ne fait pas de rendu HTML
- Angular ne connaît pas la base de données
- aucune logique métier côté template Angular

---

## Bonus (facultatif)

- améliorer l’UX (loader, messages)
- afficher les statistiques sous forme graphique
- gérer finement les erreurs HTTP

---

🎯 **Fin du TP**
