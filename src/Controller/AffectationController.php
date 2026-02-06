<?php

namespace Controller;

use Model\Affectation;
use Repository\AffectationRepository;
use Repository\ActiviteRepository;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

readonly class AffectationController
{
    public function __construct(
        private AffectationRepository $affectationRepository,
        private UserRepository        $userRepository,
        private ActiviteRepository    $activiteRepository,
        private Session               $session,
        private Response             $response,
        private Request $request
    ) {}

    private function denyIfNotRecruteur(): void
    {
        if (!$this->session->isLogged()) {
            $this->response->redirect('/login');
        }

        if (!$this->session->isRecruteur()) {
            $this->session->flash('error', 'Accès réservé');
            $this->response->redirect('/dashboard');
        }
    }

    /// Liste toutes les affectations ///
    public function index(): void
    {
        $this->denyIfNotRecruteur();

        $affectations = $this->affectationRepository->findAllWithDetails();

        // Vue : liste des affectations
        /*require __DIR__ . '/../../views/pages/affectations/list.php';*/
        $this->response->render('affectations/list', [
            'affectations' => $affectations
        ]);
    }

    /// Formulaire pour créer une nouvelle affectation ///
    /// utilisation des jointures faites dans le Repo ///
    public function create(): void
    {
        $this->denyIfNotRecruteur();

        $users = $this->userRepository->findCollaborateurs();
        $activites = $this->activiteRepository->readAll();

        /*require __DIR__ . '/../../views/pages/affectations/create.php';*/
        $this->response->render('affectations/create', [
            'activites' => $activites,
            'users' => $users
        ]);
    }

    /// envoi des données de création en BDD ///
    #[NoReturn]
    public function store(): void
    {
        $this->denyIfNotRecruteur();

        if (!$this->request->isPost()) {
            $this->response->redirect('/affectations');
        }

        $id_user = (int) $this->request->post('id_user');
        $id_activite = (int) $this->request->post('id_activite');
        $tjm = (float) $this->request->post('tjm');

        $affectation = new Affectation($id_user, $id_activite, $tjm);

        try {
            $this->affectationRepository->affecter($affectation);
            $this->session->flash('success', 'Utilisateur affecté avec succès.');
        } catch (\RuntimeException $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/affectations');
    }

    /// Supprimer une affectation ///
    #[NoReturn]
    public function delete(int $id_user, int $id_activite): void
    {
        $this->denyIfNotRecruteur();

        $this->affectationRepository->delete($id_user, $id_activite);

        $this->session->flash('success', 'Affectation supprimée avec succès.');
        $this->response->redirect('/affectations');
    }

    /// mise à jour TJM ///
    #[NoReturn]
    public function updateTjm(int $id_user, int $id_activite): void
    {
        $this->denyIfNotRecruteur();

        if (!$this->request->isPost()) {
            $this->response->redirect('/affectations');
        }

        $tjm = (float)$this->request->post('tjm');

        if (!is_numeric($tjm) || $tjm <= 0) {
            $this->session->flash('error', 'Le TJM doit être un nombre positif.');
            $this->response->redirect('/affectations');
        }

        $this->affectationRepository->updateTjm($id_user, $id_activite, (float)$tjm);

        $this->session->flash('success', 'TJM mis à jour avec succès.');
        $this->response->redirect('/affectations');
    }
}