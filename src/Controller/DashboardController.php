<?php

/// protection de l'accès au dashboard ///

namespace Controller;

use Core\Session;
use Core\Response;

readonly class DashboardController
{
    /// le controller reçoit la session, il ne la crée pas ///
    public function __construct(private Session $session, private Response $response) {}

    /// affichage du dashboard seulement quand on est connecté ///
    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            $this->response->redirect('/login');
        }
    }

    public function index(): void
    {
        $this->denyIfNotLogged();
        /*if (!$this->session->isLogged()) {
            $this->response->redirect('auth/login');
        }*/

        /// récupération de l'user stocké en session ///
        /*require __DIR__ . '/../../views/pages/dashboard/index.php';*/
        $this->response->render('dashboard/index', [
            'user' => $this->session->get('user'),
            'isAdmin' => $this->session->isAdmin(),
            'isManager' => $this->session->isManager(),
            'isRecruteur' => $this->session->isRecruteur(),
            'isCollaborateur' => $this->session->isCollaborateur()
        ]);
    }

}