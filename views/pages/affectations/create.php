<?php $users = $users ?? []; ?>
<?php $activites = $activites ?? []; ?>

<h2>Nouvelle Affectation</h2>

<form action="/affectations/store" method="post">
    <label for="id_user">Collaborateur :</label>
    <select name="id_user" id="id_user" required>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nom']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="id_activite">Activité :</label>
    <select name="id_activite" id="id_activite" required>
        <?php foreach ($activites as $activite): ?>
            <option value="<?= $activite->getId() ?>"><?= htmlspecialchars($activite->getNom()) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="tjm">TJM (€) :</label>
    <input type="number" name="tjm" id="tjm" step="0.01" required>

    <button type="submit">Affecter</button>
</form>

<a href="/affectations">⬅️ Retour à la liste</a>


<?php /* if ($session->hasFlash('error')): ?>
    <p style="color:red;"><?= $session->getFlash('error') ?></p>
<?php endif; ?>

<?php if ($session->hasFlash('success')): ?>
    <p style="color:green;"><?= $session->getFlash('success') ?></p>
<?php endif; */ ?>
