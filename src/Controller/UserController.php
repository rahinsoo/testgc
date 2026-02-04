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
        private Response $response,
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private Session $session,
        private Request $request
    ) {}

    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            header('Location: /login');
            exit;
        }
    }

    private function denyIfNotAdmin(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        if (!$this->session->isAdmin()) {
            header('Location: /dashboard');
            exit;
        }
    }

    public function index(): void
    {
        $this->denyIfNotAdmin();
        $users = $this->userRepository->readAll();
        $this->response->render('user/list', [
            'users' => $users
        ]);
    }

    public function create(): void
    {
        $this->denyIfNotAdmin();
        $roles = $this->roleRepository->readAll();
        $this->response->render('user/create', [
            'roles' => $roles
        ]);
    }

    #[NoReturn]
    public function store(): void
    {
        $this->denyIfNotAdmin();

        $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->userRepository->createUser(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['identifiant'],
            $passwordHashed,
            (int)$_POST['id_user_role']
        );

        header('Location: /users');
        exit;
    }

    public function edit(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $roles = $this->roleRepository->readAll();
        $user = $this->userRepository->readOne($id_user);
        $this->response->render('user/edit', [
            'roles' => $roles,
            'user' => $user
        ]);
    }

    #[NoReturn]
    public function update(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $user = $this->userRepository->readOne($id_user);
        if (!$user) {
            header('Location: /users');
            exit;
        }

        $updatedUser = new User(
            $id_user,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['identifiant'],
            $user->getPassword(), // Le password n'est pas modifié ici
            $_POST['id_user_role'],
            $user->getNomRole()
        );

        $this->userRepository->updateUser($updatedUser);

        header('Location: /users');
        exit;
    }

    public function changePassword(int $id_user): void
    {
        $this->denyIfNotLogged();
        $user = $this->userRepository->readOne($id_user);
        $this->response->render('user/change-password', [
            'user' => $user
        ]);
    }

    #[NoReturn]
    public function updatePassword(int $id_user): void
    {
        $this->denyIfNotLogged();

        // Vérifie l'ancien mot de passe
        $user = $this->userRepository->readOne($id_user);
        if (!password_verify($_POST['old_password'], $user->getPassword())) {
            $this->session->flash('error', 'Ancien mot de passe incorrect');
            header('Location: /users/' . $id_user . '/change-password');
            exit;
        }

        // Met à jour le mot de passe
        $newPasswordHashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($id_user, $newPasswordHashed);

        $this->session->flash('success', 'Mot de passe modifié avec succès');
        header('Location: /users');
        exit;
    }

    #[NoReturn]
    public function delete(int $id_user): void
    {
        $this->denyIfNotAdmin();
        $this->userRepository->deleteUser($id_user);
        header('Location: /users');
        exit;
    }
}
