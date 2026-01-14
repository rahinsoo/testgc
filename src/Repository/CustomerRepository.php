<?php


namespace Repository;

//use Model\Customer;
use PDO;

readonly final class CustomerRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllClients() : array {
        $sql = $this->pdo->query("SELECT * FROM ENTREPRISE");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() : int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM ENTREPRISE");
        return $sql->fetch(PDO::FETCH_COLUMN);
    }
/// CREATE ///
    public function createClient(
        string $nom,
        string $numero_siret,
        string $type,
        string $information,
        bool $is_facturable,
        string $adresse,
        int $id_projet

    ): bool
    {
        $sql = "INSERT INTO ENTREPRISE (nom, numero_SIRET, type, information, is_facturable,adresse, id_projet) 
                VALUES (:nom, :prenom, :identifiant, :password, :role)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $nom,
            'numero_SIRET' => $numero_siret,
            'type' => $type,
            'information' => $information,
            'is_facturable' => $is_facturable,
            'adresse' => $adresse,
            'id_projet' => $id_projet
        ]);
    }

    public function addClient() : array
    {
        $sql = $this->pdo->query("INSERT INTO ENTREPRISE (nom, ) VALUES ('Test Corp', '123 Test St', '10001', 'Testville', 'Technology', 'TVA123456')");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /// UPDATE ///
    public function updateUser(
        User $user
    ): bool {
        $sql = "
            UPDATE utilisateur
            SET nom = :nom,
                prenom = :prenom,
                identifiant = :identifiant,
                id_user_role = :role
            WHERE id_user = :id_user
        ";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_user' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'identifiant' => $user->getIdentifiant(),
            'role' => $user->getRoleId()
        ]);
    }


}