<?php
$clients = $listClient ?? [];
?>
<h1>Bienvenue dans ton espace de client</h1>

<!-- ✅ Affichage des messages flash -->
<?php if (isset($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
        <div class="alert alert-<?= $type ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<div class="container">
    <?php foreach ($clients as $client): ?>
    <div class="container__card">
            <article class="card">
                <a href="/customer/infoCustomer) ?>">
                    <h2 class="card__title"><?= htmlspecialchars($client['nom']) ?></h2>
                </a>

                <div class="meta">
                    <span class="badge"><?= htmlspecialchars((string)$client['numero_SIREN']) ?></span>
                </div>
                <div class="meta">
                    <button class="btn-edit" onclick="openEditModal(<?= $client['id_entreprise'] ?>)">✏️ Edit</button>
                    <button class="btn-delete" onclick="deleteClient(<?= $client['id_entreprise'] ?>, '<?= htmlspecialchars($client['nom']) ?>')">🗑️ Suppr</button>
                    <span class="">Création activité</span>
                </div>

            </article>
    </div>

    <?php endforeach; ?>
    <div class="container__card">
        <h2 class="card__title card">
            <button type="button" id="openModalBtn" class="btn-create">➕ Création entreprise</button>
        </h2>
    </div>
</div>



<div class="container">
    <div class="meta">
        <span class="card">Total clients: <?= count($clients) ?></span>
    </div>
</div>

<!-- Modale unique pour Création ET Édition -->
<div id="customerModal" class="modal">
    <div class="modal__content card">
        <span class="modal__close">&times;</span>
        <h2 id="modalTitle">Créer un nouveau client</h2>

        <!-- Le formulaire change d'action selon le mode -->
        <form id="customerForm" method="POST">
            <input type="hidden" id="clientId" name="id_entreprise">

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="numero_SIREN">Numéro SIREN *</label>
                <input type="text" id="numero_SIREN" name="numero_SIREN" required
                       pattern="[0-9]{9}"
                       title="Le SIREN doit contenir 9 chiffres">
            </div>

            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    <option value="">-- Choisir un type --</option>
                    <option value="SARL">SARL</option>
                    <option value="SAS">SAS</option>
                    <option value="SA">SA</option>
                    <option value="Auto-entrepreneur">Auto-entrepreneur</option>
                    <option value="Association">Association</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="information">Information</label>
                <textarea id="information" name="information"
                          placeholder="Informations complémentaires..."></textarea>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <textarea id="adresse" name="adresse" required
                          placeholder="123 Rue Exemple, 75001 Paris"></textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="is_facturable" name="is_facturable" checked>
                    Client facturable
                </label>
            </div>

            <button type="submit" id="submitBtn" class="btn-submit">Créer</button>
        </form>
    </div>
</div>

<script src="/js/modal.js" defer></script>