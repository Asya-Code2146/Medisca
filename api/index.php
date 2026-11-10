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

if (! file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
}

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

require __DIR__.'/../public/index.php';
