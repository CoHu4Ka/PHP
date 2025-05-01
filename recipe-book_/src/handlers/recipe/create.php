<?php
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'category' => (int)($_POST['category'] ?? 0),
        'ingredients' => trim($_POST['ingredients'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'tags' => trim($_POST['tags'] ?? ''),
        'steps' => trim($_POST['steps'] ?? ''),
    ];

    // Валидация
    $errors = [];
    if (empty($data['title'])) $errors[] = 'Название обязательно.';
    if ($data['category'] <= 0) $errors[] = 'Категория обязательна.';
    if (empty($errors)) {
        $sql = 'INSERT INTO recipes (title, category, ingredients, description, tags, steps) VALUES (?, ?, ?, ?, ?, ?)';
        $db->insert($sql, array_values($data));
        header('Location: /');
        exit;
    }
}

$categories = $db->query('SELECT * FROM categories');
require __DIR__ . '/../../../templates/recipe/create.php';