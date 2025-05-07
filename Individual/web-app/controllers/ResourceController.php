<?php
require_once __DIR__ . '/../models/Resource.php';

class ResourceController {
    private $db;
    private $resource;

    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['user'],
            $config['password']
        );
        $this->resource = new Resource($this->db);
    }

    /**
     * Display public resources
     */
    public function index() {
        $resources = $this->resource->getAll();
        require __DIR__ . '/../views/resources/index.php';
    }

    /**
     * Handle resource creation
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/?route=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                'category' => filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING),
                'status' => filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING),
                'priority' => filter_input(INPUT_POST, 'priority', FILTER_VALIDATE_INT)
            ];
            if (empty($data['title']) || empty($data['description']) || !$data['priority']) {
                $error = "All fields are required";
            } elseif ($this->resource->create($data, $_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/?route=home');
                exit;
            } else {
                $error = "Failed to create resource";
            }
        }
        require __DIR__ . '/../views/resources/create.php';
    }

    /**
     * Handle resource search
     */
    public function search() {
        $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
        $resources = $query ? $this->resource->search($query) : [];
        require __DIR__ . '/../views/resources/search.php';
    }
}
?>