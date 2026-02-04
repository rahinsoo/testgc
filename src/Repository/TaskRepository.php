<?php

namespace Repository;

use PDO;

readonly class TaskRepository
{
    public function __construct(private PDO $pdo)
    {}

    /**
     * Récupère toutes les tâches d'un utilisateur
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM tache
            WHERE id_user = : id_user
            ORDER BY id_tache DESC
        ");
        $stmt->execute(['id_user' => $userId]);
        return $stmt->fetchAll(PDO:: FETCH_ASSOC);
    }

    /**
     * Récupère une tâche spécifique d'un utilisateur
     */
    public function findOneByUser(int $taskId, int $userId): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM tache
            WHERE id_tache = :id AND id_user = :user
        ");
        $stmt->execute([
            'id' => $taskId,
            'user' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle tâche
     */
    public function create(string $title, string $description, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tache (title, description, id_user)
            VALUES (:title, :description, :user)
        ");
        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'user' => $userId
        ]);
    }

    /**
     * Met à jour une tâche
     */
    public function update(int $taskId, string $title, string $description): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE tache
            SET title = :title, description = :description
            WHERE id_tache = : id
        ");
        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'id' => $taskId
        ]);
    }

    /**
     * Supprime une tâche
     */
    public function delete(int $taskId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM tache WHERE id_tache = :id
        ");
        return $stmt->execute(['id' => $taskId]);
    }
}