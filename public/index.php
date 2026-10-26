<?php

// Legacy Food — Front Controller
declare(strict_types=1);

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

// Error reporting based on environment
$debug = filter_var(config('app.debug', false), FILTER_VALIDATE_BOOLEAN);
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
header_remove('X-Powered-By');

// Secure session configuration
$appUrl = (string)config('app.url', '');
$isHttps = str_starts_with(strtolower($appUrl), 'https://');
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('LFSESSID');
    session_start();
}

// Idle session timeout (2 hours)
$idleLimit = 7200;
if (isset($_SESSION['_last_activity']) && (time() - (int)$_SESSION['_last_activity']) > $idleLimit) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['_last_activity'] = time();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Instantiate Core objects
$request = new \App\Core\Request();
$response = new \App\Core\Response();

// Global CSRF enforcement for state-changing requests
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
if (!in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
    $token = $request->input('_csrf_token') ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
    if (!verify_csrf(is_string($token) ? $token : null)) {
        if ($request->isAjax()) {
            $response->json(['success' => false, 'error' => 'Security token invalid or expired. Please refresh the page.'], 419);
        }
        flash('error', 'Security token expired. Please try again.');
        $target = '/';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer !== '') {
            $parts = parse_url($referer);
            $refHost = strtolower((string)($parts['host'] ?? ''));
            $appHost = strtolower((string)parse_url($appUrl, PHP_URL_HOST));
            if ($refHost !== '' && $appHost !== '' && hash_equals($appHost, $refHost)) {
                $target = ((string)($parts['path'] ?? '/')) . (isset($parts['query']) ? '?' . $parts['query'] : '');
            }
        }
        $response->redirect($target);
    }
}

$router = new \App\Core\Router();

// Register Web Routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch Request
try {
    $router->dispatch($request, $response);
} catch (\Throwable $e) {
    error_log('Unhandled application error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if ($debug) {
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
