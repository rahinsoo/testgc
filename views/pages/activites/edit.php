<?php
/** @var Activite $activite */
/** @var array $nomsClients */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une activité</title>
</head>
<body>

<h1>Modifier une activité</h1>

<form action="/activites/update/<?= $activite->getId() ?>" method="post">
    <label>
        Nom :
        <input type="text" name="nom" value="<?= htmlspecialchars($activite->getNom()) ?>" required>
    </label><br><br>

    <label>
        Description :
        <input type="text" name="description" value="<?= htmlspecialchars($activite->getDescription()) ?>" required>
    </label><br><br>

    <label>
        Date de création :
        <input type="date" name="date_creation" value="<?= htmlspecialchars($activite->getDateCreation()->format('Y-m-d')) ?>" required>
    </label><br><br>

    <label>
        Date de fin :
        <input type="date" name="date_fin" value="<?= htmlspecialchars($activite->getDateFin()->format('Y-m-d')) ?>" required>
    </label><br><br>

    <label>
        Statut :
        <input type="text" name="statut" value="<?= htmlspecialchars($activite->getStatut()) ?>" required>
    </label><br><br>

    <label>
        Nom du client :
        <select name="id_client" required>
            <option value="">-- Choisir un client --</option>
            <?php foreach ($nomsClients as $client) : ?>
                <option value="<?= $client['id_client'] ?>"
                        <?= $client['id_client'] == $activite->getIdClient() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <button type="submit">Mettre à jour</button>
</form>

<a href="/activites">⬅️ Retour à la liste</a>

</body>
</html>

