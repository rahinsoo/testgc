<?php
/** @var array $user */
/** @var bool $isAdmin */
/** @var bool $isManager */
/** @var bool $isRecruteur */
/** @var bool $isCollaborateur */
?>

<h1>Dashboard</h1>

<p>
    Bienvenue <?= htmlspecialchars($user['prenom']) ?>
</p>

<ul>
    <?php if ($isAdmin): ?>
    <li>
        <a href="/users">Gestion des utilisateurs</a>
    </li>
    <?php endif; ?>

    <?php if ($isManager): ?>
    <li>
        <a href="/clients">Gestion des clients</a>
    </li>
    <li>
        <a href="/activites">Gestion des activités</a>
    </li>
    <?php endif; ?>

    <?php if($isRecruteur): ?>
    <li>
        <a href="/affectations">Gestion des affectations</a>
    </li>
    <?php endif; ?>

    <?php if($isCollaborateur): ?>
    <li>
        <a href="/tâches">Gestion des tâches</a>
    </li>
    <?php endif; ?>

    <li><a href="/logout">Déconnexion</a></li>
</ul>
