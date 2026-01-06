<?php

use Controller\AppController;
use Controller\GameApiController;
use Controller\PingApiController;
use Core\Request;
use Core\Response;
use Core\Router;

return function (Router $router, AppController $controller, PingApiController $pingApiController, GameApiController $gameApiController) {
    $router->get('/', [$controller, 'home']);
    $router->get('/add', [$controller, 'add']);
    $router->get('/games', [$controller, 'games']);
    $router->get('/random', [$controller, 'random']);
    $router->post('/add', [$controller, 'handleAddGame']);
    $router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($controller) {
        $controller->gameById((int)$m[1]);
    });

    // Routes API
    $router->get('/api/ping', [$pingApiController, 'ping']);

    // A3) Nouvelles routes API pour les jeux
    $router->get('/api/games/top', [$gameApiController, 'top']);
    $router->get('/api/games/recent', [$gameApiController, 'recent']);
    $router->get('/api/stats/ratings', [$gameApiController, 'ratingsStats']);
};