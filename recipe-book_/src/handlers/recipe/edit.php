<?php
$db = new Database();
$id = (int)($_GET['id'] ?? 0);
$recipe = $db->find('recipes', $id);

if (!$recipe) {
    http_response_code(404);
    echo 'Рецепт не найден';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
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
        $sql = 'UPDATE recipes SET title = ?, category = ?, ingredients = ?, description = ?, tags = ?, steps = ? WHERE id = ?';
        $db->execute($sql, array_values($data));
        header('Location: /recipe/' . $id);
        exit;
    }
}

$categories = $db->query('SELECT * FROM categories');
require __DIR__ . '/../../../templates/recipe/edit.php';