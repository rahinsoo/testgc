<?php
$clients = $listClient ?? [];
?>
<h1>Bienvenue dans ton espace de client</h1>

<?php foreach ($clients as $client): ?>
    <article class="card">
        <h2 class="card__title"><?= $client['nom'] ?></h2>

        <div class="meta">
            <span class="badge"><?= (int)$client['numero_SIRET'] ?></span>
            <span class="badge"><?= $client['information'] ?></span>
            <span class="">Suppr</span>
            <span class="">Edit</span>
            <span class="">Creation activité</span>
        </div>
    </article>
<?php endforeach; ?>

<article class="card">
    <h2 class="card__title">
        <button type="button" id="openModalBtn" class="btn-create">CREATE</button>
    </h2>
</article>

<div class="meta">
    <span class="card">Total clients: <?= count($clients) ?></span>
</div>

<!-- Modale de création -->
<div id="createCustomerModal" class="modal">
    <div class="modal__content">
        <span class="modal__close">&times;</span>
        <h2>Créer un nouveau client</h2>
        <form id="createCustomerForm" action="/customer/createCustomer.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="numero_SIRET">Numéro SIRET</label>
                <input type="text" id="numero_SIRET" name="numero_SIRET" required>
            </div>
            <div class="form-group">
                <label for="information">Information</label>
                <textarea id="information" name="information"></textarea>
            </div>
            <button type="submit" class="btn-submit">Créer</button>
        </form>
    </div>
</div>

<script src="../js/modal.js"></script>