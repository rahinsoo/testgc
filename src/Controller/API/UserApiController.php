<?php

namespace Controller\API;

use Core\Request;
use Model\User;
use Repository\UserRepository;
use Core\Session;

readonly class UserApiController
{
    public function __construct(
        private UserRepository $userRepository,
        private Session $session,
        private Request $request
    ) {}

    private function denyIfNotAdmin(): void
    {
        if (!$this->session->isLogged()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        if (!$this->session->isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
    }

    public function index(): void
    {
        $this->denyIfNotAdmin();
        $users = $this->userRepository->readAll();

        $usersArray = array_map(function($u){
            return [
                'id_user' => $u->getId(),
                'nom' => $u->getNom(),
                'prenom' => $u->getPrenom(),
                'identifiant' => $u->getIdentifiant(),
                'id_user_role' => $u->getRoleId(),
                'role' => $u->getNomRole()
            ];
        }, $users);

        echo json_encode($usersArray);
    }

    public function show(int $id_user): void
    {
        $this->denyIfNotAdmin();

        $user = $this->userRepository->readOne($id_user);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        echo json_encode([
            'id_user' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'identifiant' => $user->getIdentifiant(),
            'id_user_role' => $user->getRoleId(),
            'role' => $user->getNomRole()
        ]);
    }

    public function store(): void
    {
        $this->denyIfNotAdmin();

        $data = json_decode(file_get_contents("php://input"), true);
        $passwordHashed = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->userRepository->createUser(
            $data['nom'],
            $data['prenom'],
            $data['identifiant'],
            $passwordHashed,
            (int)$data['id_user_role']
        );

        http_response_code(201);
        echo json_encode(['ok' => true]);
    }

    public function update(int $id_user): void
    {
        $this->denyIfNotAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $user = $this->userRepository->readOne($id_user);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $updatedUser = new User(
            $id_user,
            $data['nom'] ?? $user->getNom(),
            $data['prenom'] ?? $user->getPrenom(),
            $data['identifiant'] ?? $user->getIdentifiant(),
            $user->getPassword(), // password non modifié
            $data['id_user_role'] ?? $user->getRoleId(),
            $user->getNomRole()
        );

        $this->userRepository->updateUser($updatedUser);
        echo json_encode(['ok' => true]);
    }

    public function destroy(int $id_user): void
    {
        $this->denyIfNotAdmin();

        $user = $this->userRepository->readOne($id_user);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $this->userRepository->deleteUser($id_user);
        http_response_code(204);
    }
}