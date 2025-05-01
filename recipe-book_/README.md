# Лабораторная работа №5: Работа с базой данных

## Цель работы
Освоить архитектуру с единой точкой входа, подключение шаблонов для визуализации страниц и переход от хранения данных в файлах к использованию базы данных MySQL.

## Условия
Продолжена разработка проекта "Книга рецептов" с учетом следующих требований:
1. Реализация архитектуры с единой точкой входа (`index.php`) для обработки всех HTTP-запросов.
2. Настройка системы шаблонов с использованием `layout.php` и отдельных представлений.
3. Перенос логики работы с рецептами из файловой системы в базу данных MySQL.
4. Реализация CRUD-операций с валидацией данных и защитой от SQL-инъекций.
5. Дополнительно: реализация пагинации для списка рецептов.

## Выполненные задания

### Задание 1: Подготовка среды
- Установлен и настроен сервер MySQL через XAMPP.
- Создана база данных `recipe_book`:
  ```sql
  CREATE DATABASE recipe_book;
  USE recipe_book;
  ```
- Созданы таблицы `categories` и `recipes`:
  ```sql
  CREATE TABLE categories (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL UNIQUE,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

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
- Дополнительно: использована библиотека `phinx` для миграций. Пример миграции:
  ```php
  // migrations/20250502120000_create_tables.php
  use Phinx\Migration\AbstractMigration;

  class CreateTables extends AbstractMigration {
      public function change() {
          $categories = $this->table('categories');
          $categories->addColumn('name', 'string', ['limit' => 100, 'null' => false])
                     ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                     ->addIndex('name', ['unique' => true])
                     ->create();

          $recipes = $this->table('recipes');
          $recipes->addColumn('title', 'string', ['limit' => 255, 'null' => false])
                  ->addColumn('category', 'integer', ['null' => false])
                  ->addColumn('ingredients', 'text', ['null' => true])
                  ->addColumn('description', 'text', ['null' => true])
                  ->addColumn('tags', 'text', ['null' => true])
                  ->addColumn('steps', 'text', ['null' => true])
                  ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->addForeignKey('category', 'categories', 'id', ['delete' => 'CASCADE'])
                  ->create();
      }
  }
  ```
- Миграции выполнены командой: `vendor/bin/phinx migrate`.

### Задание 2: Архитектура и шаблонизация
- Создана структура проекта:
  ```
  recipe-book/
  ├── public/
  │   └── index.php
  ├── src/
  │   ├── handlers/
  │   │   ├── recipe/
  │   │   │   ├── create.php
  │   │   │   ├── edit.php
  │   │   │   └── delete.php
  │   ├── db.php
  │   ├── helpers.php
  ├── config/
  │   └── db.php
  ├── templates/
  │   ├── layout.php
  │   ├── index.php
  │   └── recipe/
  │       ├── create.php
  │       ├── edit.php
  │       └── show.php
  ├── .env
  └── README.md
  ```
- Реализована единая точка входа в `public/index.php` с базовой маршрутизацией:
  ```php
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $path = trim($path, '/');

  switch ($path) {
      case '':
      case 'index':
          require __DIR__ . '/../src/handlers/index.php';
          break;
      case 'recipe/create':
          require __DIR__ . '/../src/handlers/recipe/create.php';
          break;
      case 'recipe/edit':
          require __DIR__ . '/../src/handlers/recipe/edit.php';
          break;
      case 'recipe/delete':
          require __DIR__ . '/../src/handlers/recipe/delete.php';
          break;
      case preg_match('/recipe\/(\d+)/', $path, $matches):
          $_GET['id'] = $matches[1];
          require __DIR__ . '/../src/handlers/recipe/show.php';
          break;
      default:
          http_response_code(404);
          echo '404 Страница не найдена';
  }
  ```
- Настроен базовый шаблон `templates/layout.php`:
  ```php
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
  ```
- Созданы шаблоны для страниц: `index.php`, `recipe/create.php`, `recipe/edit.php`, `recipe/show.php`.

### Задание 3: Подключение к базе данных
- Параметры подключения хранятся в `config/db.php`:
  ```php
  return [
      'host' => 'localhost',
      'dbname' => 'recipe_book',
      'user' => 'root',
      'password' => '',
      'charset' => 'utf8mb4',
  ];
  ```
- Дополнительно: использован файл `.env` с библиотекой `vlucas/phpdotenv`:
  ```
  DB_HOST=localhost
  DB_NAME=recipe_book
  DB_USER=root
  DB_PASS=
  DB_CHARSET=utf8mb4
  ```
- Реализован класс `Database` в `src/db.php` для работы с базой данных:
  ```php
  class Database {
      private PDO $pdo;

      public function __construct() {
          $this->pdo = getDbConnection();
      }

      public function query(string $sql, array $params = []): array {
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute($params);
          return $stmt->fetchAll();
      }

      public function insert(string $sql, array $params = []): int {
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute($params);
          return (int)$this->pdo->lastInsertId();
      }

      public function execute(string $sql, array $params = []): int {
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute($params);
          return $stmt->rowCount();
      }

      public function find(string $table, int $id): ?array {
          $sql = "SELECT * FROM $table WHERE id = ?";
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute([$id]);
          $result = $stmt->fetch();
          return $result ?: null;
      }
  }
  ```

### Задание 4: Реализация CRUD-функциональности
- Реализованы обработчики:
  - **Создание рецепта** (`handlers/recipe/create.php`):
    ```php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'category' => (int)($_POST['category'] ?? 0),
            'ingredients' => trim($_POST['ingredients'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'tags' => trim($_POST['tags'] ?? ''),
            'steps' => trim($_POST['steps'] ?? ''),
        ];
        $errors = [];
        if (empty($data['title'])) $errors[] = 'Название обязательно.';
        if ($data['category'] <= 0) $errors[] = 'Категория обязательна.';
        if (empty($errors)) {
            $sql = 'INSERT INTO recipes (title, category, ingredients, description, tags, steps) VALUES (?, ?, ?, ?, ?, ?)';
            $db->insert($sql, array_values($data));
            header('Location: /');
            exit;
        }
    }
    ```
  - **Редактирование рецепта** (`handlers/recipe/edit.php`).
  - **Удаление рецепта** (`handlers/recipe/delete.php`).
  - **Просмотр рецепта** (`handlers/recipe/show.php`).
- Все данные валидируются на стороне сервера.

### Задание 5: Защита от SQL-инъекций
- Использованы подготовленные запросы PDO для всех операций с базой данных.
- Пример уязвимого кода:
  ```php
  $title = $_POST['title'];
  $sql = "SELECT * FROM recipes WHERE title = '$title'";
  ```
  Ввод `title = ' OR 1=1; --` приводит к выполнению:
  ```sql
  SELECT * FROM recipes WHERE title = '' OR 1=1; --'
  ```
