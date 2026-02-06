<?php

/// importations de toutes les classes dont on a besoin ///
use Controller\AppController;

use Repository\ActiviteRepository;
use Repository\HomeRepository;
use Repository\CustomerRepository;
use Repository\RoleRepository;
use Repository\UserRepository;
use Repository\TaskRepository;
use Repository\AffectationRepository;

use Controller\UserController;
use Controller\ActiviteController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\AffectationController;
use Controller\API\UserApiController;
use Controller\API\SireneApiController;
//use Controller\TaskController;
use Controller\CustomerController;

use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;

/// activation des sessions ///
session_start();

/// chargement automatique des classes ///
require __DIR__ . '/../autoload.php';

/// contient la configuration de la BDD ///
$config = require_once __DIR__ . '/../config/db.php';


/// gère les Headers CORS ///
Cors::handle();

/// Création des objets ///
$session = new Session(); // encapsule $_SESSION
$request = new Request(); // infos de la requête (URL, GET, POST, headers...)
$response = new Response(); // ce que l'on renvoie (HTML, JSON, redirection...)
$router = new Router(); // fait le lien entre URL et Controller

/// injection de dépendances (création connexion PDO) ///
$homeRepository = new HomeRepository(Database::makePdo($config['db']));
$customerRepository = new CustomerRepository(Database::makePdo($config['db']));
$userRepository = new UserRepository(Database::makePdo($config['db']));
$roleRepository = new RoleRepository(Database::makePdo($config['db']));
//$taskRepository = new TaskRepository(Database::makePdo($config['db']));
$activiteRepository = new ActiviteRepository(Database::makePdo($config['db']));
$affectationRepository = new AffectationRepository(Database::makePdo($config['db']));


/// Instanciation des controllers avec SRP (Single Responsibility Principle) ///
$AppController = new AppController($response,$homeRepository, $session, $request);

$authController = new AuthController($userRepository, $session, $request, $response);
$userController = new UserController($userRepository, $roleRepository, $session, $response, $request);
$activiteController = new ActiviteController($activiteRepository, $customerRepository, $response, $session, $request);
$dashboardController = new DashboardController($session, $response);
$passwordController = new PasswordController($userRepository, $session, $response, $request);
$affectationController = new AffectationController($affectationRepository, $userRepository, $activiteRepository, $session, $response, $request);
$userApiController = new UserApiController($userRepository, $session);
$sireneApiController = new SireneApiController($session);
//$taskController = new TaskController($response, $taskRepository, $session, $request);
$CustomerController = new CustomerController($response, $CustomerRepository, $session, $request);

/// appel des routes ///
$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes(
    $router,                // Router
    $AppController,         // AppController
    $CustomerController,    // CustomerController
    $userController,        // UserController
    $authController,        // AuthController
    $dashboardController,   // DashboardController
    $passwordController,    // PasswordController
    $userApiController,     // UserApiController
    $activiteController,
    $affectationController,
    $sireneApiController    // SireneApiController
);

/// ligne qui lance l'appli ///
$router->dispatch($request, $response);


