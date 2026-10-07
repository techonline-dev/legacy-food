<?php

namespace App\Core;

class Response {
    private int $statusCode = 200;
    private array $headers = [];

    public function status(int $code): self {
        $this->statusCode = $code;
        return $this;
    }

    public function header(string $key, string $value): self {
        $this->headers[$key] = $value;
        return $this;
    }

    public function json(array $data, int $code = 200): void {
        $this->status($code);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->sendHeaders();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function view(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        $viewFile = __DIR__ . '/../../views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            $this->status(500);
            echo "View not found: " . htmlspecialchars($viewPath);
            exit;
        }

        // Extract variables into view scope
        extract($data, EXTR_SKIP);

        // Capture view output
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If a layout is specified, render the view inside the layout
        if ($layout !== null) {
            $layoutFile = __DIR__ . '/../../views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                $this->sendHeaders();
                include $layoutFile;
                exit;
            }
        }

        $this->sendHeaders();
        echo $content;
        exit;
    }

    public function redirect(string $url): void {
        if (!preg_match('#^https?://#i', $url)) {
            $url = url($url);
        }
        $this->header('Location', $url);
        $this->sendHeaders();
        exit;
    }

    private function sendHeaders(): void {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
    }
}
