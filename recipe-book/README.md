# Лабораторная работа: Архитектура приложения и работа с базой данных

## Цель работы
1. Освоить архитектуру с единой точкой входа
2. Подключить систему шаблонов для визуализации страниц
3. Перейти от хранения данных в файлах к использованию базы данных MySQL

## Выполнение работы

### 1. Подготовка базы данных
```sql
-- Создание таблицы категорий
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы рецептов
CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category INT NOT NULL,
  ingredients TEXT,
  description TEXT,
  tags TEXT,
  steps TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category) REFERENCES categories(id) ON DELETE CASCADE
);
```
### 2. Реализация единой точки входа (public/index.php)
```php
<?php
require_once __DIR__.'/../src/helpers.php';
require_once __DIR__.'/../src/db.php';

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'home':
        require __DIR__.'/../templates/index.php';
        break;
    case 'recipe/create':
        require __DIR__.'/../src/handlers/recipe/create.php';
        break;
    // Другие маршруты...
    default:
        http_response_code(404);
        require __DIR__.'/../templates/404.php';
}
```
### 4. Подключение к базе данных (src/db.php)
```php
<?php
function getDBConnection() {
    $config = require __DIR__.'/../config/db.php';
    
    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
            $config['username'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
```
### 5. Пример CRUD-операции (создание рецепта)
```php
// src/handlers/recipe/create.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $category = (int)$_POST['category'];
    $ingredients = htmlspecialchars($_POST['ingredients']);
    
    $db = getDBConnection();
    $stmt = $db->prepare(
        "INSERT INTO recipes (title, category, ingredients) 
         VALUES (:title, :category, :ingredients)"
    );
    
    $stmt->execute([
        ':title' => $title,
        ':category' => $category,
        ':ingredients' => $ingredients
    ]);
    
    header('Location: /?route=home');
    exit;
}
```
### Контрольные вопросы

1. Преимущества единой точки входа:

    Централизованная обработка запросов
    Единое место для инициализации приложения
    Упрощение контроля доступа и безопасности
    Удобство маршрутизации

2. Преимущества шаблонов:

    Разделение логики и представления
    Повторное использование кода (header/footer)
    Упрощение поддержки и изменений
    Возможность кэширования шаблонов

3. Преимущества БД перед файлами:

    Более быстрый поиск и сортировка
    Транзакции и целостность данных
    Масштабируемость
    Безопасность (разграничение прав доступа)
    Резервное копирование и восстановление

4. SQL-инъекции:

    Пример уязвимого кода:
        $query = "SELECT * FROM users WHERE login = '$_POST[login]' AND password = '$_POST[password]'"; 
        Атака: ввести ' OR '1'='1 в поле login

    Защита:
        Использование подготовленных выражений (PDO)
        Экранирование специальных символов
        Валидация входных данных

### Выводы

В ходе работы:

1. Реализована архитектура с единой точкой входа
2. Настроена система шаблонов для разделения логики и представления
3. Перенесено хранение данных из файлов в базу данных MySQL
4. Реализованы основные CRUD-операции с защитой от SQL-инъекций
5. Получены практические навыки работы с PDO и подготовленными выражениями
6. Проект демонстрирует правильный подход к построению безопасных веб-приложений с использованием современных практик разработки.