<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$envFiles = [__DIR__ . '/../.env', __DIR__ . '/../.env.local'];
foreach ($envFiles as $envFile) {
    if (!file_exists($envFile)) {
        continue;
    }
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

require_once __DIR__ . '/../app/Helpers/functions.php';

try {
    $result = \App\Core\AdminProvisioner::fromEnv(true);
    fwrite(STDOUT, sprintf("Admin %s: %s\n", $result['action'], $result['email']));
    exit(0);
} catch (\Throwable $e) {
    fwrite(STDERR, "Admin provisioning failed: " . $e->getMessage() . "\n");
    exit(1);
}
