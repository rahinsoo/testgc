<?php

namespace Repository;

use PDO;

readonly class CustomerRepository {
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
    ): bool
    {
        // ✅ CORRECTION : placeholders correspondent aux colonnes
        $sql = "INSERT INTO ENTREPRISE (nom, numero_SIRET, type, information, is_facturable, adresse) 
                VALUES (:nom, :numero_SIRET, :type, :information, :is_facturable, :adresse)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $nom,
            'numero_SIRET' => $numero_siret,
            'type' => $type,
            'information' => $information,
            'is_facturable' => $is_facturable ?  1 : 0, // Conversion boolean → int
            'adresse' => $adresse,
        ]);
    }

    /// UPDATE ///
    public function updateClient(
        int $id_entreprise,
        string $nom,
        string $numero_siret,
        string $type,
        string $information,
        bool $is_facturable,
        string $adresse
    ): bool {
        $sql = "
            UPDATE ENTREPRISE
            SET nom = :nom,
                numero_SIRET = :numero_SIRET,
                type = :type,
                information = :information,
                is_facturable = :is_facturable,
                adresse = :adresse
            WHERE id_entreprise = :id_entreprise
        ";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_entreprise' => $id_entreprise,
            'nom' => $nom,
            'numero_SIRET' => $numero_siret,
            'type' => $type,
            'information' => $information,
            'is_facturable' => $is_facturable ? 1 : 0,
            'adresse' => $adresse,
        ]);
    }

    /// DELETE ///
    public function deleteClient(int $id_entreprise): bool {
        $sql = "DELETE FROM ENTREPRISE WHERE id_entreprise = :id_entreprise";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_entreprise' => $id_entreprise]);
    }

    /// READ ONE ///
    public function findClientById(int $id_entreprise): ?array {
        $sql = "SELECT * FROM ENTREPRISE WHERE id_entreprise = :id_entreprise";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_entreprise' => $id_entreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}