<?php

declare(strict_types=1);

$host = getenv('DB_HOST') ?: 'mysql-guest-book';
$db = getenv('DB_NAME') ?: 'guest-book-db';
$user = getenv('DB_USER') ?: 'vlavlamat';
$pass = getenv('DB_PASS') ?: 'password';
$charset = 'utf8mb4';

//$db_config = [
//    'host' => 'mysql-guest-book',
//    'db' => 'guest-book-db',
//    'user' => 'vlavlamat',
//    'pass' => 'password',
//    'charset' => 'utf8mb4',
//];

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PHP < 8.0 - необходимо включать, когда версия PHP меньше 8
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $db = new PDO($dsn, $user, $pass, $options);
    return $db;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}