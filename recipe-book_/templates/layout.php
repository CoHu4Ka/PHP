<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title ?? 'Книга рецептов'); ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Книга рецептов</h1>
        <nav>
            <a href="/">Главная</a>
            <a href="/recipe/create">Добавить рецепт</a>
        </nav>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
        <p>© 2025 Книга рецептов</p>
    </footer>
</body>
</html>