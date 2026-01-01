<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/includes/functions.php';

$db = require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    redirect('/index.php');
}

$title = 'Login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = loadData(['email', 'password']);

    $validation = new Valitron\Validator($data);

    $validation->rules([
        'required' => ['email', 'password'],
        'email' => ['email'],
    ]);

    if ($validation->validate()) {
        if (loginUser($db, $data)) {
            redirect('/index.php');
        } else {
            redirect('/login.php');
        }
    } else {
        $_SESSION['errors'] = getErrors($validation->errors());
    }
}

require_once __DIR__ . '/views/login.tpl.php';
