<?php

namespace Repository;

use PDO;

readonly class RoleRepository
{
    public function __construct(private PDO $pdo) {}

    /// lire les rôles, utilisé dans la jointure avec utilisateur ///
    public function readAll(): array
    {
        $sql = $this->pdo->query("SELECT * FROM user_role");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}