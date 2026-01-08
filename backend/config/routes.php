<?php

use Controller\AppController;
//use Controller\GameApiController;
//use Controller\PingApiController;
use Core\Request;
use Core\Response;
use Core\Router;

return function (Router $router, AppController $controller) {
    $router->get('/', [$controller, 'Tableau de bord']);
    $router->get('/client', [$controller, 'client']);
    $router->get('/games', [$controller, 'games']);
    $router->get('/random', [$controller, 'random']);
    $router->post('/add', [$controller, 'handleAddGame']);
    //$router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($controller) {
        //$controller->gameById((int)$m[1]);
    //});
};