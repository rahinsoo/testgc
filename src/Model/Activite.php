<?php

namespace Model;

readonly class Activite
{
    public function __construct(
        private int $id_activite,
        private string $nom,
        private string $description,
        private \DateTimeImmutable $date_creation,
        private ?\DateTimeImmutable $date_fin,
        private string $statut,
        private int $id_client,
        private string $nom_client
    ) {}

    public function getId(): int {
        return $this->id_activite;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDateCreation(): \DateTimeImmutable {
        return $this->date_creation;
    }

    public function getDateFin(): ?\DateTimeImmutable {
        return $this->date_fin;
    }

    public function getStatut(): string {
        return $this->statut;
    }

    public function getIdClient(): int {
        return $this->id_client;
    }

    public function getNomClient(): string {
        return $this->nom_client;
    }
}