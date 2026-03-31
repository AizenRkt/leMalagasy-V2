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
    $content = (string) ob_get_clean();

    $layout = $data['_layout'] ?? null;
    if ($layout === null && str_starts_with($view, 'front/')) {
        $layout = 'front/layout';
    }

    if (!is_string($layout) || $layout === '') {
        return $content;
    }

    $layoutFile = base_path('app/views/' . $layout . '.php');
    if (!is_file($layoutFile)) {
        throw new RuntimeException('Layout not found: ' . $layout);
    }

    $layoutData = $data;
    $layoutData['content'] = $content;
    $layoutData['view'] = $view;
    extract($layoutData, EXTR_SKIP);

    ob_start();
    require $layoutFile;

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

function article_url(string $title, int $id): string
{
    if ($id <= 0) {
        return '/article';
    }

    $value = trim($title);
    if ($value === '') {
        return '/article?id=' . (string) $id;
    }

    $slug = mb_strtolower($value, 'UTF-8');
    if (function_exists('iconv')) {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        if (is_string($converted) && $converted !== '') {
            $slug = $converted;
        }
    }

    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    if ($slug === '') {
        $slug = 'article';
    }

    return '/' . $slug . '-' . (string) $id . '.html';
}

function category_url(string $name, int $id): string
{
    if ($id <= 0) {
        return '/category';
    }

    $value = trim($name);
    if ($value === '') {
        return '/category?id=' . (string) $id;
    }

    $slug = mb_strtolower($value, 'UTF-8');
    if (function_exists('iconv')) {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        if (is_string($converted) && $converted !== '') {
            $slug = $converted;
        }
    }

    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    if ($slug === '') {
        $slug = 'category';
    }

    return '/category/' . $slug . '-' . (string) $id . '.html';
}

function absolute_url(string $path = '/'): string
{
    $baseUrl = (string) config('app.base_url', '');
    $normalizedBase = rtrim($baseUrl, '/');
    $normalizedPath = '/' . ltrim($path, '/');

    if ($normalizedBase === '') {
        return $normalizedPath;
    }

    return $normalizedBase . $normalizedPath;
}

function current_url(): string
{
    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = (string) (parse_url($requestUri, PHP_URL_PATH) ?? '/');

    return absolute_url($path);
}

function seo_description(string $text, int $maxLength = 160): string
{
    $clean = trim(strip_tags($text));
    $clean = preg_replace('/\s+/', ' ', $clean) ?? '';

    if ($clean === '') {
        return '';
    }

    if (mb_strlen($clean, 'UTF-8') <= $maxLength) {
        return $clean;
    }

    return rtrim(mb_substr($clean, 0, $maxLength - 1, 'UTF-8')) . '…';
}

function asset_url(string $path): string
{
    $normalizedPath = '/' . ltrim($path, '/');

    if (!str_starts_with($normalizedPath, '/assets/')) {
        return $normalizedPath;
    }

    $fullPath = base_path('public' . $normalizedPath);
    if (!is_file($fullPath)) {
        return $normalizedPath;
    }

    $version = (string) (filemtime($fullPath) ?: '1');

    return $normalizedPath . '?v=' . rawurlencode($version);
}
