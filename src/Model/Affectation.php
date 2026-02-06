<?php

namespace Model;

readonly class Affectation
{
    public function __construct(
        private int $id_user,
        private int $id_activite,
        private float $tjm
    ) {}

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function getIdActivite(): int
    {
        return $this->id_activite;
    }

    public function getTjm(): float
    {
        return $this->tjm;
    }
}