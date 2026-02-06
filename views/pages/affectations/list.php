<h2>Liste des Affectations</h2>

<?php /*if ($session->hasFlash('error')): ?>
    <p style="color:red;"><?= $session->getFlash('error') ?></p>
<?php endif; ?>

<?php  if ($session->hasFlash('success')): ?>
    <p style="color:green;"><?= $session->getFlash('success') ?></p>
<?php endif; */ ?>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>Utilisateur</th>
        <th>Activité</th>
        <th>TJM (€)</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($affectations)): ?>
        <tr>
            <td colspan="4">Aucune affectation trouvée.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($affectations as $aff): ?>
            <tr>
                <td><?= htmlspecialchars($aff['user_nom']) ?> <?= htmlspecialchars($aff['user_prenom']) ?></td>
                <td><?= htmlspecialchars($aff['activite_nom']) ?></td>
                <td><?= number_format($aff['tjm'], 2, ',', ' ') ?></td>
                <td>
                    <!-- Modifier TJM -->
                    <form action="/affectations/updateTjm/<?= $aff['id_user'] ?>/<?= $aff['id_activite'] ?>" method="post" style="display:inline;">
                        <input type="number" name="tjm" step="0.01" value="<?= $aff['tjm'] ?>" required>
                        <button type="submit">Modifier</button>
                    </form>

                    <!-- Supprimer affectation -->
                    <form action="/affectations/delete/<?= $aff['id_user'] ?>/<?= $aff['id_activite'] ?>" method="post" style="display:inline;">
                        <button type="submit" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<p>
    <a href="/affectations/create">Nouvelle Affectation</a>
</p>

<a href="/dashboard">⬅️ Retour au tableau de bord</a>
<a href="/logout">Déconnexion</a>
