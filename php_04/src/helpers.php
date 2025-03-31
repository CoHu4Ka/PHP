<?php
/**
 * Читает рецепты из файла
 * 
 * @param string $filePath Путь к файлу с рецептами
 * @param int|null $limit Ограничение количества возвращаемых рецептов
 * @param int $page Номер страницы для пагинации
 * @return array Массив рецептов
 */
function readRecipes($filePath, $limit = null, $page = 1) {
    if (!file_exists($filePath)) {
        return [];
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $recipes = array_map(function($line) {
        return json_decode($line, true);
    }, $lines);
    
    // Применяем пагинацию, если указан limit
    if ($limit !== null) {
        $offset = ($page - 1) * $limit;
        $recipes = array_slice($recipes, $offset, $limit);
    }
    
    return $recipes;
}

/**
 * Получает общее количество рецептов
 * 
 * @param string $filePath Путь к файлу с рецептами
 * @return int Количество рецептов
 */
function getTotalRecipes($filePath) {
    if (!file_exists($filePath)) {
        return 0;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return count($lines);
}

/**
 * Экранирует HTML-спецсимволы в строке
 * 
 * @param string $string Исходная строка
 * @return string Экранированная строка
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}