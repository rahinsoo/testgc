<?php
$clients = $featuredClient ?? [];
//$total = $total ?? 0;
$user = $_SESSION['user'] ?? null;
?>

<section class="welcome-section">
    <h1>Bienvenue <?= htmlspecialchars($user['prenom'] ?? '') ?> ! 👋</h1>
    <p>Vous êtes connecté sur votre espace DataTime</p>
</section>



    <section class="clients-list">
        <h2>Vos clients</h2>
        <?php if (empty($clients)): ?>
            <p>Aucun client pour le moment.</p>
        <?php else: ?>
            <?php foreach ($clients as $client): ?>
                    <article class="card">
                        <h3 class="card__title"><?= htmlspecialchars($client['nom']) ?></h3>
                        <div class="meta">
                            <span class="badge"><?= htmlspecialchars((string)$client['numero_SIREN']) ?></span>
                            <span class="badge"><?= htmlspecialchars($client['type']) ?></span>
                            <span class="badge"><?= htmlspecialchars($client['information']) ?></span>
                            <span class="badge"><?= htmlspecialchars($client['adresse']) ?></span>
                        </div>
                    </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
