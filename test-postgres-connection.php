<?php
require_once __DIR__ . '/database.php';

$pdo = silinexDb();

if (!$pdo) {
    fwrite(STDERR, "PostgreSQL connection failed. Check DB_DRIVER, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, and DB_SSLMODE.\n");
    exit(1);
}

$version = $pdo->query('SELECT version() AS version')->fetch();
echo "PostgreSQL connected successfully.\n";
echo ($version['version'] ?? 'Version unavailable') . "\n";
