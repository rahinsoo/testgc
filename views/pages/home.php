<?php
$clients = $featuredClient ?? [];
//$total = $total ?? 0;
?>

    <h1>Bienvenue dans ton espace (home)</h1>
    <!--<p class="sub">Home — featuring --><?php //= count($games) ?><!-- games.</p>-->
    <!---->
    <!--<section class="card">-->
    <!--    <div class="meta">-->
    <!--        <span class="badge">Total: --><?php //= (int)$total ?><!--</span>-->
    <!--        <span class="badge">Featured: --><?php //= count($games) ?><!--</span>-->
    <!--    </div>-->
    <!--</section>-->
    <!---->
    <!--<a href="/random" class="btn">🎲 Random game</a>-->
    <!---->
<?php foreach ($clients as $client): ?>
        <article class="card">
            <h2 class="card__title"><?= $client['nom'] ?></h2>

            <div class="meta">
                <span class="badge"><?= (int) $client['numero_SIRET'] ?></span>
                <span class="badge"><?= $client['type'] ?></span>
                <span class="badge"><?= $client['information'] ?></span>
                <span class="badge"><?= $client['adresse'] ?></span>
            </div>
<!--            <a href="/games/--><?php //= $client['id'] ?><!--">Naviguer vers le détail</a>-->
        </article>
<?php endforeach; ?>