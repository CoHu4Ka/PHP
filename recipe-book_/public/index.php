<?php
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/db.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

switch ($path) {
    case '':
    case 'index':
        require __DIR__ . '/../src/handlers/index.php';
        break;
    case 'recipe/create':
        require __DIR__ . '/../src/handlers/recipe/create.php';
        break;
    case 'recipe/edit':
        require __DIR__ . '/../src/handlers/recipe/edit.php';
        break;
    case 'recipe/delete':
        require __DIR__ . '/../src/handlers/recipe/delete.php';
        break;
    case preg_match('/recipe\/(\d+)/', $path, $matches):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/../src/handlers/recipe/show.php';
        break;
    default:
        http_response_code(404);
        echo '404 Страница не найдена';
}