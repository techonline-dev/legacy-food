<?php

namespace App\Core;

class Request {
    private array $data = [];
    private string $method;
    private string $uri;

    public function __construct() {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($this->method === 'POST' && isset($_POST['_method'])) {
            $this->method = strtoupper($_POST['_method']);
        }

        $this->uri = $this->parseUri();
        $this->parseData();
    }

    private function parseUri(): string {
        $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
        $parts = explode('?', $rawUri, 2);
        $uri = '/' . trim($parts[0], '/');

        // Check configured APP_URL subpath (e.g. /legacyfood)
        $appUrl = function_exists('config') ? config('app.url', '') : (getenv('APP_URL') ?: '');
        if (!empty($appUrl)) {
            $parsedPath = parse_url($appUrl, PHP_URL_PATH);
            $appPath = '/' . trim((string)$parsedPath, '/');
            if ($appPath !== '/' && str_starts_with($uri, $appPath)) {
                $uri = substr($uri, strlen($appPath));
                $uri = '/' . trim($uri, '/');
            }
        }

        // Normalize when project is hosted in subfolder like /legacyfood/ or /legacyfood/public/
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $subDir = dirname($scriptName);
        $subDir = str_replace('\\', '/', $subDir);
        $subDir = preg_replace('#/(public)$#', '', $subDir);
        
        if (!empty($subDir) && $subDir !== '/' && str_starts_with($uri, $subDir)) {
            $uri = substr($uri, strlen($subDir));
            $uri = '/' . trim($uri, '/');
        }

        return $uri === '' ? '/' : $uri;
    }

    private function parseData(): void {
        $data = array_merge($_GET, $_POST);
        
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $data = array_merge($data, $json);
            }
        }

        $this->data = $data;
    }

    public function method(): string {
        return $this->method;
    }

    public function isMethod(string $method): bool {
        return strcasecmp($this->method, $method) === 0;
    }

    public function isPost(): bool {
        return $this->method === 'POST';
    }

    public function isGet(): bool {
        return $this->method === 'GET';
    }

    public function isAjax(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }

    public function uri(): string {
        return $this->uri;
    }

    public function all(): array {
        return $this->data;
    }

    public function input(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function query(string $key, $default = null) {
        return $_GET[$key] ?? ($this->data[$key] ?? $default);
    }

    public function file(string $key) {
        return $_FILES[$key] ?? null;
    }

    public function has(string $key): bool {
        return isset($this->data[$key]);
    }
}
