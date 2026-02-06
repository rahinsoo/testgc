<?php

namespace Repository;

use PDO;
use Model\User;

readonly class UserRepository
{
    public function __construct(private PDO $pdo)
    {}

    /// CREATE ///
    public function createUser(
        string $nom,
        string $prenom,
        string $identifiant,
        string $passwordHashed,
        int    $id_user_role
    ): bool
    {
        $sql = "INSERT INTO utilisateur (nom, prenom, identifiant, password, id_user_role) 
                VALUES (:nom, :prenom, :identifiant, :password, :role)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'identifiant' => $identifiant,
            'password' => $passwordHashed,
            'role' => $id_user_role
        ]);
    }

    /// READ ///
    public function readAll(): array
    {
        $sql = $this->pdo->query
        ("SELECT
        u.id_user,
        u.nom,
        u.prenom,
        u.identifiant,
        u.id_user_role,
        r.role
        FROM utilisateur u
        LEFT JOIN user_role r ON u.id_user_role = r.id_user_role");
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            return new User(
                $row['id_user'],
                $row['nom'],
                $row['prenom'],
                $row['identifiant'],
                '',
                $row['id_user_role'],
                $row['role']
            );
        },
            $rows);
    }

    public function readOne(int $id_user) : User | false {
        $sql = $this->pdo->prepare("SELECT
        u.id_user,
        u.nom,
        u.prenom,
        u.identifiant,
        u.id_user_role,
        r.role
        FROM utilisateur u
        LEFT JOIN user_role r ON u.id_user_role = r.id_user_role 
        WHERE id_user = :id_user");
        $sql->execute(['id_user' => $id_user]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        return new User(
            $row['id_user'],
            $row['nom'],
            $row['prenom'],
            $row['identifiant'],
            '',
            $row['id_user_role'],
            $row['role']
        );
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

    /// UPDATE / MODIF juste du password ///
    public function updatePassword(int $id_user, string $passwordHashed): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE utilisateur SET password = :password WHERE id_user = :id_user"
        );

        return $stmt->execute([
            'id_user' => $id_user,
            'password' => $passwordHashed
        ]);
    }

    /// DELETE ///
    public function deleteUser(int $id_user): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM utilisateur WHERE id_user = :id_user"
        );

        return $stmt->execute(['id_user' => $id_user]);
    }

    public function findByIdentifiant(string $identifiant): ?array
    {
        $sql = $this->pdo->prepare(
            "SELECT * FROM utilisateur WHERE identifiant = :identifiant"
        );

        $sql->bindValue(':identifiant', $identifiant);
        $sql->execute();

        $user = $sql->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findCollaborateurs(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM utilisateur WHERE id_user_role = :id_user_role"
        );
        $stmt->execute(['id_user_role' => 2]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /// sécurité pour mot de passe oublié ///
    /*public function saveResetToken(int $userId, string $token, string $expiresAt): void
    {
        $sql = "UPDATE utilisateur
            SET reset_token = :token, reset_token_expires_at = :expires
            WHERE id_user = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'token' => $token,
            'expires' => $expiresAt,
            'id' => $userId
        ]);
    }

    public function findByResetToken(string $token): ?User
    {
        $sql = "SELECT * FROM utilisateur
            WHERE reset_token = :token
            AND reset_token_expires_at > NOW()";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['token' => $token]);

        $data = $stmt->fetch();
        return $data ? $this->hydrate($data) : null;
    }

    public function clearResetToken(int $userId): void
    {
        $sql = "UPDATE user
            SET reset_token = NULL, reset_token_expires_at = NULL
            WHERE id_user = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
    }*/

}