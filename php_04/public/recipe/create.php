<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить новый рецепт</title>
    <style>
        .error { color: red; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Добавить новый рецепт</h1>
    
    <form action="/src/handlers/recipe_create.php" method="post">
        <div class="form-group">
            <label for="title">Название рецепта:</label>
            <input type="text" id="title" name="title" required>
            <?php if (isset($errors['title'])): ?>
                <span class="error"><?= htmlspecialchars($errors['title']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="category">Категория:</label>
            <select id="category" name="category" required>
                <option value="">Выберите категорию</option>
                <option value="Завтраки">Завтраки</option>
                <option value="Обеды">Обеды</option>
                <option value="Ужины">Ужины</option>
                <option value="Десерты">Десерты</option>
                <option value="Напитки">Напитки</option>
            </select>
            <?php if (isset($errors['category'])): ?>
                <span class="error"><?= htmlspecialchars($errors['category']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="ingredients">Ингредиенты (каждый с новой строки):</label>
            <textarea id="ingredients" name="ingredients" rows="5" required></textarea>
            <?php if (isset($errors['ingredients'])): ?>
                <span class="error"><?= htmlspecialchars($errors['ingredients']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="description">Описание рецепта:</label>
            <textarea id="description" name="description" rows="5" required></textarea>
            <?php if (isset($errors['description'])): ?>
                <span class="error"><?= htmlspecialchars($errors['description']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="tags">Тэги (удерживайте Ctrl для выбора нескольких):</label>
            <select id="tags" name="tags[]" multiple>
                <option value="Быстро">Быстро</option>
                <option value="Просто">Просто</option>
                <option value="Праздничное">Праздничное</option>
                <option value="Здоровое">Здоровое</option>
                <option value="Вегетарианское">Вегетарианское</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Шаги приготовления:</label>
            <div id="steps-container">
                <!-- JavaScript будет добавлять поля для шагов здесь -->
            </div>
            <button type="button" id="add-step">Добавить шаг</button>
        </div>
        
        <button type="submit">Отправить рецепт</button>
    </form>

    <script>
        document.getElementById('add-step').addEventListener('click', function() {
            const container = document.getElementById('steps-container');
            const stepCount = container.children.length + 1;
            
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step';
            
            const label = document.createElement('label');
            label.textContent = `Шаг ${stepCount}:`;
            
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'steps[]';
            input.required = true;
            
            stepDiv.appendChild(label);
            stepDiv.appendChild(input);
            container.appendChild(stepDiv);
        });
    </script>
</body>
</html>