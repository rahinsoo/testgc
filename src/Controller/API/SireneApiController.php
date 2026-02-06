<?php

namespace Controller\API;

use Core\Session;

readonly class SireneApiController
{
    public function __construct(private Session $session) {}

    public function rechercherSiret(string $siret): void
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->session->isLogged()) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        // Validation du SIRET
        $siretClean = preg_replace('/\s/', '', $siret);
        if (!preg_match('/^\d{14}$/', $siretClean)) {
            http_response_code(400);
            echo json_encode(['error' => 'Le SIRET doit contenir 14 chiffres']);
            exit;
        }

        // REMPLACEZ par votre vraie clé API de l'INSEE
        // À obtenir sur : https://portail-api.insee.fr/catalog
        $apiKey = "f03b71b1-35dc-4291-bb71-b135dcd2911a";

        $url = "https://api.insee.fr/api-sirene/3.11/siret/{$siretClean}";

        $options = [
            'http' => [
                "method" => "GET",
                "header" =>
                    "Accept: application/json;charset=utf-8;qs=1\r\n" .
                    "X-INSEE-Api-Key-Integration: $apiKey\r\n"
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            // Check HTTP response headers to determine the error
            if (isset($http_response_header)) {
                foreach ($http_response_header as $header) {
                    if (strpos($header, '404') !== false) {
                        http_response_code(404);
                        echo json_encode(['error' => 'Entreprise non trouvée']);
                        exit;
                    }
                }
            }
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la recherche']);
            exit;
        }

        header('Content-Type: application/json');
        echo $response;
    }
}