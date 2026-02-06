<?php $activites = $activites ?? []; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des activités</title>
</head>

<body>

<h1>Activités</h1>

<a href="/activites/create">➕ Ajouter une activité</a>

<table border="1" cellpadding="5">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Date de création</th>
        <th>Date de fin</th>
        <th>Statut</th>
        <th>Client</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($activites as $activite): ?>
        <tr>
            <td><?= htmlspecialchars($activite->getId()) ?></td>
            <td><?= htmlspecialchars($activite->getNom()) ?></td>
            <td><?= htmlspecialchars($activite->getDescription()) ?></td>
            <td><?= htmlspecialchars($activite->getDateCreation()->format('Y-m-d')) ?></td>
            <td><?= htmlspecialchars($activite->getDateFin()->format('Y-m-d')) ?></td>
            <td><?= htmlspecialchars($activite->getStatut()) ?></td>
            <td><?= htmlspecialchars($activite->getNomClient()) ?></td>
            <td>
                <a href="/activites/edit/<?= $activite->getId() ?>">✏️ Modifier</a>

                <form action="/activites/delete/<?= $activite->getId() ?>" method="post" style="display:inline;">
                    <button type="submit" onclick="return confirm('Supprimer cette activité ?')">
                        🗑️ Supprimer
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="/dashboard">⬅️ Retour au tableau de bord</a>
<a href="/logout">Déconnexion</a>

</body>
</html>

