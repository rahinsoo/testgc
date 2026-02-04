<?php
$user = $_SESSION['user'] ?? null;
?>

<section class="settings-section">
    <h1>⚙️ Paramètres</h1>

    <div class="card">
        <h2>Préférences</h2>
        <form method="POST" action="/settings/update">
            <label>
                <input type="checkbox" name="notifications" checked>
                Recevoir des notifications (non fonctionnel)
            </label><br>

            <label>
                <input type="checkbox" name="dark_mode">
                Mode sombre (non fonctionnel)
            </label><br><br>

            <button type="submit" class="btn">💾 Enregistrer (non fonctionnel)</button>
        </form>
    </div>
</section>