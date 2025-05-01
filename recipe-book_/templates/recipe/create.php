<?php
ob_start();
?>
<h2>Добавить новый рецепт</h2>
<form method="POST" action="/recipe/create">
    <label>Название: <input type="text" name="title" required></label><br>
    <label>Категория:
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Ингредиенты: <textarea name="ingredients"></textarea></label><br>
    <label>Описание: <textarea name="description"></textarea></label><br>
    <label>Теги: <input type="text" name="tags"></label><br>
    <label>Шаги: <textarea name="steps"></textarea></label><br>
    <button type="submit">Сохранить</button>
</form>
<?php
$content = ob_get_clean();
$title = 'Добавить рецепт';
require __DIR__ . '/../layout.php';