<?php

/// afficher la page login et vérifier les identifiants ///

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

readonly class AuthController
{
    /// le controller coordonne les outils qu'on lui donne, il ne crée rien ///
    public function __construct(
        private UserRepository $userRepository,
        private Session $session,
        private Request $request,
        private Response $response
    ) {}

    /// affichage du formulaire de connexion ///
    public function login(): void
    {
        /*require __DIR__ . '/../../views/pages/auth/login.php';*/
        $this->response->render('auth/login');
    }

    /// méthode appelée après soumission du formulaire ///
    #[NoReturn]
    public function authenticate(): void
    {
        if (!$this->request->isPost()) {
            $this->response->redirect('/login');
        }

        $identifiant = $this->request->post('identifiant');
        $password = $this->request->post('password');

        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->session->flash('error', 'Identifiants invalides');
            header('Location: /login');
            exit;
        }

        unset($user['password']);
        $this->session->set('user', $user);

        /*if ($this->session->isAdmin()) {
            header('Location: /users');
        } else {
            header('Location: /dashboard');
        }*/
        /*header('Location: /dashboard');
        exit;*/
        $this->response->redirect('/dashboard');
    }

    #[NoReturn]
    public function logout(): void
    {
        $this->session->destroy();
        /*header('Location: /login');
        exit;*/
        $this->response->redirect('/login');
    }

}