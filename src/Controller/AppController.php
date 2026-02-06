<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\HomeRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController {

    public function __construct(
        private Response $response,
        private HomeRepository $homeRepository,
        private Session $session,
        private Request $request
    ) {}

    public function home() : void {
        // ✅ SÉCURISATION :  vérifier si connecté
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $clients = $this->homeRepository->findAllClients();

        $this->response->render('home', [
            'featuredClient' => $clients,
            'total' => $this->homeRepository->countAll()
        ]);
    }

    public function profile(): void
    {
        // Vérifier l'authentification
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        // Afficher la page de paramètres
        $this->response->render('profile', [
            'user' => $this->session->get('user')
        ]);
    }

    public function settings(): void
    {
        // Vérifier l'authentification
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        // Afficher la page de paramètres
        $this->response->render('settings', [
            'user' => $this->session->get('user')
        ]);
    }

    public function pagetest() : void
    {
        $this->response->render('pagetest', [
        ]);
    }

    public function notFound() : void {
        $this->response->render('not-found', [], 404);
    }

}