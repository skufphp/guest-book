<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/includes/functions.php';

$db = require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    redirect('/index.php');
}

$title = 'Register';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = loadData(['name', 'email', 'password']);

    $validation = new Valitron\Validator($data);

    $validation->rules([
            'required' => ['name', 'email', 'password'],
            'email' => ['email'],
            'lengthMin' => [
                    ['password', 6],
            ],
            'lengthMax' => [
                    ['name', 50],
                    ['email', 50],
            ]
    ]);

    if ($validation->validate()) {
        if (registerUser($db, $data)) {
            redirect('/login.php');
        }
    } else {
        $_SESSION['errors'] = getErrors($validation->errors());
    }
}

require_once __DIR__ . '/views/register.tpl.php';
