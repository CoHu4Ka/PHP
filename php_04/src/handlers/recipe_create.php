<?php
require_once __DIR__ . '/../../src/helpers.php';

// Инициализация массива для ошибок
$errors = [];

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/recipe/create.php');
    exit;
}

// Фильтрация и валидация данных
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$ingredients = trim($_POST['ingredients'] ?? '');
$description = trim($_POST['description'] ?? '');
$tags = $_POST['tags'] ?? [];
$steps = $_POST['steps'] ?? [];

// Валидация названия
if (empty($title)) {
    $errors['title'] = 'Название рецепта обязательно для заполнения';
} elseif (strlen($title) > 100) {
    $errors['title'] = 'Название не должно превышать 100 символов';
}

// Валидация категории
if (empty($category)) {
    $errors['category'] = 'Выберите категорию';
}

// Валидация ингредиентов
if (empty($ingredients)) {
    $errors['ingredients'] = 'Укажите ингредиенты';
}

// Валидация описания
if (empty($description)) {
    $errors['description'] = 'Описание рецепта обязательно';
}

// Валидация шагов
if (empty($steps)) {
    $errors['steps'] = 'Добавьте хотя бы один шаг приготовления';
} else {
    foreach ($steps as $step) {
        if (empty(trim($step))) {
            $errors['steps'] = 'Все шаги должны быть заполнены';
            break;
        }
    }
}

// Если есть ошибки, возвращаем пользователя на форму
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = [
        'title' => $title,
        'category' => $category,
        'ingredients' => $ingredients,
        'description' => $description,
        'tags' => $tags,
        'steps' => $steps
    ];
    header('Location: /public/recipe/create.php');
    exit;
}

// Подготовка данных для сохранения
$recipeData = [
    'id' => uniqid(),
    'title' => $title,
    'category' => $category,
    'ingredients' => array_filter(array_map('trim', explode("\n", $ingredients))),
    'description' => $description,
    'tags' => array_map('trim', $tags),
    'steps' => array_map('trim', $steps),
    'created_at' => date('Y-m-d H:i:s')
];

// Сохранение в файл
$storagePath = __DIR__ . '/../../storage/recipes.txt';
file_put_contents($storagePath, json_encode($recipeData) . PHP_EOL, FILE_APPEND);

// Перенаправление на главную
header('Location: /public/index.php');
exit;