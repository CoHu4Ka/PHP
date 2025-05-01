<?php
ob_start();
?>
<h2>Редактировать рецепт</h2>
<form method="POST" action="/recipe/edit">
    <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">
    <label>Название: <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required></label><br>
    <label>Категория:
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $recipe['category'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Ингредиенты: <textarea name="ingredients"><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea></label><br>
    <label>Описание: <textarea name="description"><?php echo htmlspecialchars($recipe['description']); ?></textarea></label><br>
    <label>Теги: <input type="text" name="tags" value="<?php echo htmlspecialchars($recipe['tags']); ?>"></label><br>
    <label>Шаги: <textarea name="steps"><?php echo htmlspecialchars($recipe['steps']); ?></textarea></label><br>
    <button type="submit">Обновить</button>
</form>
<?php
$content = ob_get_clean();
$title = 'Редактировать рецепт';
require __DIR__ . '/../layout.php';