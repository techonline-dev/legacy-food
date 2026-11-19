<?php

$readEnv = static function (string $key, ?string $default = null): ?string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string)$value;
};

$isLiveServer = (
    str_contains(__DIR__, '/home/u291611210') ||
    str_contains(__DIR__, 'lensinteractive.com') ||
    (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'lensinteractive.com')) ||
    (isset($_SERVER['SERVER_NAME']) && str_contains($_SERVER['SERVER_NAME'], 'lensinteractive.com'))
);

$appEnv = strtolower($readEnv('APP_ENV', $isLiveServer ? 'production' : 'local') ?? ($isLiveServer ? 'production' : 'local'));
$defaultTarget = ($isLiveServer || in_array($appEnv, ['production', 'prod'], true)) ? 'production' : 'local';
$dbTarget = strtolower($readEnv('DB_TARGET', $defaultTarget) ?? $defaultTarget);

if ($dbTarget === 'auto') {
    $dbTarget = ($isLiveServer || in_array($appEnv, ['production', 'prod'], true)) ? 'production' : 'local';
}

$useLocal = $isLiveServer ? false : !in_array($dbTarget, ['production', 'prod'], true);

$local = [
    'host' => $readEnv('DB_LOCAL_HOST', $readEnv('DB_HOST', '127.0.0.1')),
    'port' => $readEnv('DB_LOCAL_PORT', $readEnv('DB_PORT', '3306')),
    'database' => $readEnv('DB_LOCAL_DATABASE', $readEnv('DB_DATABASE', 'legacyfood')),
    'username' => $readEnv('DB_LOCAL_USERNAME', $readEnv('DB_USERNAME', 'root')),
    'password' => $readEnv('DB_LOCAL_PASSWORD', $readEnv('DB_PASSWORD', '')),
    'charset' => $readEnv('DB_LOCAL_CHARSET', $readEnv('DB_CHARSET', 'utf8mb4')),
];

$production = [
    'host' => $readEnv('DB_PROD_HOST', $readEnv('DB_HOST', 'localhost')),
    'port' => $readEnv('DB_PROD_PORT', $readEnv('DB_PORT', '3306')),
    'database' => $readEnv('DB_PROD_DATABASE', $readEnv('DB_DATABASE', 'u291611210_legacyfood')),
    'username' => $readEnv('DB_PROD_USERNAME', $readEnv('DB_USERNAME', 'u291611210_legacyfood')),
    'password' => $readEnv('DB_PROD_PASSWORD', $readEnv('DB_PASSWORD', 'b=S5riu3')),
    'charset' => $readEnv('DB_PROD_CHARSET', $readEnv('DB_CHARSET', 'utf8mb4')),
];

$active = $useLocal ? $local : $production;

return [
    'target' => $useLocal ? 'local' : 'production',
    'is_local' => $useLocal,
    'host' => $active['host'],
    'port' => $active['port'],
    'database' => $active['database'],
    'username' => $active['username'],
    'password' => $active['password'],
    'charset' => $active['charset'],

    'connections' => [
        'local' => $local,
        'production' => $production,
    ],

    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
