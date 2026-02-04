<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;
use Repository\CustomerRepository;

readonly class CustomerController
{
    public function __construct(
        private Response           $response,
        private CustomerRepository $customerRepository,
        private Session            $session,
        private Request            $request
    )
    {
    }

    // GET /customers
    public function listClient(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $clients = $this->customerRepository->findAllClients();
        $this->response->render('customer/listCustomer', [
            'listClient' => $clients
        ]);
    }

    #[NoReturn]
    public function createcustomer(): void
    {
        // Récupération des données du formulaire
        $nom = $this->request->getPostParam('nom');
        $numero_siren = $this->request->getPostParam('numero_SIREN');
        $type = $this->request->getPostParam('type');
        $information = $this->request->getPostParam('information') ?? '';
        $adresse = $this->request->getPostParam('adresse');
        $is_facturable = $this->request->getPostParam('is_facturable') === 'on' ||
            $this->request->getPostParam('is_facturable') === '1';

        // Validation basique
        if (empty($nom) || empty($numero_siren) || empty($type) || empty($adresse)) {
            $this->session->flash('error', 'Tous les champs obligatoires doivent être remplis.');
            header('Location: /customer/listCustomer');
            exit;
        }

        // Insertion en base
        $success = $this->customerRepository->createClient(
            nom: $nom,
            numero_siren: $numero_siren,
            type: $type,
            information: $information,
            is_facturable: $is_facturable,
            adresse: $adresse
        );

        if ($success) {
            $this->session->flash('success', 'Entreprise créée avec succès !  ✅');
        } else {
            $this->session->flash('error', 'Erreur lors de la création de l\'entreprise.');
        }

        header('Location:  /customer/listCustomer');
        exit;
    }

    // Récupérer un client en JSON
    public function getClient(int $id): void
    {
        if (!$this->session->isLogged()) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        $client = $this->customerRepository->findClientById($id);

        if (!$client) {
            http_response_code(404);
            echo json_encode(['error' => 'Client non trouvé']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($client);
        exit;
    }

// Mettre à jour un client
    #[NoReturn]
    public function updateClient(int $id): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $nom = $this->request->getPostParam('nom');
        $numero_siren = $this->request->getPostParam('numero_SIREN');
        $type = $this->request->getPostParam('type');
        $information = $this->request->getPostParam('information') ?? '';
        $adresse = $this->request->getPostParam('adresse');
        $is_facturable = $this->request->getPostParam('is_facturable') === 'on' ||
            $this->request->getPostParam('is_facturable') === '1';

        if (empty($nom) || empty($numero_siren) || empty($type) || empty($adresse)) {
            $this->session->flash('error', 'Tous les champs obligatoires doivent être remplis.');
            header('Location: /customer/listCustomer');
            exit;
        }

        $success = $this->customerRepository->updateClient(
            id_entreprise: $id,
            nom: $nom,
            numero_siren: $numero_siren,
            type: $type,
            information: $information,
            is_facturable: $is_facturable,
            adresse: $adresse
        );

        if ($success) {
            $this->session->flash('success', 'Client modifié avec succès ! ✅');
        } else {
            $this->session->flash('error', 'Erreur lors de la modification.');
        }

        header('Location: /customer/listCustomer');
        exit;
    }

// Supprimer un client
    #[NoReturn]
    public function deleteClient(int $id): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $success = $this->customerRepository->deleteClient($id);

        if ($success) {
            $this->session->flash('success', 'Client supprimé avec succès ! 🗑️');
        } else {
            $this->session->flash('error', 'Erreur lors de la suppression.');
        }

        header('Location: /customer/listCustomer');
        exit;
    }

}