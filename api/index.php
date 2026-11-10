<?php

$paths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($paths as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

$dbPath = '/tmp/database.sqlite';
$isNew = ! file_exists($dbPath) || filesize($dbPath) === 0;

if ($isNew) {
    touch($dbPath);
}

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $dbPath);
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $dbPath;
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $dbPath;

if ($isNew) {
    $artisan = __DIR__ . '/../artisan';
    exec('php ' . escapeshellarg($artisan) . ' migrate --seed --force 2>/dev/null');
}

require __DIR__.'/../public/index.php';
