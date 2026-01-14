<?php

namespace Model;

readonly class Entreprise {

    public function __construct(
        private int $id_entreprise,
        private string $nom,
        private string $numero_SIRET,
        private string $type,
        private string $information,
        private int $is_facturable,
        private string $adresse,
        private int $id_projet,
    ) {}

    public function getFindAllEntreprise()
    {

    }
