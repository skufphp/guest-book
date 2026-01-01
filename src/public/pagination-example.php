<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$initialData = range(1, 105);

$postsPerPage = 10;
$totalPosts = count($initialData);

$totalPages = ceil($totalPosts / $postsPerPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}
if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $postsPerPage;

dump(array_slice($initialData, $offset, $postsPerPage));

for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}
