<?php
// Включаем строгую типизацию
declare(strict_types=1);

// Указываем путь к директории с изображениями (относительный путь)
$dir = 'images/';

// Сканируем содержимое директории
$files = scandir($dir);

// Проверяем, удалось ли прочитать директорию
if ($files === false) {
    die("Ошибка при чтении директории.");
}

// Функция для проверки, является ли файл изображением
function is_image($file) {
    $image_info = getimagesize($file);
    return $image_info !== false;
}

// Выводим HTML-заголовок и стили для галереи
echo "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Галерея изображений</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 2em;
            color: #555;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .gallery img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<header>
        <h1>About Cars</h1>
        <nav>
            <a href='#news'>News</a>
            <a href='#contacts'>Contacts</a>
        </nav>
    </header>
    <h1>Галерея изображений</h1>
    <div class='gallery'>";

// Перебираем файлы в директории
foreach ($files as $file) {
    // Пропускаем текущий каталог (.) и родительский каталог (..)
    if ($file != "." && $file != "..") {
        // Получаем полный путь к изображению
        $path = $dir . $file;
        // Проверяем, является ли файл изображением
        if (is_image($path)) {
            // Выводим изображение на страницу
            echo "<img src='" . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . "'>";
        }
    }
}

// Закрываем HTML-теги
echo "</div>
</body>
</html>";
?>
