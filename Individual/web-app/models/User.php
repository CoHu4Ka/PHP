<?php
class User {
    private $db;

    /**
     * User constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Register a new user
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $role
     * @return bool
     */
    public function register($username, $email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword, $role]);
    }

    /**
     * Find user by email
     * @param string $email
     * @return array|null
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verify user credentials
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public function verify($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    /**
     * Get all users
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>