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
        private RoleRepository $roleRepository
    ) {}

    public function index(): void
    {
        $users = $this->userRepository->readAll();
        require __DIR__ . '/../../views/pages/user/liste.php';
    }

    public function create(): void
    {
        $roles = $this->roleRepository->readAll();
        require __DIR__ . '/../../views/pages/user/create.php';
    }

    #[NoReturn]
    public function store(): void
    {
        $this->userRepository->createUser(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['identifiant'],
            $_POST['password'],
            (int)$_POST['id_user_role']
        );

        header('Location: /users');
        exit;
    }

    public function edit(int $id_user): void
    {
        $roles = $this->roleRepository->readAll();
        $user = $this->userRepository->readOne($id_user);
        require __DIR__ . '/../../views/pages/user/edit.php';
    }

    #[NoReturn]
    public function update(int $id_user): void
    {
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
            $_POST['role']
        );

        $this->userRepository->updateUser($updatedUser);

        header('Location: /users');
        exit;
    }

    public function changePassword(int $id_user): void
    {
        // Affiche le formulaire de changement de mot de passe
        $user = $this->userRepository->readOne($id_user);
        require __DIR__ . '/../../views/pages/user/change-password.php';
    }


    public function updatePassword(int $id_user): void
    {
        // Vérifie l'ancien mot de passe (optionnel mais recommandé)
        $user = $this->userRepository->readOne($id_user);

        if (!password_verify($_POST['old_password'], $user->getPassword())) {
            // Mauvais ancien mot de passe
            $_SESSION['error'] = 'Ancien mot de passe incorrect';
            header('Location: /users/' . $id_user . '/change-password');
            exit;
        }

        // Met à jour le mot de passe
        $this->userRepository->updatePassword($id_user, $_POST['new_password']);

        $_SESSION['success'] = 'Mot de passe modifié avec succès';
        header('Location: /users');
        exit;
    }

    #[NoReturn]
    public function delete(int $id_user): void
    {
        $this->userRepository->deleteUser($id_user);
        header('Location: /users');
        exit;
    }
}
