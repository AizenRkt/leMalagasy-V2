<?php

declare(strict_types=1);

function base_path(string $path = ''): string
{
    $base = dirname(__DIR__, 2);

    if ($path === '') {
        return $base;
    }

    return $base . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
}

function view(string $view, array $data = []): string
{
    $viewFile = base_path('app/views/' . $view . '.php');

    if (!is_file($viewFile)) {
        throw new RuntimeException('View not found: ' . $view);
    }

    extract($data, EXTR_SKIP);

    ob_start();
    require $viewFile;

    return (string) ob_get_clean();
}

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    return $value === false || $value === null ? $default : $value;
}

function config(string $key, mixed $default = null): mixed
{
    static $cache = [];

    if (!str_contains($key, '.')) {
        return $default;
    }

    [$file, $path] = explode('.', $key, 2);

    if (!array_key_exists($file, $cache)) {
        $configFile = base_path('config/' . $file . '.php');
        $cache[$file] = is_file($configFile) ? require $configFile : [];
    }

    $value = $cache[$file];
    foreach (explode('.', $path) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}
