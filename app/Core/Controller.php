<?php

namespace App\Core;

abstract class Controller {
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    protected function view(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        $this->response->view($viewPath, $data, $layout);
    }

    protected function json(array $data, int $code = 200): void {
        $this->response->json($data, $code);
    }

    protected function redirect(string $url): void {
        $this->response->redirect($url);
    }

    protected function validate(array $rules): array {
        $errors = [];
        $data = $this->request->all();

        foreach ($rules as $field => $ruleString) {
            $ruleList = explode('|', $ruleString);
            $val = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && (empty($val) && $val !== '0')) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                } elseif ($rule === 'email' && !empty($val) && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Please provide a valid email address.";
                } elseif (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (!empty($val) && strlen((string)$val) < $min) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters.";
                    }
                } elseif (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (!empty($val) && strlen((string)$val) > $max) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " may not exceed {$max} characters.";
                    }
                } elseif ($rule === 'numeric' && !empty($val) && !is_numeric($val)) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be numeric.";
                }
            }
        }

        return $errors;
    }

    protected function validateCsrf(): bool {
        $isLocal = (env('APP_ENV') === 'development' || env('APP_ENV') === 'local' || in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']));
        $token = $this->request->input('_csrf_token') ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        $valid = verify_csrf($token);
        if (!$valid && $isLocal) {
            return true;
        }
        return $valid;
    }
}
