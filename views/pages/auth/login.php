<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<h1>Connexion</h1>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="/login">
    <label>
        Identifiant
        <input type="text" name="identifiant" required>
    </label>
    <br><br>

    <label>
        Mot de passe
        <input type="password" name="password" required>
    </label>
    <br><br>

    <button class="nav__link" type="submit">Se connecter</button>
</form>

<p>
    <a href="/forgot-password">Mot de passe oublié ?</a>
</p>

</body>
</html>


