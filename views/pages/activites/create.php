<?php $nomsClients = $nomsClients ?? []; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une activité</title>
</head>
<body>

<h1>Créer une activité</h1>

<form action="/activites/store" method="post">
    <label>
        Nom :
        <input type="text" name="nom" required>
    </label><br><br>

    <label>
        Description :
        <input type="text" name="description" required>
    </label><br><br>

    <label>
        Date de création :
        <input type="date" name="date_creation" required>
    </label><br><br>

    <label>
        Date de fin :
        <input type="date" name="date_fin" required>
    </label><br><br>

    <label>
        Statut :
        <input type="text" name="statut" required>
    </label><br><br>

    <label>
        Nom du client :
        <select name="id_client" required>
            <option value="">-- Choisir un client --</option>
            <?php foreach ($nomsClients as $nomsClient) : ?>
                <option value="<?= $nomsClient['id_client'] ?>">
                    <?= htmlspecialchars($nomsClient['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <button type="submit">Créer</button>
</form>

<a href="/activites">⬅️ Retour à la liste</a>

</body>
</html>


