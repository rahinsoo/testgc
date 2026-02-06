<?php

namespace Controller;

use Model\User;
use Repository\RoleRepository;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

readonly class UserController
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private Session        $session,
        private Response        $response,
        private Request $request
    ) {}

    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            $this->response->redirect('/login');
        }
    }

    private function denyIfNotAdmin(): void
    {
        if (!$this->session->isLogged()) {
            $this->response->redirect('/login');
        }

        if (!$this->session->isAdmin()) {
            $this->response->redirect('/dashboard');
        }
    }

    public function index(): void
    {
        $this->denyIfNotAdmin();
        $users = $this->userRepository->readAll();
        /*require __DIR__ . '/../../views/pages/user/list.php';*/
        $this->response->render('user/list', [
            'users' => $users]
        );
    }

    public function create(): void
    {
        $this->denyIfNotAdmin();
        $roles = $this->roleRepository->readAll();
        /*require __DIR__ . '/../../views/pages/user/create.php';*/
        $this->response->render('user/create', [
            'roles' => $roles
        ]);
    }

    #[NoReturn]
    public function store(): void
    {
        $this->denyIfNotAdmin();

        if (!$this->request->isPost()) {
            $this->response->redirect('/users');
        }

        $passwordHashed = password_hash($this->request->post('password'), PASSWORD_DEFAULT);
        $this->userRepository->createUser(
            $this->request->post('nom'),
            $this->request->post('prenom'),
            $this->request->post('identifiant'),
            $passwordHashed,
            (int)$this->request->post('id_user_role')
        );

        $this->response->redirect('/users');
    }

    public function edit(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $roles = $this->roleRepository->readAll();
        $user = $this->userRepository->readOne($id_user);
        /*require __DIR__ . '/../../views/pages/user/edit.php';*/
        $this->response->render('user/edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    #[NoReturn]
    public function update(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $user = $this->userRepository->readOne($id_user);
        if (!$user) {
            $this->response->redirect('/users');
        }

        if (!$this->request->isPost()) {
            $this->response->redirect('/users');
        }

        $updatedUser = new User(
            $id_user,
            $this->request->post('nom'),
            $this->request->post('prenom'),
            $this->request->post('identifiant'),
            $user->getPassword(), // Le password n'est pas modifié ici
            $this->request->post('id_user_role'),
            $user->getNomRole()
        );

        $this->userRepository->updateUser($updatedUser);

        $this->response->redirect('/users');
    }

    public function changePassword(): void
    {
        $this->denyIfNotLogged();
        $user = $this->session->get('user');
        /*require __DIR__ . '/../../views/pages/user/change-password.php';*/
        $this->response->render('/profile/change-password');
    }

    #[NoReturn]
    public function updatePassword(): void
    {
        $this->denyIfNotLogged();

        // Vérifie l'ancien mot de passe
        $user = $this->session->get('user');

        if (!password_verify($this->request->post('old_password'), $user->getPassword())) {
            $this->session->flash('error', 'Ancien mot de passe incorrect');
            $this->response->redirect('/profile/change-password');
        }

        // Met à jour le mot de passe
        $newPassword = $this->request->post('new_password');
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->userRepository->updatePassword($user->getId(), $hashed);

        $this->session->flash('success', 'Mot de passe modifié avec succès');
        /*header('Location: /users');
        exit;*/
        $this->response->redirect('/profile');
    }

    #[NoReturn]
    public function delete(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $this->userRepository->deleteUser($id_user);
        $this->response->redirect('/users');
    }
}
