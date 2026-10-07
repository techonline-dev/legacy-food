<?php

// Check host and environment to identify whether running locally
$hostHeader = $_SERVER['HTTP_HOST'] ?? '';
$serverIp = $_SERVER['SERVER_ADDR'] ?? '';

$isLocalHost = in_array($hostHeader, ['localhost', '127.0.0.1', '::1', 'legacyfood.test', 'localhost:8000', 'localhost:8080'])
    || str_ends_with($hostHeader, '.test')
    || str_ends_with($hostHeader, '.local')
    || str_ends_with($hostHeader, '.localhost')
    || in_array($serverIp, ['127.0.0.1', '::1'])
    || (php_sapi_name() === 'cli');

$appEnv = strtolower((string)($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? getenv('APP_ENV') ?: ''));
$isLocalEnv = ($appEnv === 'local' || $appEnv === 'development' || $appEnv === 'dev');

// Determine which DB target to use: 'auto' (default), 'local', or 'production'
$dbTarget = strtolower((string)($_ENV['DB_TARGET'] ?? $_SERVER['DB_TARGET'] ?? getenv('DB_TARGET') ?: 'auto'));

$useLocal = false;
if ($dbTarget === 'local') {
    $useLocal = true;
} elseif ($dbTarget === 'production' || $dbTarget === 'prod') {
    $useLocal = false;
} else {
    // 'auto' mode: uses local credentials if on localhost/.test domain or APP_ENV=local
    $useLocal = $isLocalEnv || $isLocalHost;
}

// ====================================================================
// 1. Local Database Configuration (Laragon / XAMPP / Localhost MySQL)
// ====================================================================
$localHost     = $_ENV['DB_LOCAL_HOST'] ?? $_SERVER['DB_LOCAL_HOST'] ?? getenv('DB_LOCAL_HOST') ?: '127.0.0.1';
$localPort     = $_ENV['DB_LOCAL_PORT'] ?? $_SERVER['DB_LOCAL_PORT'] ?? getenv('DB_LOCAL_PORT') ?: '3306';
$localDatabase = $_ENV['DB_LOCAL_DATABASE'] ?? $_SERVER['DB_LOCAL_DATABASE'] ?? getenv('DB_LOCAL_DATABASE') ?: 'legacyfood';
$localUsername = $_ENV['DB_LOCAL_USERNAME'] ?? $_SERVER['DB_LOCAL_USERNAME'] ?? getenv('DB_LOCAL_USERNAME') ?: 'root';
$localPassword = $_ENV['DB_LOCAL_PASSWORD'] ?? $_SERVER['DB_LOCAL_PASSWORD'] ?? (getenv('DB_LOCAL_PASSWORD') !== false ? getenv('DB_LOCAL_PASSWORD') : '');
$localCharset  = $_ENV['DB_LOCAL_CHARSET'] ?? $_SERVER['DB_LOCAL_CHARSET'] ?? getenv('DB_LOCAL_CHARSET') ?: 'utf8mb4';

// ====================================================================
// 2. Production Database Configuration (Live Server / Hostinger Cloud)
// ====================================================================
$prodHost     = $_ENV['DB_PROD_HOST'] ?? $_SERVER['DB_PROD_HOST'] ?? getenv('DB_PROD_HOST') ?: 'localhost';
$prodPort     = $_ENV['DB_PROD_PORT'] ?? $_SERVER['DB_PROD_PORT'] ?? getenv('DB_PROD_PORT') ?: '3306';
$prodDatabase = $_ENV['DB_PROD_DATABASE'] ?? $_SERVER['DB_PROD_DATABASE'] ?? getenv('DB_PROD_DATABASE') ?: 'u291611210_legacyfood';
$prodUsername = $_ENV['DB_PROD_USERNAME'] ?? $_SERVER['DB_PROD_USERNAME'] ?? getenv('DB_PROD_USERNAME') ?: 'u291611210_legacyfood';
$prodPassword = $_ENV['DB_PROD_PASSWORD'] ?? $_SERVER['DB_PROD_PASSWORD'] ?? (getenv('DB_PROD_PASSWORD') !== false ? getenv('DB_PROD_PASSWORD') : 'rPw6TR5Z$');
$prodCharset  = $_ENV['DB_PROD_CHARSET'] ?? $_SERVER['DB_PROD_CHARSET'] ?? getenv('DB_PROD_CHARSET') ?: 'utf8mb4';

// ====================================================================
// Active Connection Resolution
// ====================================================================
if ($useLocal) {
    $activeTarget = 'local';
    $host = $localHost;
    $port = $localPort;
    $database = $localDatabase;
    $username = $localUsername;
    $password = $localPassword;
    $charset = $localCharset;
} else {
    $activeTarget = 'production';
    $host = $prodHost;
    $port = $prodPort;
    $database = $prodDatabase;
    $username = $prodUsername;
    $password = $prodPassword;
    $charset = $prodCharset;
}

// Fallback to generic DB_* variables if specified directly without LOCAL/PROD prefixes
if (isset($_ENV['DB_DATABASE']) && !isset($_ENV['DB_LOCAL_DATABASE']) && !isset($_ENV['DB_PROD_DATABASE'])) {
    $host = $_ENV['DB_HOST'] ?? $host;
    $port = $_ENV['DB_PORT'] ?? $port;
    $database = $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USERNAME'] ?? $username;
    $password = $_ENV['DB_PASSWORD'] ?? $password;
    $charset = $_ENV['DB_CHARSET'] ?? $charset;
}

return [
    'target' => $activeTarget,
    'is_local' => $useLocal,
    'host' => $host,
    'port' => $port,
    'database' => $database,
    'username' => $username,
    'password' => $password,
    'charset' => $charset,

    'connections' => [
        'local' => [
            'host' => $localHost,
            'port' => $localPort,
            'database' => $localDatabase,
            'username' => $localUsername,
            'password' => $localPassword,
            'charset' => $localCharset,
        ],
        'production' => [
            'host' => $prodHost,
            'port' => $prodPort,
            'database' => $prodDatabase,
            'username' => $prodUsername,
            'password' => $prodPassword,
            'charset' => $prodCharset,
        ],
    ],

    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
