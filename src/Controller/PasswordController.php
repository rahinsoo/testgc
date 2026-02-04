<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Repository\UserRepository;

use JetBrains\PhpStorm\NoReturn;

readonly class PasswordController
{
    public function __construct(
        private Response $response,
        private UserRepository $userRepository,
        private Session $session,
        //private Request $request
    ) {}

    public function forgot(): void
    {
        $this->response->render('auth/forgot-password', []);
    }

    #[NoReturn]
    public function reset(): void
    {
        $identifiant = $_POST['identifiant'] ?? '';
        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user) {
            $this->session->flash('error', 'Identifiant introuvable');
            header('Location: /forgot-password');
            exit;
        }

        // Ici, on ne demande pas l’ancien mot de passe
        $newPassword = $_POST['new_password'] ?? '';
        if (!$newPassword) {
            $this->session->flash('error', 'Veuillez saisir un nouveau mot de passe');
            header('Location: /forgot-password');
            exit;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($user['id_user'], $hashedPassword);

        $this->session->flash('success', 'Mot de passe réinitialisé avec succès');
        header('Location: /login');
        exit;
    }
}