<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Repository\TaskRepository;

readonly class TaskController
{
    public function __construct(
        private Response $response,
        private TaskRepository $TaskRepository,
        private Session  $session,
        private Request  $request

    ) {}

    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            header('Location: /login');
            exit;
        }
    }

    public function tasks(): void
    {
        $tasks = $this->TaskRepository->findByUser(1); // --> rechercher id_user
        $this->response->render('tasks/index', [
            'tasks' => $tasks
        ]);
    }

    public function create(): void
    {
        $this->denyIfNotLogged();

        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getBody();

            TaskRepository::create(
                $data['title'] ?? '',
                $data['description'] ?? '',
                $this->session->get('user')['id_user']
            );

            header('Location: /tasks');
            exit;
        }

        $this->response->render('tasks/create', []);
    }

    public function edit(int $id): void
    {
        $this->denyIfNotLogged();

        $task = TaskRepository::findOneByUser($id, $this->session->get('user')['id_user']);

        if (!$task) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }

        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getBody();

            TaskRepository::update(
                $id,
                $data['title'] ?? '',
                $data['description'] ?? ''
            );

            header('Location:  /tasks');
            exit;
        }

        $this->response->render('tasks/edit', [
            'task' => $task
        ]);
    }

    public function delete(int $id): void
    {
        $this->denyIfNotLogged();

        $task = TaskRepository::findOneByUser($id, $this->session->get('user')['id_user']);

        if (!$task) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }

        TaskRepository::delete($id);
        header('Location: /tasks');
        exit;
    }
}