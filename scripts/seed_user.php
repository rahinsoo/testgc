<?php

/// remplissage automatique de la table utilisateur ///

require __DIR__ . '/../autoload.php';

use Repository\UserRepository;
use Core\Database;

$config = require __DIR__ . '/../config/db.php';
$pdo = Database::makePdo($config['db']);

$userRepository = new UserRepository($pdo);

$users = [
    ['Martin', 'Alice', 'amartin@datatime.com', 'password', 2]
];

$pdo->beginTransaction();

foreach ($users as [$nom, $prenom, $identifiant, $password, $id_user_role]) {
    $userRepository->createUser($nom, $prenom, $identifiant, $password, $id_user_role);
}

$pdo->commit();
echo "Seed users OK\n";