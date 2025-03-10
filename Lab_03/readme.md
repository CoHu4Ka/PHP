# Лабораторная работа №3. Массивы и Функции

## Цель работы
Освоить работу с массивами в PHP, применяя различные операции: создание, добавление, удаление, сортировка и поиск. Закрепить навыки работы с функциями, включая передачу аргументов, возвращаемые значения и анонимные функции.

---

## Задание 1. Работа с массивами

### Задание 1.1. Подготовка среды
Убедимся, что установлен PHP 8+. Создадим файл index.php и включите строгую типизацию:

```php
<?php

declare(strict_types=1);
```

### Задание 1.2. Создание массива транзакций
Создадим массив $transactions, содержащий информацию о банковских транзакциях:

```php
$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
];
```

### Задание 1.3. Вывод списка транзакций
Используем цикл foreach, чтобы вывести список транзакций в HTML-таблице:

```php
echo "<table border='1'>
<thead>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Merchant</th>
        <th>Days Since Transaction</th>
    </tr>
</thead>
<tbody>";

foreach ($transactions as $transaction) {
    echo "<tr>
        <td>{$transaction['id']}</td>
        <td>{$transaction['date']}</td>
        <td>{$transaction['amount']}</td>
        <td>{$transaction['description']}</td>
        <td>{$transaction['merchant']}</td>
        <td>" . daysSinceTransaction($transaction['date']) . "</td>
    </tr>";
}

echo "</tbody></table>";
```

### Задание 1.4. Реализация функций
    Функция calculateTotalAmount:

```php
/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций.
 * @return float Общая сумма.
 */
function calculateTotalAmount(array $transactions): float {
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}

echo "Total Amount: " . calculateTotalAmount($transactions);
```

    Функция findTransactionByDescription:

```php
/**
 * Ищет транзакцию по части описания.
 *
 * @param string $descriptionPart Часть описания.
 * @param array $transactions Массив транзакций.
 * @return array|null Найденная транзакция или null.
 */
function findTransactionByDescription(string $descriptionPart, array $transactions): ?array {
    foreach ($transactions as $transaction) {
        if (strpos($transaction['description'], $descriptionPart) !== false) {
            return $transaction;
        }
    }
    return null;
}
```

    Функция findTransactionById:

```php
/**
 * Ищет транзакцию по идентификатору.
 *
 * @param int $id Идентификатор транзакции.
 * @param array $transactions Массив транзакций.
 * @return array|null Найденная транзакция или null.
 */
function findTransactionById(int $id, array $transactions): ?array {
    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}

// Реализация с использованием array_filter
function findTransactionByIdFilter(int $id, array $transactions): ?array {
    $result = array_filter($transactions, fn($t) => $t['id'] === $id);
    return $result ? reset($result) : null;
}
```

    Функция daysSinceTransaction:

```php
/**
 * Возвращает количество дней с момента транзакции.
 *
 * @param string $date Дата транзакции.
 * @return int Количество дней.
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime();
    return $currentDate->diff($transactionDate)->days;
}
```

    Функция addTransaction:

```php
/**
 * Добавляет новую транзакцию.
 *
 * @param int $id Идентификатор.
 * @param string $date Дата.
 * @param float $amount Сумма.
 * @param string $description Описание.
 * @param string $merchant Получатель.
 * @return void
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void {
    global $transactions;
    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}
```

### Задание 1.5. Сортировка транзакций
Сортировка по дате:

```php 
usort($transactions, fn($a, $b) => strtotime($a['date']) - strtotime($b['date']));
```

Сортировка по сумме (по убыванию):
```php 
usort($transactions, fn($a, $b) => $b['amount'] <=> $a['amount']);
```

### Задание 2. Работа с файловой системой

```php
<?php
// Включаем строгую типизацию
declare(strict_types=1);

// Указываем путь к директории с изображениями (относительный путь)
$dir = 'images/';

// Сканируем содержимое директории
$files = scandir($dir);

// Проверяем, удалось ли прочитать директорию
if ($files === false) {
    die("Ошибка при чтении директории.");
}

// Функция для проверки, является ли файл изображением
function is_image($file) {
    $image_info = getimagesize($file);
    return $image_info !== false;
}

// Выводим HTML-заголовок и стили для галереи
echo "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Галерея изображений</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 2em;
            color: #555;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .gallery img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<header>
        <h1>About Cats</h1>
        <nav>
            <a href='#news'>News</a>
            <a href='#contacts'>Contacts</a>
        </nav>
    </header>
    <h1>Галерея изображений</h1>
    <div class='gallery'>";

// Перебираем файлы в директории
foreach ($files as $file) {
    // Пропускаем текущий каталог (.) и родительский каталог (..)
    if ($file != "." && $file != "..") {
        // Получаем полный путь к изображению
        $path = $dir . $file;
        // Проверяем, является ли файл изображением
        if (is_image($path)) {
            // Выводим изображение на страницу
            echo "<img src='" . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . "'>";
        }
    }
}

// Закрываем HTML-теги
echo "</div>
</body>
</html>";
?>
```

### Контрольные вопросы

Что такое массивы в PHP?
Массивы в PHP — это структуры данных, которые хранят набор значений (элементов) под одним именем. Они могут быть индексированными, ассоциативными или многомерными.

Каким образом можно создать массив в PHP?
Массив можно создать с помощью конструкции array() или короткого синтаксиса []. Например:

```php 
$array = [1, 2, 3];
$assocArray = ["key" => "value"];
```

Для чего используется цикл foreach?
Цикл foreach используется для итерации по элементам массива. Он автоматически перебирает все элементы массива, не требуя указания индексов.