<h1>Mot de passe oublié</h1>

<?php //if ($message = $this->session->pullFlash('error')): ?>
<!--    <p style="color:red">--><?php //= htmlspecialchars($message) ?><!--</p>-->
<?php //endif; ?>
<!---->
<?php //if ($message = $this->session->pullFlash('success')): ?>
<!--    <p style="color:green">--><?php //= htmlspecialchars($message) ?><!--</p>-->
<?php //endif; ?>

<form action="/forgot-password" method="post">
    <label for="identifiant">Identifiant :</label>
    <input type="text" name="identifiant" id="identifiant" required>

    <label for="new_password">Nouveau mot de passe :</label>
    <input type="password" name="new_password" id="new_password" required>

    <button type="submit">Réinitialiser le mot de passe</button>
</form>

<p><a href="/login">Retour à la connexion</a></p>

