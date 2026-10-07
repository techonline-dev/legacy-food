<?php

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($val === false || $val === null) {
            return $default;
        }
        switch (strtolower((string)$val)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        return $val;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null) {
        static $configs = [];
        $parts = explode('.', $key);
        $file = $parts[0];

        if (!isset($configs[$file])) {
            $filePath = __DIR__ . '/../../config/' . $file . '.php';
            if (file_exists($filePath)) {
                $configs[$file] = require $filePath;
            } else {
                $configs[$file] = [];
            }
        }

        $val = $configs[$file];
        for ($i = 1; $i < count($parts); $i++) {
            if (is_array($val) && isset($val[$parts[$i]])) {
                $val = $val[$parts[$i]];
            } else {
                return $default;
            }
        }
        return $val;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $configuredUrl = rtrim((string)config('app.url', 'https://lensinteractive.com/legacyfood'), '/');
        $baseUrl = $configuredUrl;
        
        // Auto-detect base URL dynamically if running in local environment or HTTP_HOST is present
        if (isset($_SERVER['HTTP_HOST'])) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
                || (isset($_SERVER['HTTP_CF_VISITOR']) && str_contains($_SERVER['HTTP_CF_VISITOR'], 'https'));
            $protocol = $isHttps ? 'https://' : 'http://';
            
            $confHost = parse_url($configuredUrl, PHP_URL_HOST);
            if (!empty($configuredUrl) && !empty($confHost) && $_SERVER['HTTP_HOST'] === $confHost) {
                // Keep the configured URL when host matches production domain
                $baseUrl = $configuredUrl;
            } else {
                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
                $subDir = dirname($scriptName);
                $subDir = str_replace('\\', '/', $subDir);
                $subDir = preg_replace('#/(public)$#', '', $subDir);
                $subDir = rtrim($subDir, '/');
                if (!empty($subDir) && $subDir !== '/') {
                    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $subDir;
                } else {
                    $baseUrl = $protocol . $_SERVER['HTTP_HOST'];
                }
            }
        }
        
        $path = ltrim($path, '/');
        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

if (!function_exists('current_path')) {
    function current_path(): string {
        $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uriPath = parse_url($rawUri, PHP_URL_PATH) ?: '/';

        // Strip subdirectory prefix if application is hosted within a subfolder
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $subDir = dirname($scriptName);
        $subDir = str_replace('\\', '/', $subDir);
        $subDir = preg_replace('#/(public)$#', '', $subDir);
        $subDir = rtrim($subDir, '/');

        if (!empty($subDir) && $subDir !== '/' && str_starts_with($uriPath, $subDir)) {
            $uriPath = substr($uriPath, strlen($subDir));
        }

        $uriPath = '/' . ltrim($uriPath, '/');
        return rtrim($uriPath, '/') ?: '/';
    }
}

if (!function_exists('is_active_path')) {
    function is_active_path(string|array $target, bool $exact = false): bool {
        $current = current_path();

        if (is_array($target)) {
            foreach ($target as $t) {
                if (is_active_path($t, $exact)) {
                    return true;
                }
            }
            return false;
        }

        $target = '/' . trim($target, '/');
        if ($target === '//') $target = '/';

        if ($target === '/') {
            return $current === '/';
        }

        if ($exact) {
            return $current === $target;
        }

        return $current === $target || str_starts_with($current, $target . '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        $path = ltrim($path, '/');
        return url($path);
    }
}

if (!function_exists('currency_format')) {
    function currency_format($amount): string {
        $num = (float)$amount;
        return '₹' . number_format($num, 2);
    }
}

if (!function_exists('format_price')) {
    function format_price($amount): string {
        return currency_format($amount);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($token) && !empty($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
    }
}

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('setting')) {
    function setting(string $key, $default = null) {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('auth_check')) {
    function auth_check(): bool {
        return \App\Core\Auth::check();
    }
}

if (!function_exists('auth_admin')) {
    function auth_admin() {
        return \App\Core\Auth::admin();
    }
}

if (!function_exists('auth_admin_check')) {
    function auth_admin_check(): bool {
        return \App\Core\Auth::adminCheck();
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return;
        }
        if (isset($_SESSION['_flash'][$key])) {
            $msg = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('has_flash')) {
    function has_flash(string $key): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['_flash'][$key]);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url) {
        if (!preg_match('#^https?://#i', $url)) {
            $url = url($url);
        }
        header("Location: $url");
        exit;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text ?: 'n-a');
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = sanitize($val);
            }
            return $data;
        }
        return trim(strip_tags((string)$data));
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}
