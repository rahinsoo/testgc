<?php
$user = $_SESSION['user'] ?? null;
?>

<section class="settings-section">
    <h1>⚙️ Paramètres</h1>

    <div class="card">
        <h2>Informations personnelles</h2>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom'] ?? 'N/A') ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom'] ?? 'N/A') ?></p>
        <p><strong>Identifiant :</strong> <?= htmlspecialchars($user['identifiant'] ?? 'N/A') ?></p>
    </div>

    <div class="card">
        <h2>Sécurité</h2>
        <a href="/users/<?= $user['id_user'] ?? 0 ?>/change-password" class="btn">
            🔒 Changer mon mot de passe (non fonctionnel)
        </a>
    </div>
</section>