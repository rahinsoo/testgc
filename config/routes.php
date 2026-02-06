<?php

use Core\Request;
use Core\Response;
use Controller\AppController;
use Controller\CustomerController;
use Controller\API\SireneApiController;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
//use Controller\TaskController;
use Controller\ActiviteController;
use Controller\AffectationController;

use Core\Router;

return function ( // fonction injectée par l'appli
    Router $router,
    AppController $controller,
    CustomerController $customerController,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController,
    SireneApiController $sireneApiController,
    //TaskController $taskController,
    ActiviteController $activiteController,
    AffectationController $affectationController
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
    $router->get('/customer/listCustomer', [$controller, 'customer']);
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
    $router->post('/users/update/(\d+)', function($matches) use ($userController) { // envoi des données de modification
        $userController->update((int)$matches[1]);
    });

    $router->post('/users/delete/(\d+)', function($matches) use ($userController) { // envoi des données de suppression
        $userController->delete((int)$matches[1]);
    });

    /// routes pour changer le password  ///
    $router->get('/users/(\d+)/change-password', function($matches) use ($userController) {
        $userController->changePassword((int)$matches[1]);
    });

    $router->post('/users/(\d+)/update-password', function($matches) use ($userController) {
        $userController->updatePassword((int)$matches[1]);
    });

    // ============================================
    // routes pour l'authentification/connexion
    // ============================================

    $router->get('/', [$authController, 'login']);
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

    $router->post('/api/test-login', function() { // route pour mimer la connexion sur postman
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

    /// routes UserAPi ///
    $router->get('/api/users', function() use ($userApiController) { // affichage de tous les utilisateurs
        $userApiController->index();
    });

    $router->get('/api/users/(\d+)', function($matches) use ($userApiController) { // affichage d'un seul utilisateur
        $userApiController->show((int)$matches[1]);
    });

    $router->post('/api/users', function() use ($userApiController) { // envoi données création
        $userApiController->store();
    });

    $router->put('/api/users/(\d+)', function($matches) use ($userApiController) { // modification complète, je dois mettre des values pour tous les paramètres
        $userApiController->update((int)$matches[1]);
    });

    $router->patch('/api/users/(\d+)', function($matches) use ($userApiController) { // modification partielle
        $userApiController->update((int)$matches[1]);
    });

    $router->delete('/api/users/(\d+)', function ($matches) use ($userApiController) { // suppression
        $userApiController->destroy((int)$matches[1]);
    });
    $router->get('/api/sirene/siret/(\d+)', function($matches) use ($sireneApiController) {
        $sireneApiController->rechercherSiret($matches[1]);
    });

    /// routes pour les activités ///
    $router->get('/activites', [$activiteController, 'index']); // affichage liste des activités
    $router->get('/activites/create', [$activiteController, 'create']); // formulaire création activité
    $router->post('/activites/store', [$activiteController, 'store']); // envoi création en BDD
    $router->get('/activites/edit/(\d+)', function($matches) use ($activiteController) { // formulaire pré-rempli modif activité
        $activiteController->edit((int)$matches[1]);
    });
    $router->post('/activites/update/(\d+)', function($matches) use ($activiteController) { // envoi modif activité en BDD
        $activiteController->update((int)$matches[1]);
    });
    $router->post('/activites/delete/(\d+)', function($matches) use ($activiteController) { // suppression
        $activiteController->delete((int)$matches[1]);
    });

    /// routes pour les affectations ///
    $router->get('/affectations', [$affectationController, 'index']); // liste des affectations
    $router->get('/affectations/create', [$affectationController, 'create']); // formulaire création affectation
    $router->post('/affectations/store', [$affectationController, 'store']); // envoi création de l'affectation en BDD
    ///$router->get('/affectations/edit/(\d+)', function($matches) use ($affectationController) { // formulaire modif du TJM
        ///$affectationController->edit((int)$matches[1]);
    ///});
    $router->post('/affectations/updateTjm/(\d+)/(\d+)', function($matches) use ($affectationController) { // envoi modif TJM en BDD
        $affectationController->updateTjm((int)$matches[1], (int)$matches[2]);
    });
    $router->post('/affectations/delete/(\d+)/(\d+)', function($matches) use ($affectationController) { // suppression
        $affectationController->delete((int)$matches[1], (int)$matches[2]);
    });
};