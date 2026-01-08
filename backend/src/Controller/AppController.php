<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\DataPunchRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController {

    public function __construct(
        private Response $response,
        private DataPunchRepository $datapunchRepository,
        private Session $session,
        private Request $request,
    ) {}

    public function home() : void {
        $games = $this->datapunchRepository->findTop(3);

        $this->response->render('home', [
            'featuredGames' => $games,
            'total' => $this->datapunchRepository->countAll()
        ]);
    }

    public function games() : void {
        $games = $this->datapunchRepository->findAllSortedByRating();

        $this->response->render('games', [
            'games' => $games
        ]);
    }

    public function gameById (int $id) : void {
        $game = $this->datapunchRepository->findById($id);
        $success = $this->session->pullFlash('success');
        $this->response->render('detail', [
            'id' => $id,
            'game' => $game,
            'success' => $success
        ]);
    }

    public function notFound() : void {
        $this->response->render('not-found', [], 404);
    }

    #[NoReturn]
    public function random() : void {
        $lastId = $this->session->get('last_random_id') ?? null;
        $game = null;

        for ($i = 0; $i < 5; $i++) {
            $candidate = $this->datapunchRepository->findRandom();

            if ($candidate['id'] !== $lastId) {
                $game = $candidate;
            }
        }

        $id = $game['id'];

        $this->session->set('last_random_id', $id);

        $this->response->redirect('/games/' . $id);
    }

    public function add(): void {
        if ($this->request->isPost()) {
            $this->handleAddGame();
            return;
        }

        $this->response->render('add', []);
    }

}