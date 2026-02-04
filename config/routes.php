<?php

use Core\Request;
use Core\Response;
use Controller\AppController;
use Controller\CustomerController;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
//use Controller\TaskController;
use Core\Router;

return function (
    Router $router,
    AppController $controller,
    CustomerController $customerController,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController
    //TaskController $taskController
)
{
    // ============================================
    // ROUTE PRINCIPALE (redirige selon connexion)
    // ============================================

    $router->get('/', function() use ($controller, $authController) {
        if (isset($_SESSION['user'])) {
            $controller->home();  // ← Affiche home. php si connecté
        } else {
            $authController->login();  // ← Affiche login si non connecté
        }
    });

    // ============================================
    // ROUTES HOME
    // ============================================

    $router->get('/home', [$controller, 'home']);
    $router->get('/pagetest', [$controller, 'pagetest']);
    $router->get('/profile', [$controller, 'profile']);
    $router->get('/settings', [$controller, 'settings']);

    // ============================================
    // ROUTES CUSTOMER
    // ============================================

    $router->get('/customer/listCustomer', [$customerController, 'listClient']); // liste des clients
    $router->get('/customer/infoCustomer', [$customerController, 'getClient']); // lire un client

    // Récupérer un client spécifique (pour pré-remplir le modal)
$router->get('/customer/get/(\d+)', function($matches) use ($customerController) {
    $customerController->getClient((int)$matches[1]);
});

// Mettre à jour un client
$router->post('/customer/update/(\d+)', function($matches) use ($customerController) {
    $customerController->updateClient((int)$matches[1]);
});

// Créer un client
$router->post('/customer/createCustomer', [$customerController, 'createCustomer']);

// Supprimer un client
$router->post('/customer/delete/(\d+)', function($matches) use ($customerController) {
    $customerController->deleteClient((int)$matches[1]);
});

    // ============================================
    // ROUTES AUTHENTIFICATION
    // ============================================

    $router->get('/users', [$userController, 'index']); // liste
    $router->get('/users/create', [$userController, 'create']); // formulaire
    $router->post('/users/store', [$userController, 'store']); // envoi création
    $router->get('/users/edit/(\d+)', function($matches) use ($userController) {
        $userController->edit((int)$matches[1]);
    });
    $router->post('/users/update/(\d+)', function($matches) use ($userController) {
        $userController->update((int)$matches[1]);
    });

    $router->post('/users/delete/(\d+)', function($matches) use ($userController) {
        $userController->delete((int)$matches[1]);
    });

    $router->get('/users/(\d+)/change-password', function($matches) use ($userController) {
        $userController->changePassword((int)$matches[1]);
    });

    $router->post('/users/(\d+)/update-password', function($matches) use ($userController) {
        $userController->updatePassword((int)$matches[1]);
    });

    // ============================================
    // routes pour l'authentification/connexion
    // ============================================

    $router->get('/login', [$authController, 'login']);
    $router->post('/login', [$authController, 'authenticate']);
    $router->get('/logout', [$authController, 'logout']);

    // ============================================
    // routes pour mot de passe oublié
    // ============================================

    $router->get('/forgot-password', [$passwordController, 'forgot']);
    $router->post('/forgot-password', [$passwordController, 'reset']);

    // ============================================
    // ROUTES TASKS
    // ============================================

//    $router->get('/tasks', [$taskController, 'tasks']);
//
//    $router->get('/tasks/create', [$taskController, 'create']);
//    $router->post('/tasks/create', [$taskController, 'create']);
//
//    $router->get('/tasks/edit/(\d+)', function($matches) use ($taskController) {
//        $taskController->edit((int)$matches[1]);
//    });
//
//    $router->post('/tasks/edit/(\d+)', function($matches) use ($taskController) {
//        $taskController->edit((int)$matches[1]);
//    });
//
//    $router->post('/tasks/delete/(\d+)', function($matches) use ($taskController) {
//        $taskController->delete((int)$matches[1]);
//    });

    // ============================================
    // ROUTES API
    // ============================================

    $router->post('/api/test-login', function() {
        $_SESSION['user'] = [
            'id_user' => 1,
            'id_user_role' => 1,
            'role' => 'admin'
        ];
        echo json_encode(['ok' => true, 'role' => 'admin']);
    });

    $router->delete('/api/test', function () {
        echo json_encode(['ok' => true]);
    });

    $router->get('/api/users', function() use ($userApiController) {
        $userApiController->index();
    });

    $router->get('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->show((int)$matches[1]);
    });

    $router->post('/api/users', function() use ($userApiController) {
        $userApiController->store();
    });

    $router->put('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->update((int)$matches[1]);
    });

    $router->patch('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->update((int)$matches[1]);
    });

    $router->delete('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->destroy((int)$matches[1]);
    });
};