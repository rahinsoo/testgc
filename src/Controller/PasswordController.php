<?php

namespace Controller;

use Core\Session;
use Core\Response;
use Core\Request;
use Repository\UserRepository;

use JetBrains\PhpStorm\NoReturn;

readonly class PasswordController
{
    public function __construct(
        private UserRepository $userRepository,
        private Session $session,
        private Response $response,
        private Request $request
    ) {}

    public function forgot(): void
    {
        /*require __DIR__ . '/../../views/pages/auth/forgot-password.php';*/
        $this->response->render('auth/forgot-password', [
            'session' => $this->session
        ]);
    }

    #[NoReturn]
    public function reset(): void
    {
        $identifiant = $this->request->post('identifiant') ?? '';
        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user) {
            $this->session->flash('error', 'Identifiant introuvable');
            /*header('Location: /forgot-password');
            exit;*/
            $this->response->redirect('/forgot-password');
        }

        // Ici, on ne demande pas l’ancien mot de passe
        $newPassword = $this->request->post('new_password') ?? '';
        if (!$newPassword) {
            $this->session->flash('error', 'Veuillez saisir un nouveau mot de passe');
            /*header('Location: /forgot-password');
            exit;*/
            $this->response->redirect('/forgot-password');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($user['id_user'], $hashedPassword);

        $this->session->flash('success', 'Mot de passe réinitialisé avec succès');
        /*header('Location: /login');
        exit;*/
        $this->response->redirect('/login');
    }

    /// sécurité avec Token ///
    /*#[NoReturn]
    public function sendResetLink(): void
    {
        $identifiant = $this->request->post('identifiant');
        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user) {
            $this->session->flash('error', 'Identifiant introuvable');
            $this->response->redirect('/forgot-password');
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1h

        $this->userRepository->saveResetToken(
            $user->getId(),
            $token,
            $expiresAt
        );

        // pour l’instant on affiche le lien (simulation email)
        $this->session->flash(
            'info',
            "Lien de réinitialisation : /reset-password?token=$token"
        );

        $this->response->redirect('/login');
    }

    public function resetForm(): void
    {
        $token = $this->request->get('token');
        $this->response->render('auth/reset-password', ['token' => $token]);
    }

    #[NoReturn]
    public function resetPassword(): void
    {
        $token = $this->request->post('token');
        $newPassword = $this->request->post('new_password');

        $user = $this->userRepository->findByResetToken($token);

        if (!$user) {
            $this->session->flash('error', 'Lien invalide ou expiré');
            $this->response->redirect('/forgot-password');
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($user->getId(), $hashed);
        $this->userRepository->clearResetToken($user->getId());

        $this->session->flash('success', 'Mot de passe réinitialisé');
        $this->response->redirect('/login');
    }*/
}