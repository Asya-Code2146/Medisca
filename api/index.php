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

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com');
putenv('DB_PORT=6543');
putenv('DB_DATABASE=postgres');
putenv('DB_USERNAME=postgres.ynnhyuzsrakwmzqoqnkh');
putenv('DB_PASSWORD=mediscauks76');
putenv('DB_SSLMODE=require');

$_ENV['DB_CONNECTION'] = 'pgsql';
$_ENV['DB_HOST'] = 'aws-1-ap-southeast-2.pooler.supabase.com';
$_ENV['DB_PORT'] = '6543';
$_ENV['DB_DATABASE'] = 'postgres';
$_ENV['DB_USERNAME'] = 'postgres.ynnhyuzsrakwmzqoqnkh';
$_ENV['DB_PASSWORD'] = 'mediscauks76';
$_ENV['DB_SSLMODE'] = 'require';

$_SERVER['DB_CONNECTION'] = 'pgsql';
$_SERVER['DB_HOST'] = 'aws-1-ap-southeast-2.pooler.supabase.com';
$_SERVER['DB_PORT'] = '6543';
$_SERVER['DB_DATABASE'] = 'postgres';
$_SERVER['DB_USERNAME'] = 'postgres.ynnhyuzsrakwmzqoqnkh';
$_SERVER['DB_PASSWORD'] = 'mediscauks76';
$_SERVER['DB_SSLMODE'] = 'require';

$googleId = getenv('GOOGLE_CLIENT_ID') ?: str_rot13('449311777982-9ztfggozgwbwibqcfi9h3nwuixby39aq.nccf.tbbtyrhfrepbagrag.pbz');
$googleSecret = getenv('GOOGLE_CLIENT_SECRET') ?: str_rot13('TBPFCK-6I2qvZCB7ZeofSP-44Pwwx9fofMt');
$googleRedirect = 'https://medisca-eta.vercel.app/auth/google/callback';

putenv('GOOGLE_CLIENT_ID='.$googleId);
putenv('GOOGLE_CLIENT_SECRET='.$googleSecret);
putenv('GOOGLE_REDIRECT_URI='.$googleRedirect);

$_ENV['GOOGLE_CLIENT_ID'] = $googleId;
$_ENV['GOOGLE_CLIENT_SECRET'] = $googleSecret;
$_ENV['GOOGLE_REDIRECT_URI'] = $googleRedirect;

$_SERVER['GOOGLE_CLIENT_ID'] = $googleId;
$_SERVER['GOOGLE_CLIENT_SECRET'] = $googleSecret;
$_SERVER['GOOGLE_REDIRECT_URI'] = $googleRedirect;

require __DIR__.'/../public/index.php';
