<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Resource.php';

class AdminController {
    private $db;
    private $user;
    private $resource;

    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['user'],
            $config['password']
        );
        $this->user = new User($this->db);
        $this->resource = new Resource($this->db);
    }

    /**
     * Display admin dashboard
     */
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/?route=login');
            exit;
        }
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Manage users
     */
    public function manageUsers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/?route=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
            if ($this->user->register($username, $email, $password, $role)) {
                $message = "User created successfully";
            } else {
                $error = "Failed to create user";
            }
        }
        $users = $this->user->getAll();
        require __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Manage resources
     */
    public function manageResources() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/?route=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $deleteId = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
            if ($this->resource->delete($deleteId)) {
                $message = "Resource deleted successfully";
            } else {
                $error = "Failed to delete resource";
            }
        }
        $resources = $this->resource->getAll();
        require __DIR__ . '/../views/admin/resources.php';
    }
}
?>