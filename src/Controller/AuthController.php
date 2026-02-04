<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

readonly class AuthController
{
    public function __construct(
        private Response $response,
        private UserRepository $userRepository,
        private Session $session,
        private Request $request
    ) {}

    public function login(): void
    {
        $this->response->render('auth/login', []);
    }

    #[NoReturn]
    public function authenticate(): void
    {
        $identifiant = $_POST['identifiant'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->session->flash('error', 'Identifiants invalides');
            header('Location: /login');
            exit;
        }

        unset($user['password']);
        $this->session->set('user', $user);

        header('Location: /home');
        exit;
    }

    #[NoReturn]
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

}