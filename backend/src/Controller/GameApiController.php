<?php

namespace Controller;

use Core\Response;
use Repository\GamesRepository;

final readonly class GameApiController
{
    public function __construct(
        private Response $response,
        private GamesRepository $gamesRepository
    ) {}

    /**
     * GET /api/games/top
     * Retourne les jeux les mieux notés
     */
    public function top(): void
    {
        $games = $this->gamesRepository->findTopRated();
        $this->response->json([
            'success' => true,
            'data' => $games,
            'count' => count($games)
        ], 200);
    }

    /**
     * GET /api/games/recent
     * Retourne les jeux les plus récents
     */
    public function recent(): void
    {
        $games = $this->gamesRepository->findRecent();
        $this->response->json([
            'success' => true,
            'data' => $games,
            'count' => count($games)
        ], 200);
    }

    /**
     * GET /api/stats/ratings
     * Retourne le nombre de jeux pour chaque note
     */
    public function ratingsStats(): void
    {
        $stats = $this->gamesRepository->countGamesByRating();
        $this->response->json([
            'success' => true,
            'data' => $stats,
            'total' => array_sum(array_column($stats, 'count'))
        ], 200);
    }
}