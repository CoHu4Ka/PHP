<?php
$db = new Database();
$perPage = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$total = $db->query('SELECT COUNT(*) as count FROM recipes')[0]['count'];
$recipes = $db->query(
    'SELECT r.*, c.name as category_name FROM recipes r JOIN categories c ON r.category = c.id LIMIT ? OFFSET ?',
    [$perPage, $offset]
);

$totalPages = ceil($total / $perPage);
require __DIR__ . '/../../templates/index.php';