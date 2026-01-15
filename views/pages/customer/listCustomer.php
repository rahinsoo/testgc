<?php
$clients = $listClient ?? [];
$error = $error ?? null;
$errors = $errors ?? [];
$old_input = $old_input ?? [];
$success = isset($_GET['success']) && $_GET['success'] == '1';

// Generate CSRF token
require_once __DIR__ . '/../../../src/Helper/Csrf.php';
$csrfToken = \Helper\Csrf::generateToken();
?>
<h1>Bienvenue dans ton espace de client</h1>

<?php if ($success): ?>
    <div class="alert alert-success">Client créé avec succès!</div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<?php foreach ($clients as $client): ?>
    <article class="card">
        <h2 class="card__title"><?= $client['nom'] ?></h2>

        <div class="meta">
            <span class="badge"><?= (int) $client['numero_SIRET'] ?></span>
            <span class="badge"><?= $client['type'] ?></span>
            <span class="badge"><?= $client['information'] ?></span>
            <span class="badge"><?= $client['adresse'] ?></span>
            <span class="">Suppr</span>
            <span class="">Edit</span>
            <span class="">Creation activité</span>
        </div>
    </article>
<?php endforeach; ?>

<!--<article class="card">-->
<h2 class="card__title card">
    <button type="button" id="openModalBtn" class="btn-create">Création entreprise</button>
</h2>
<!--</article>-->

<div class="meta">
    <span class="card">Total clients: <?= count($clients) ?></span>
</div>

<!-- Modale de création -->
<div id="createCustomerModal" class="modal">
    <div class="modal__content card">
        <span class="modal__close">&times;</span>
        <h2>Créer un nouveau client</h2>
        <form id="createCustomerForm" action="/customer/listCustomer" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($old_input['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                <?php if (isset($errors['nom'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="numero_SIRET">Numéro SIRET (14 chiffres) *</label>
                <input type="text" id="numero_SIRET" name="numero_SIRET" value="<?= htmlspecialchars($old_input['numero_SIRET'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required maxlength="14" pattern="\d{14}">
                <?php if (isset($errors['numero_SIRET'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['numero_SIRET'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="type">Type *</label>
                <input type="text" id="type" name="type" value="<?= htmlspecialchars($old_input['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required maxlength="50">
                <?php if (isset($errors['type'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['type'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="information">Information</label>
                <textarea id="information" name="information" maxlength="500"><?= htmlspecialchars($old_input['information'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <textarea id="adresse" name="adresse" required maxlength="200"><?= htmlspecialchars($old_input['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <?php if (isset($errors['adresse'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['adresse'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-submit">Créer</button>
        </form>
    </div>
</div>