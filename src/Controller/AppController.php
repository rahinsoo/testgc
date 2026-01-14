<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
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
        //private Session $session,
        //private Request $request,
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

    public function pagetest() : void
    {
        $this->response->render('pagetest', [
        ]);
    }

    public function notFound() : void {
        $this->response->render('not-found', [], 404);
    }


}