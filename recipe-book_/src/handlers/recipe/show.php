<?php
$db = new Database();
$id = (int)($_GET['id'] ?? 0);
$recipe = $db->find('recipes', $id);

if (!$recipe) {
    http_response_code(404);
    echo 'Рецепт не найден';
    exit;
}

$category = $db->find('categories', $recipe['category']);
require __DIR__ . '/../../../templates/recipe/show.php';