- Исправление с использованием подготовленных запросов:
  ```php
  $sql = 'SELECT * FROM recipes WHERE title = ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$_POST['title']]);
  ```
- Все входные данные валидируются и экранируются (`htmlspecialchars` в шаблонах).

### Задание 6: Пагинация (дополнительное)
- Реализована пагинация с использованием `LIMIT` и `OFFSET`:
  ```php
  $perPage = 5;
  $page = max(1, (int)($_GET['page'] ?? 1));
  $offset = ($page - 1) * $perPage;
  $total = $db->query('SELECT COUNT(*) as count FROM recipes')[0]['count'];
  $recipes = $db->query(
      'SELECT r.*, c.name as category_name FROM recipes r JOIN categories c ON r.category = c.id LIMIT ? OFFSET ?',
      [$perPage, $offset]
  );
  $totalPages = ceil($total / $perPage);
  ```
- В шаблоне `templates/index.php` добавлены ссылки для навигации по страницам.

## Документация
- Код задокументирован с использованием PHPDoc. Пример:
  ```php
  /**
   * Выполняет SELECT-запрос и возвращает результаты.
   *
   * @param string $sql SQL-запрос.
   * @param array $params Параметры для подготовленного запроса.
   * @return array Результаты запроса.
   */
  public function query(string $sql, array $params = []): array {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll();
  }
  ```

## Контрольные вопросы
1. **Какие преимущества даёт использование единой точки входа в веб-приложении?**
   - Централизованное управление: все запросы проходят через `index.php`, что упрощает маршрутизацию, аутентификацию и логирование.
   - Удобство обслуживания: глобальные настройки изменяются в одном месте.
   - Безопасность: легче внедрять проверки (например, защиту от CSRF) для всех маршрутов.

2. **Какие преимущества даёт использование шаблонов?**
   - Повторное использование: общие шаблоны сокращают дублирование кода.
   - Удобство поддержки: изменения структуры сайта выполняются в одном файле.
   - Разделение ответственности: представление отделено от логики, что улучшает читаемость.

3. **Какие преимущества даёт хранение данных в базе по сравнению с хранением в файлах?**
   - Масштабируемость: базы данных эффективно обрабатывают большие объемы данных и одновременные запросы.
   - Гибкость запросов: SQL поддерживает сложные запросы (например, фильтрацию по категориям).
   - Целостность данных: внешние ключи обеспечивают согласованность.
   - Безопасность: базы данных поддерживают аутентификацию и шифрование.

4. **Что такое SQL-инъекция? Придумайте пример SQL-инъекции и объясните, как её предотвратить.**
   - **Определение**: Уязвимость, при которой злоумышленник внедряет вредоносный SQL-код через пользовательский ввод.
   - **Пример**: Уязвимый запрос:
     ```php
     $title = $_POST['title'];
     $sql = "SELECT * FROM recipes WHERE title = '$title'";
     ```
     Ввод: `title = ' OR 1=1; --`. Результат:
     ```sql
     SELECT * FROM recipes WHERE title = '' OR 1=1; --'
     ```
     Это возвращает все записи.
   - **Предотвращение**:
     - Использовать подготовленные запросы:
       ```php
       $sql = 'SELECT * FROM recipes WHERE title = ?';
       $stmt = $pdo->prepare($sql);
       $stmt->execute([$_POST['title']]);
       ```
     - Валидировать и очищать входные данные.

## Дополнительные замечания
- **Тестирование**: Проверены все CRUD-операции и пагинация.
- **Безопасность**: Добавлена защита от XSS (`htmlspecialchars`) и SQL-инъекций.
- **Рекомендации**: Добавить CSRF-токены для POST-форм и обработку ошибок подключения к базе данных.