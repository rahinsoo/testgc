<?php


namespace Repository;

use PDO;

readonly final class DataPunchRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllClients() : array {
        $sql = $this->pdo->query("SELECT * FROM games");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}