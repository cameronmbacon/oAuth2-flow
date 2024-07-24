<?php

// Load environment variables for local development
if (file_exists(__DIR__ . '/config.dev.php')) {
    require __DIR__ . '/config.dev.php';
}

return [
    'db_host' => getenv('DB_HOST'),
    'db_name' => getenv('DB_NAME'),
    'db_user' => getenv('DB_USER'),
    'db_pass' => getenv('DB_PASS'),
    'github_client_id' => getenv('GITHUB_CLIENT_ID'),
    'github_client_secret' => getenv('GITHUB_CLIENT_SECRET'),
    'github_redirect_uri' => getenv('GITHUB_REDIRECT_URI'),
];