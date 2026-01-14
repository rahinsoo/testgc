<?php $users = $users ?? []; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
</head>

<body>

<h1>Utilisateurs</h1>

<a href="/users/create">➕ Ajouter un utilisateur</a>

<table border="1" cellpadding="5">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Identifiant</th>
        <th>Rôle</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user->getId()) ?></td>
            <td><?= htmlspecialchars($user->getNom()) ?></td>
            <td><?= htmlspecialchars($user->getPrenom()) ?></td>
            <td><?= htmlspecialchars($user->getIdentifiant()) ?></td>
            <td><?= htmlspecialchars($user->getNomRole()) ?></td>
            <td>
                <a href="/users/edit/<?= $user->getId() ?>">✏️ Modifier</a>

                <form action="/users/delete/<?= $user->getId() ?>" method="post" style="display:inline;">
                    <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')">
                        🗑️ Supprimer
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
