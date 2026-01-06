<?php

namespace Repository;

use PDO;

readonly final class GamesRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllSortedByRating() : array {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY rating DESC");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() : array {
        $sql = $this->pdo->query("SELECT * FROM games");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findTop(int $limit) : array {
        $sql = $this->pdo->prepare("SELECT * FROM games ORDER BY id LIMIT :limit"); // On ajoute du dynamise avec le token :limit.
        $sql->bindValue('limit', $limit, PDO::PARAM_INT); // On lie le token :limit à l'argument $limit.
        $sql->execute(); // On execute.
        return $sql->fetchAll(PDO::FETCH_ASSOC); // On retourne nos valeurs.
    }

    public function findById(int $id) : ?array {
        $sql = $this->pdo->prepare("SELECT * FROM games WHERE id = :id");
        $sql->bindValue('id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function countAll() : int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM games");
        return $sql->fetch(PDO::FETCH_COLUMN);
    }

    public function findRandom() {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY RAND() LIMIT 1");
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function createGame(array $data) : int {
        $sql = $this->pdo->prepare("INSERT INTO games (title, platform, genre, releaseYear, rating, description, notes) VALUES (:title, :platform, :genre, :releaseYear, :rating, :description, :notes)");
        $sql->execute([
            'title' => $data['title'],
            'platform' => $data['platform'],
            'genre' => $data['genre'],
            'releaseYear' => $data['releaseYear'],
            'rating' => $data['rating'],
            'description' => $data['description'],
            'notes' => $data['notes']
        ]);

        return $this->pdo->lastInsertId();
    }


    /**
     * A2. 1) Récupère les jeux les mieux notés (rating décroissant)
     * Utilise ORDER BY pour trier par note décroissante
     */
    public function findTopRated(int $limit = 10) : array {
        $sql = $this->pdo->prepare("SELECT * 
            FROM games 
            ORDER BY rating DESC, title ASC 
            LIMIT :limit
        ");
        $sql->bindValue('limit', $limit, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * A2.2) Récupère les jeux les plus récents (année de sortie décroissante)
     * Utilise ORDER BY pour trier par année de sortie décroissante
     */
    public function findRecent(int $limit = 10) : array {
        $sql = $this->pdo->prepare("
            SELECT * 
            FROM games 
            ORDER BY releaseYear DESC, title ASC 
            LIMIT :limit
        ");
        $sql->bindValue('limit', $limit, PDO:: PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO:: FETCH_ASSOC);
    }

    /**
     * A2.3) Compte le nombre de jeux pour chaque note
     * Utilise GROUP BY pour grouper par rating et COUNT pour compter
     */
    public function countGamesByRating() : array {
        $sql = $this->pdo->query("
            SELECT 
                rating, 
                COUNT(*) as count 
            FROM games 
            GROUP BY rating 
            ORDER BY rating DESC
        ");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}