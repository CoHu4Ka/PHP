<?php
ob_start();
?>
<h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
<p><strong>Категория:</strong> <?php echo htmlspecialchars($category['name']); ?></p>
<p><strong>Ингредиенты:</strong> <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
<p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
<p><strong>Теги:</strong> <?php echo htmlspecialchars($recipe['tags']); ?></p>
<p><strong>Шаги:</strong> <?php echo nl2br(htmlspecialchars($recipe['steps'])); ?></p>
<a href="/recipe/edit?id=<?php echo $recipe['id']; ?>">Редактировать</a>
<form method="POST" action="/recipe/delete" style="display:inline;">
    <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">
    <button type="submit" onclick="return confirm('Вы уверены?');">Удалить</button>
</form>
<?php
$content = ob_get_clean();
$title = $recipe['title'];
require __DIR__ . '/../layout.php';