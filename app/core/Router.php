<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, mixed>> */
    private array $routes = [];

    public function get(string $uri, mixed $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, mixed $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function dispatch(string $method, string $uri): void
    {
        $normalizedUri = $this->normalizeUri($uri);
        $action = $this->routes[$method][$normalizedUri] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo view('errors/404', ['uri' => $normalizedUri]);
            return;
        }

        if (is_callable($action)) {
            echo $action();
            return;
        }

        if (is_array($action) && count($action) === 2) {
            [$controllerClass, $controllerMethod] = $action;
            $controller = new $controllerClass();
            echo $controller->{$controllerMethod}();
            return;
        }

        throw new \RuntimeException('Invalid route action for ' . $normalizedUri);
    }

    private function addRoute(string $method, string $uri, mixed $action): void
    {
        $this->routes[$method][$this->normalizeUri($uri)] = $action;
    }

    private function normalizeUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $trimmed = '/' . trim($path, '/');

        return $trimmed === '//' ? '/' : $trimmed;
    }
}
