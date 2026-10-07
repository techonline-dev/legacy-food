<?php

namespace App\Core;

class Router {
    private array $routes = [];
    private array $groupMiddleware = [];

    public function get(string $path, $handler, array $middleware = []): self {
        $this->addRoute('GET', $path, $handler, $middleware);
        return $this;
    }

    public function post(string $path, $handler, array $middleware = []): self {
        $this->addRoute('POST', $path, $handler, $middleware);
        return $this;
    }

    public function put(string $path, $handler, array $middleware = []): self {
        $this->addRoute('PUT', $path, $handler, $middleware);
        return $this;
    }

    public function delete(string $path, $handler, array $middleware = []): self {
        $this->addRoute('DELETE', $path, $handler, $middleware);
        return $this;
    }

    public function group(array $attributes, callable $callback): void {
        $prevMiddleware = $this->groupMiddleware;
        if (isset($attributes['middleware'])) {
            $middlewares = (array)$attributes['middleware'];
            $this->groupMiddleware = array_merge($this->groupMiddleware, $middlewares);
        }

        $callback($this);

        $this->groupMiddleware = $prevMiddleware;
    }

    private function addRoute(string $method, string $path, $handler, array $middleware = []): void {
        $mergedMiddleware = array_merge($this->groupMiddleware, $middleware);
        $this->routes[] = [
            'method' => $method,
            'path' => '/' . trim($path, '/'),
            'handler' => $handler,
            'middleware' => $mergedMiddleware,
        ];
    }

    public function dispatch(Request $request, Response $response): void {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = $val;
                    }
                }

                // Execute middlewares
                foreach ($route['middleware'] as $mw) {
                    $mwInstance = is_string($mw) ? new $mw() : $mw;
                    $allowed = $mwInstance->handle($request, $response);
                    if ($allowed === false) {
                        return;
                    }
                }

                // Call Handler
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    call_user_func($handler, $request, $response, ...array_values($params));
                    return;
                }

                if (is_string($handler) && str_contains($handler, '@')) {
                    [$ctrlClass, $action] = explode('@', $handler);
                    if (!str_starts_with($ctrlClass, '\\') && !str_starts_with($ctrlClass, 'App\\')) {
                        $ctrlClass = "\\App\\Controllers\\" . ltrim($ctrlClass, '\\');
                    }

                    if (class_exists($ctrlClass)) {
                        $ctrl = new $ctrlClass($request, $response);
                        if (method_exists($ctrl, $action)) {
                            call_user_func_array([$ctrl, $action], array_values($params));
                            return;
                        }
                    }
                }

                $response->status(500);
                echo "Controller or action not found: " . htmlspecialchars(is_string($handler) ? $handler : 'Closure');
                return;
            }
        }

        // Route not found -> 404
        $response->status(404);
        if ($request->isAjax()) {
            $response->json(['success' => false, 'error' => 'Resource not found'], 404);
        } else {
            $response->view('errors.404', ['title' => 'Page Not Found | Legacy Food'], 'main');
        }
    }
}
