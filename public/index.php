<?php

use Controller\AppController;
use Repository\HomeRepository;
use Repository\CustomerRepository;
use Controller\UserController;
use Repository\RoleRepository;
use Repository\UserRepository;

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


$AppController = new AppController($response,$homeRepository, $CustomerRepository, $request);
//$userRepository = new UserRepository(Database::makePdo($config['db']));
//$roleRepository = new RoleRepository(Database::makePdo($config['db']));
//$userController = new UserController($userRepository, $roleRepository);

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $AppController);
$router->dispatch($request, $response);


