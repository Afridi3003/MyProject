<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

if ($path === '/admin') {
    require __DIR__ . '/admin.php';
    return true;
}

require __DIR__ . '/index.php';
