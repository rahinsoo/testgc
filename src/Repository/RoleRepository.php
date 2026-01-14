<?php

namespace Repository;

use PDO;

readonly class RoleRepository
{
    public function __construct(private PDO $pdo) {}

    public function readAll(): array
    {
        $sql = $this->pdo->query("SELECT * FROM user_role");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}