<?php

declare(strict_types=1);

echo "<h1>Guest Book Project</h1>";
echo "<p>PHP is working!</p>";

//$host = getenv('DB_HOST') ?: 'mysql-guest-book';
//$db   = getenv('DB_NAME') ?: 'guest-book-db';
//$user = getenv('DB_USER') ?: 'vlavlamat';
//$pass = getenv('DB_PASS') ?: 'password';
//$charset = 'utf8mb4';
//
//$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//$options = [
//    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//    PDO::ATTR_EMULATE_PREPARES   => false,
//];
//
//try {
//     $pdo = new PDO($dsn, $user, $pass, $options);
//     echo "<p style='color: green;'>Successfully connected to the database!</p>";
//} catch (PDOException $e) {
//     echo "<p style='color: red;'>Database connection failed: " . $e->getMessage() . "</p>";
//}

echo phpinfo();
