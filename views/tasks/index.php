<h1>Mes tâches</h1>

<a href="/tasks/create">Nouvelle tâche</a>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong><br>
        <?= nl2br(htmlspecialchars($task['description'])) ?><br>

        <a href="/tasks/edit/<?= $task['id_tache'] ?>">Modifier</a>

        <form method="POST" action="/tasks/delete/<?= $task['id_tache'] ?>" style="display:inline">
            <button type="submit" onclick="return confirm('Supprimer cette tâche ?')">
                Supprimer
            </button>
        </form>
    </li>
<?php endforeach; ?>
</ul>
