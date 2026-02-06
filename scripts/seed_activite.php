<?php

/// remplissage automatique de la table activite ///


/// chargement automatique des classes, pas besoin de faire des require partout ///
require __DIR__ . '/../autoload.php';

/// import de ce dont on a besoin ///
use Repository\ActiviteRepository; // gère les requêtes SQL liées aux activités
use Core\Database; // gère la connexion PDO

$config = require __DIR__ . '/../config/db.php';  // retourne un tableau de configuration
$pdo = Database::makePdo($config['db']); // création d'un objet PDO, connexion à la BDD

$activiteRepository = new ActiviteRepository($pdo); // injection de PDO dans le repository

$activites = [
    ['DataPunch', 'création d\'une appli pointeuse', new DateTimeImmutable('2025/11/26'), new DateTimeImmutable('2026/04/23'), 'en cours', 1]
];

$pdo->beginTransaction();

/// destructuring de tableau ///
foreach ($activites as [$nom, $description, $date_creation, $date_fin, $statut, $id_client]) {
    $activiteRepository->createActivite($nom, $description, $date_creation, $date_fin, $statut, $id_client);
}

/// validation de la transaction ///
$pdo->commit();

/// message de confirmation ///
echo "Seed activites OK\n";