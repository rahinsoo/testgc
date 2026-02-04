<?php
// Détecter si l'utilisateur est connecté
$isLogged = isset($_SESSION['user']);
$user = $isLogged ? $_SESSION['user'] : null;
$isAdmin = $isLogged && isset($user['id_user_role']) && $user['id_user_role'] === 1;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Time</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <link rel="stylesheet" href="/assets/modal.css">
<!--    --><?php //if ($isLogged): ?>
<!--        <script src="/js/modal.js" defer></script>-->
<!--    --><?php //endif; ?>
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <a href="<?= $isLogged ? '/home' : '/login' ?>">
            <img class="img_header" src="/img/DATAPUNCH.png" alt="DataTime">
        </a>
        <?php if ($isLogged): ?>
            <!-- NAVIGATION POUR UTILISATEUR CONNECTÉ -->
            <div class="nav__user">
                    <span class="nav__username">
                        <?= htmlspecialchars($user['prenom'] ??  '') ?>
                        <?= htmlspecialchars($user['nom'] ??  '') ?>
                    </span>
            </div>
            <nav class="nav">
                <a class="nav__link" href="/home">📊 Tableau de Bord</a>
                <a class="nav__link" href="/customer/listCustomer">🏢 Clients</a>
                <a class="nav__link" href="/pagetest">📅 Activités</a>
                <a class="nav__link" href="/tasks">✅ Tâches</a>
                <a class="nav__link" href="/pagetest">Activités par Salarié</a>
                <a class="nav__link" href="/profile">Profil</a>
                <a class="nav__link" href="/settings">⚙️ Paramètres</a>
                <?php if ($isAdmin): ?>
                    <a class="nav__link" href="/users">👥 Utilisateurs</a>
                <?php endif; ?>
                <a class="nav__link " href="/logout">🚪 Déconnexion</a>

            </nav>
            <?php else: ?>
            <!-- NAVIGATION POUR UTILISATEUR NON CONNECTÉ -->
            <nav class="nav">
                <span class="nav__username">
                    On The World
                </span>
<!--                <a class="nav__link" href="/login">Se connecter</a>-->
            </nav>
        <?php endif; ?>
    </div>
</header>
<main class="">

