<?php

use Controller\AppController;
use Core\Request;
use Core\Response;
use Core\Router;

return function (Router $router, AppController $controller) {
    $router->get('/', [$controller, 'home']);
    $router->get('/home', [$controller, 'home']);
    $router->get('/customer/listCustomer', [$controller, 'customer']);
    $router->post('/customer/listCustomer', [$controller, 'createCustomer']);
    $router->get('/pagetest', [$controller, 'pagetest']);
    $router->get('/customer/infoCustomer', [$controller, 'infoCustomer']);
//    $router->post('', [$controller, '']);
//    $router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($controller) {
//        $controller->gameById((int)$m[1]);
//    });
};