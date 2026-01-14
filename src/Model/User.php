<?php

namespace Model;

readonly class User {

    public function __construct(
        private int $id_user,
        private string $nom,
        private string $prenom,
        private string $identifiant,
        private string $password,
        private int $id_user_role,
        private string $role,
    ) {}

    public function getId(): int
    {
        return $this->id_user;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleId(): int
    {
        return $this->id_user_role;
    }

    public function getNomRole(): string {
        return $this->role;
    }
}
