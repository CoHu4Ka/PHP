<?php
define('BASE_URL', '/web-app/public');
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ResourceController.php';
require_once __DIR__ . '/../controllers/AdminController.php';

$route = $_GET['route'] ?? 'home';
$authController = new AuthController();
$resourceController = new ResourceController();
$adminController = new AdminController();

switch ($route) {
    case 'home':
        $resourceController->index();
        break;
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'reset':
        $authController->resetPassword();
        break;
    case 'create':
        $resourceController->create();
        break;
    case 'search':
        $resourceController->search();
        break;
    case 'admin/dashboard':
        $adminController->dashboard();
        break;
    case 'admin/users':
        $adminController->manageUsers();
        break;
    case 'admin/resources':
        $adminController->manageResources();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
}
?>