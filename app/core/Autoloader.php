<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            $prefix = 'App\\';
            $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (is_file($file)) {
                require_once $file;
            }
        });
    }
}
