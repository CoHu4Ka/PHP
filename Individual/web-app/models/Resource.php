<?php
class Resource {
    private $db;

    /**
     * Resource constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new resource
     * @param array $data
     * @param int $userId
     * @return bool
     */
    public function create($data, $userId) {
        $stmt = $this->db->prepare("INSERT INTO resources (title, description, category, status, priority, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['category'],
            $data['status'],
            $data['priority'],
            $userId
        ]);
    }

    /**
     * Get all resources
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM resources");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search resources by criteria
     * @param string $query
     * @return array
     */
    public function search($query) {
        $stmt = $this->db->prepare("SELECT * FROM resources WHERE title LIKE ? OR description LIKE ?");
        $stmt->execute(["%$query%", "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a resource
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM resources WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>