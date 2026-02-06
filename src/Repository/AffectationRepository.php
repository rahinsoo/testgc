<?php

namespace Repository;

use PDO;
use Model\Affectation;

readonly class AffectationRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /// vérifier si l'affectation existe déjà ///
    public function exists(int $id_user, int $id_activite): bool
{
    $stmt = $this->pdo->prepare("
        SELECT 1
        FROM affecter
        WHERE id_user = :id_user
          AND id_activite = :id_activite
    ");

    $stmt->execute([
        'id_user' => $id_user,
        'id_activite' => $id_activite
    ]);

    return (bool) $stmt->fetchColumn();
}

    /// affecter un utilisateur à une activité avec un TJM ///
    public function affecter(Affectation $affectation): void
    {
        if ($this->exists($affectation->getIdUser(), $affectation->getIdActivite())) {
            // Option : lancer une exception ou juste ignorer
            throw new \RuntimeException("L'utilisateur est déjà affecté à cette activité.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO affecter (id_user, id_activite, tjm)
            VALUES (:id_user, :id_activite, :tjm)
        ");

        $stmt->execute([
            'id_user' => $affectation->getIdUser(),
            'id_activite' => $affectation->getIdActivite(),
            'tjm' => $affectation->getTjm()
        ]);
    }

    /// Mettre à jour le TJM pour une affectation existante ///
    public function updateTjm(int $id_user, int $id_activite, float $tjm): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE affecter
            SET tjm = :tjm
            WHERE id_user = :id_user
              AND id_activite = :id_activite
        ");

        $stmt->execute([
            'tjm' => $tjm,
            'id_user' => $id_user,
            'id_activite' => $id_activite
        ]);
    }

    /// supprimer une afffectation ///
    public function delete(int $id_user, int $id_activite): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM affecter
            WHERE id_user = :id_user
              AND id_activite = :id_activite
        ");

        $stmt->execute([
            'id_user' => $id_user,
            'id_activite' => $id_activite
        ]);
    }

    /// récupérer toutes les affectations d'un utilisateur ///
    public function findByUser(int $id_user): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM affecter
            WHERE id_user = :id_user
        ");

        $stmt->execute(['id_user' => $id_user]);

        $affectations = [];
        while ($row = $stmt->fetch()) {
            $affectations[] = new Affectation(
                $row['id_user'],
                $row['id_activite'],
                (float) $row['tjm']
            );
        }

        return $affectations;
    }

    /// lire toutes les affectations ///
    public function findAllWithDetails(): array
    {
        $sql = $this->pdo->query("
        SELECT a.id_user, u.nom AS user_nom, u.prenom AS user_prenom,
               a.id_activite, act.nom AS activite_nom, a.tjm
        FROM affecter a
        INNER JOIN utilisateur u ON u.id_user = a.id_user
        INNER JOIN activite act ON act.id_activite = a.id_activite
        ORDER BY u.nom, act.nom
    ");

        $rows = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return [
                'id_user' => $row['id_user'],
                'user_nom' => $row['user_nom'],
                'user_prenom' => $row['user_prenom'],
                'id_activite' => $row['id_activite'],
                'activite_nom' => $row['activite_nom'],
                'tjm' => (float)$row['tjm']
            ];
        }, $rows);
    }
}