/*<?php
/** @var User $user */
/** @var array $roles */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
</head>
<body>

<h1>Modifier l’utilisateur</h1>

<form action="/users/update/<?= $user->getId() ?>" method="post">
    <label>
        Nom :
        <input type="text" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" required>
    </label><br><br>

    <label>
        Prénom :
        <input type="text" name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" required>
    </label><br><br>

    <label>
        Identifiant :
        <input type="text" name="identifiant" value="<?= htmlspecialchars($user->getIdentifiant()) ?>" required>
    </label><br><br>

    <label>
        Rôle :
        <select name="id_user_role" required>
            <option value="">-- Choisir un rôle --</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id_user_role'] ?>">
                    <?= htmlspecialchars($role['role']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <button type="submit">Mettre à jour</button>
</form>

<a href="/users">⬅️ Retour à la liste</a>

</body>
</html>

