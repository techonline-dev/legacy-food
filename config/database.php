<?php

// Check host, domain, and server path to reliably detect local dev vs live production server
$hostHeader = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
$hostName = strtolower(explode(':', $hostHeader)[0] ?? '');

// Known local development hostnames
$isLocalDomain = in_array($hostName, ['localhost', '127.0.0.1', '::1', 'legacyfood.test', 'localhost:8000', 'localhost:8080'])
    || str_ends_with($hostName, '.test')
    || str_ends_with($hostName, '.local')
    || str_ends_with($hostName, '.localhost');

// Live production indicators:
// 1. Host domain contains lensinteractive.com or live public domain
// 2. Filesystem path is Linux server directory (/home/... or /public_html/)
$currentDir = str_replace('\\', '/', __DIR__);
$isProductionPath = str_starts_with($currentDir, '/home/')
    || str_contains($currentDir, '/public_html/')
    || str_contains($currentDir, 'lensinteractive.com');

$isProductionDomain = str_contains($hostName, 'lensinteractive.com')
    || (!empty($hostName) && !$isLocalDomain);

$isCli = (php_sapi_name() === 'cli');
$isWindows = (PHP_OS_FAMILY === 'Windows');

// True local environment: ONLY when on a local development domain/machine, and NEVER on production path or domain
$isLocalHost = ($isLocalDomain || ($isCli && $isWindows)) && !$isProductionPath && !$isProductionDomain;

$appEnv = strtolower((string)($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? getenv('APP_ENV') ?: ''));
$isLocalEnv = ($appEnv === 'local' || $appEnv === 'development' || $appEnv === 'dev');
$isProdEnv = ($appEnv === 'production' || $appEnv === 'prod');

// Determine which DB target to use: 'auto' (default), 'local', or 'production'
$dbTarget = strtolower((string)($_ENV['DB_TARGET'] ?? $_SERVER['DB_TARGET'] ?? getenv('DB_TARGET') ?: 'auto'));

$useLocal = false;
if ($dbTarget === 'local') {
    $useLocal = true;
} elseif ($dbTarget === 'production' || $dbTarget === 'prod') {
    $useLocal = false;
} else {
    // 'auto' mode:
    // If on live production domain or server path, ALWAYS default to production database
    if ($isProductionDomain || $isProductionPath || $isProdEnv) {
        $useLocal = false;
    } elseif ($isLocalHost || $isLocalEnv) {
        $useLocal = true;
    } else {
        $useLocal = false;
    }
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

// Fallback to generic DB_* variables if specified directly
if (isset($_ENV['DB_DATABASE'])) {
    if (!$useLocal) {
        // In production, do NOT let default 'root'/empty overwrite production settings
        if (!empty($_ENV['DB_USERNAME']) && $_ENV['DB_USERNAME'] !== 'root') {
            $host = $_ENV['DB_HOST'] ?? $host;
            $port = $_ENV['DB_PORT'] ?? $port;
            $database = $_ENV['DB_DATABASE'] ?? $database;
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'] ?? $password;
            $charset = $_ENV['DB_CHARSET'] ?? $charset;
        }
    } else {
        if (!isset($_ENV['DB_LOCAL_DATABASE'])) {
            $host = $_ENV['DB_HOST'] ?? $host;
            $port = $_ENV['DB_PORT'] ?? $port;
            $database = $_ENV['DB_DATABASE'];
            $username = $_ENV['DB_USERNAME'] ?? $username;
            $password = $_ENV['DB_PASSWORD'] ?? $password;
            $charset = $_ENV['DB_CHARSET'] ?? $charset;
        }
    }
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
