<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/Pagination.php';

$db = require_once __DIR__ . '/includes/db.php';

$title = 'Home';

if (isset($_POST['submit-message'])) {
    $data = loadData(['message']);
    $validation = new Valitron\Validator($data);
    $validation->rules([
        'required' => ['message'],
    ]);

    if ($validation->validate()) {
        if (saveMessage($db, $data)) {
            redirect('/index.php');
        }
    } else {
        $_SESSION['errors'] = getErrors($validation->errors());
    }
}

if (isset($_POST['edit-message'])) {
    $data = loadData(['message', 'id', 'page']);
    $validation = new Valitron\Validator($data);
    $validation->rules([
        'required' => ['message', 'id'],
        'integer' => ['id', 'page'],
    ]);

    if ($validation->validate()) {
        if (editMessage($db, $data)) {
            redirect("/index.php?page={$data['page']}#message-{$data['id']}");
        }
    } else {
        $_SESSION['errors'] = getErrors($validation->errors());
    }
}

if (isset($_GET['do']) && $_GET['do'] == 'toggle-status') {
    $id = (int)$_GET['id'] ?? 0;
    $status = (isset($_GET['status']) && $_GET['status'] == '1') ? 1 : 0;

    toggleMessageStatus($db, $status, $id);
    $page = isset($_GET['page']) ? "?page=" . (int)$_GET['page'] : "?page=1";
    redirect("/index.php$page#message-$id");
}

$page = $_GET['page'] ?? 1;
$postsPerPage = 2;
$totalPages = getCountMessages($db);
$pagination = new Pagination((int)$page, $postsPerPage, $totalPages);
$startPage = $pagination->getStart();

$messages = getMessages($db, $startPage, $postsPerPage);

require_once __DIR__ . '/views/index.tpl.php';
