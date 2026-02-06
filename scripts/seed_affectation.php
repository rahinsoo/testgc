<?php

/// remplissage automatique de la table affecter ///

require __DIR__ . '/../autoload.php';

use Model\Affectation;
use Repository\AffectationRepository;
use Core\Database;

$config = require __DIR__ . '/../config/db.php';
$pdo = Database::makePdo($config['db']);

$affectationRepository = new AffectationRepository($pdo);

$affectations = [
    [10, 2, 400]
];

$pdo->beginTransaction();

foreach ($affectations as [$id_user, $id_activite, $tjm]) {
    $affectation = new Affectation($id_user, $id_activite, $tjm);
    $affectationRepository->affecter($affectation);
}

$pdo->commit();
echo "Seed affectations OK\n";