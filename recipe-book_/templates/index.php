<?php
ob_start();
?>
<h2>Все рецепты</h2>
<?php if (empty($recipes)): ?>
    <p>Рецепты не найдены.</p>
<?php else: ?>
    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li>
                <a href="/recipe/<?php echo $recipe['id']; ?>">
                    <?php echo htmlspecialchars($recipe['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">Предыдущая</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i === $page ? 'style="font-weight:bold;"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Следующая</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = 'Все рецепты';
require __DIR__ . '/layout.php';