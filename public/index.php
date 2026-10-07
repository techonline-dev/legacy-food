<?php

// Legacy Food — Front Controller
declare(strict_types=1);

// Error reporting based on environment
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoloader for App namespace
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

// Load environment variables from .env and optional .env.local
$envFiles = [__DIR__ . '/../.env', __DIR__ . '/../.env.local'];
foreach ($envFiles as $envFile) {
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Load helper functions
require_once __DIR__ . '/../app/Helpers/functions.php';

// Start session if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// Instantiate Core objects
$request = new \App\Core\Request();
$response = new \App\Core\Response();
$router = new \App\Core\Router();

// Register Web Routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch Request
try {
    $router->dispatch($request, $response);
} catch (\Throwable $e) {
    if (config('app.debug', true)) {
        http_response_code(500);
        echo "<!DOCTYPE html><html><head><title>Legacy Food Error</title><style>body{font-family:sans-serif;padding:30px;background:#fdf6e3;color:#07160d;}pre{background:#fff;padding:20px;border-radius:8px;border:1px solid #bc944c;overflow-x:auto;}</style></head><body>";
        echo "<h1>Legacy Food Application Exception</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</body></html>";
    } else {
        http_response_code(500);
        $response->view('errors.500', ['title' => 'Server Error | Legacy Food']);
    }
}
