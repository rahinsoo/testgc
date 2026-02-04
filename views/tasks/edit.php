<h1>Modifier la tâche</h1>

<form method="POST">
    <label>Titre</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>

    <label>Description</label><br>
    <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br><br>

    <button type="submit">Modifier</button>
</form>

<a href="/tasks">⬅ Retour</a>
