<?php


namespace Repository;

//use Model\Home;
use PDO;

readonly final class HomeRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllClients() : array {
        $sql = $this->pdo->query("SELECT * FROM ENTREPRISE");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() : int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM ENTREPRISE");
        return $sql->fetch(PDO::FETCH_COLUMN);
    }

//    public function readAll(): array
//    {
//        $sql = $this->pdo->query
//        ("SELECT
//        u.id_user,
//        u.nom,
//        u.prenom,
//        u.identifiant,
//        u.id_user_role,
//        r.role
//        FROM utilisateur u
//        LEFT JOIN user_role r ON u.id_user_role = r.id_user_role");
//        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
//
//        return array_map(function($row) {
//            return new Client(
//                $row['id_user'],
//                $row['nom'],
//                $row['prenom'],
//                $row['identifiant'],
//                '',
//                $row['id_user_role'],
//                $row['role']
//            );
//        },
//            $rows);
//    }
}