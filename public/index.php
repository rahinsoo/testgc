<?php

use Controller\AppController;
use Repository\HomeRepository;
use Repository\CustomerRepository;
use Repository\RoleRepository;
use Repository\UserRepository;
use Repository\TaskRepository;

use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
//use Controller\TaskController;
use Controller\CustomerController;

use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;

session_start();
require __DIR__ . '/../autoload.php';
$config = require_once __DIR__ . '/../config/db.php';

Cors::handle();

$session = new Session();
$request = new Request();
$response = new Response();
$router = new Router();

$homeRepository = new HomeRepository(Database::makePdo($config['db']));
$CustomerRepository = new CustomerRepository(Database::makePdo($config['db']));
$userRepository = new UserRepository(Database::makePdo($config['db']));
$roleRepository = new RoleRepository(Database::makePdo($config['db']));
//$taskRepository = new TaskRepository(Database::makePdo($config['db']));

$AppController = new AppController($response,$homeRepository, $session, $request);
$authController = new AuthController($response, $userRepository, $session, $request);
$userController = new UserController($response, $userRepository, $roleRepository, $session, $request);
$dashboardController = new DashboardController($response, $session, $request);
$passwordController = new PasswordController($response, $userRepository, $session);
$userApiController = new UserApiController($userRepository, $session, $request);
//$taskController = new TaskController($response, $taskRepository, $session, $request);
$CustomerController = new CustomerController($response, $CustomerRepository, $session, $request);

$registerRoutes = require __DIR__ . '/../config/routes.php';

// Respecter l'ordre défini dans routes.php
$registerRoutes(
    $router,              // #1 Router
    $AppController,       // #2 AppController
    $CustomerController,  // #3 CustomerController
    $userController,      // #4 UserController
    $authController,      // #5 AuthController
    $dashboardController, // #6 DashboardController
    $passwordController,  // #7 PasswordController
    $userApiController    // #8 UserApiController
);

$router->dispatch($request, $response);


