<?php
require_once __DIR__ . '/../src/helpers.php';

$recipes = readRecipes(__DIR__ . '/../storage/recipes.txt');
$latestRecipes = array_slice($recipes, -2);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог рецептов</title>
    <style>
        .recipe { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
        .recipe-title { font-size: 1.5em; margin-bottom: 10px; }
        .recipe-meta { color: #666; margin-bottom: 10px; }
        .recipe-tag { display: inline-block; background: #eee; padding: 2px 5px; margin-right: 5px; }
    </style>
</head>
<body>
    <h1>Последние добавленные рецепты</h1>
    
    <nav>
        <a href="/public/index.php">Главная</a> |
        <a href="/public/recipe/create.php">Добавить рецепт</a> |
        <a href="/public/recipe/index.php">Все рецепты</a>
    </nav>
    
    <?php if (empty($latestRecipes)): ?>
        <p>Пока нет ни одного рецепта. <a href="/public/recipe/create.php">Добавьте первый!</a></p>
    <?php else: ?>
        <?php foreach ($latestRecipes as $recipe): ?>
            <div class="recipe">
                <h2 class="recipe-title"><?= e($recipe['title']) ?></h2>
                <div class="recipe-meta">
                    Категория: <?= e($recipe['category']) ?> | 
                    Дата добавления: <?= e($recipe['created_at']) ?>
                </div>
                <div class="recipe-tags">
                    <?php foreach ($recipe['tags'] as $tag): ?>
                        <span class="recipe-tag"><?= e($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <p><?= nl2br(e($recipe['description'])) ?></p>
                <a href="/public/recipe/index.php?id=<?= e($recipe['id']) ?>">Подробнее</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>