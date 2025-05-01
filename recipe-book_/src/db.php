<?php
/**
 * Устанавливает соединение с базой данных через PDO.
 *
 * @return PDO Экземпляр PDO.
 * @throws PDOException Если подключение не удалось.
 */
function getDbConnection(): PDO {
    $config = require __DIR__ . '/../config/db.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}