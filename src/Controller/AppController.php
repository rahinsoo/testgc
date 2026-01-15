<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
use Helper\Csrf;
use Helper\Validator;
use Helper\ValidationConfig;
use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\HomeRepository;
use Repository\CustomerRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController {

    public function __construct(
        private Response $response,
        private HomeRepository $homeRepository,
        private CustomerRepository $customerRepository,
        private Request $request,
        //private Session $session,
    ) {}

    public function home() : void {
        $clients = $this->homeRepository->findAllClients();

        $this->response->render('home', [
            'featuredClient' => $clients,
            'total' => $this->homeRepository->countAll()
        ]);
    }

    public function customer() : void
    {
        $clients = $this->customerRepository->findAllClients();
        $this->response->render('/customer/listCustomer', [
            'listClient' => $clients
        ]);
    }

    public function infoCustomer() : void
    {
        $clients = $this->customerRepository->findAllClients();
        $this->response->render('/customer/infoCustomer', [
            'infoClient' => $clients
        ]);
    }

    public function pagetest() : void
    {
        $this->response->render('pagetest', [
        ]);
    }

    public function notFound() : void {
        $this->response->render('not-found', [], 404);
    }

    public function createCustomer() : void
    {
        // Verify CSRF token
        $token = $this->request->post('csrf_token');
        if (!Csrf::verifyToken($token)) {
            $this->response->render('/customer/listCustomer', [
                'listClient' => $this->customerRepository->findAllClients(),
                'error' => 'Token de sécurité invalide. Veuillez réessayer.'
            ]);
            return;
        }

        // Get and sanitize input
        $nom = Validator::sanitize($this->request->post('nom'));
        $numero_siret = Validator::sanitize($this->request->post('numero_SIRET'));
        $type = Validator::sanitize($this->request->post('type'));
        $information = Validator::sanitize($this->request->post('information'));
        $adresse = Validator::sanitize($this->request->post('adresse'));

        // Validate input
        $validator = new Validator();
        $validator->validateString(
            'nom', 
            $nom, 
            ValidationConfig::LENGTHS['name']['min'], 
            ValidationConfig::LENGTHS['name']['max'], 
            ValidationConfig::PATTERNS['name']
        );
        $validator->validateSiret('numero_SIRET', $numero_siret);
        $validator->validateString(
            'type', 
            $type, 
            ValidationConfig::LENGTHS['type']['min'], 
            ValidationConfig::LENGTHS['type']['max']
        );
        $validator->validateString(
            'adresse', 
            $adresse, 
            ValidationConfig::LENGTHS['adresse']['min'], 
            ValidationConfig::LENGTHS['adresse']['max']
        );

        if ($validator->hasErrors()) {
            $this->response->render('/customer/listCustomer', [
                'listClient' => $this->customerRepository->findAllClients(),
                'errors' => $validator->getErrors(),
                'old_input' => [
                    'nom' => $nom,
                    'numero_SIRET' => $numero_siret,
                    'type' => $type,
                    'information' => $information,
                    'adresse' => $adresse
                ]
            ]);
            return;
        }

        // Create client
        try {
            $success = $this->customerRepository->createClient(
                $nom,
                $numero_siret,
                $type,
                $information,
                $adresse
            );

            if ($success) {
                // Redirect to avoid form resubmission
                $this->response->redirect('/customer/listCustomer?success=1');
            } else {
                $this->response->render('/customer/listCustomer', [
                    'listClient' => $this->customerRepository->findAllClients(),
                    'error' => 'Une erreur est survenue lors de la création du client.'
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging (if logging is implemented)
            // error_log('Customer creation error: ' . $e->getMessage());
            
            $this->response->render('/customer/listCustomer', [
                'listClient' => $this->customerRepository->findAllClients(),
                'error' => 'Une erreur est survenue lors de la création du client. Veuillez réessayer.'
            ]);
        }
    }

}
