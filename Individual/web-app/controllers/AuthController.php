<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['user'],
            $config['password']
        );
        $this->user = new User($this->db);
    }

    /**
     * Handle login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $user = $this->user->verify($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: ' . BASE_URL . '/?route=home');
                exit;
            } else {
                $error = "Invalid credentials";
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Handle registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            if (strlen($password) < 6) {
                $error = "Password must be at least 6 characters";
            } elseif ($this->user->register($username, $email, $password)) {
                header('Location: ' . BASE_URL . '/?route=login');
                exit;
            } else {
                $error = "Registration failed";
            }
        }
        require __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Handle logout
     */
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/?route=home');
        exit;
    }

    /**
     * Handle password reset (simulated)
     */
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $user = $this->user->findByEmail($email);
            if ($user) {
                // Simulate sending email by logging to a file
                file_put_contents('reset_log.txt', "Reset link for $email\n", FILE_APPEND);
                $message = "Password reset link sent (check reset_log.txt)";
            } else {
                $error = "Email not found";
            }
        }
        require __DIR__ . '/../views/auth/reset.php';
    }
}
?>