<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;

readonly class DashboardController
{
    public function __construct(
        private Response $response,
        private Session $session,
        private Request $request
    ) {}

    public function index(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $user = $this->session->get('user');
        $this->response->render('dashboard/index', [
            'user' => $user
        ]);
    }

}