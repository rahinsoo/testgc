<?php $roles = $roles ?? []; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un utilisateur</title>
</head>
<body>

<h1>Créer un utilisateur</h1>

<form action="/users/store" method="post">
    <label>
        Nom :
        <input type="text" name="nom" required>
    </label><br><br>

    <label>
        Prénom :
        <input type="text" name="prenom" required>
    </label><br><br>

    <label>
        Identifiant :
        <input type="text" name="identifiant" required>
    </label><br><br>

    <label>
        Mot de passe :
        <input type="password" name="password" required>
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

    <button type="submit">Créer</button>
</form>

<a href="/users">⬅️ Retour à la liste</a>

</body>
</html>

