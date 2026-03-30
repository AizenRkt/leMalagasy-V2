<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

final class Database
{
    private static ?PDO $postgres = null;
    private static ?object $mongodb = null;

    public static function postgres(): PDO
    {
        if (self::$postgres instanceof PDO) {
            return self::$postgres;
        }

        $cfg = config('database.postgres');
        if (!is_array($cfg)) {
            throw new RuntimeException('Missing database.postgres configuration.');
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;options=--client_encoding=%s',
            $cfg['host'],
            $cfg['port'],
            $cfg['database'],
            $cfg['charset']
        );

        self::$postgres = new PDO(
            $dsn,
            (string) $cfg['username'],
            (string) $cfg['password'],
            $cfg['options'] ?? []
        );

        return self::$postgres;
    }

    public static function mongodb(): object
    {
        if (self::$mongodb !== null) {
            return self::$mongodb;
        }

        $managerClass = 'MongoDB\\Driver\\Manager';
        if (!class_exists($managerClass)) {
            throw new RuntimeException(
                'MongoDB extension is missing. Install ext-mongodb in your PHP container.'
            );
        }

        $cfg = config('database.mongodb');
        if (!is_array($cfg)) {
            throw new RuntimeException('Missing database.mongodb configuration.');
        }

        $authPart = '';
        if (!empty($cfg['username'])) {
            $authPart = rawurlencode((string) $cfg['username']);
            if (!empty($cfg['password'])) {
                $authPart .= ':' . rawurlencode((string) $cfg['password']);
            }
            $authPart .= '@';
        }

        $uri = sprintf(
            'mongodb://%s%s:%d/%s?authSource=%s',
            $authPart,
            $cfg['host'],
            $cfg['port'],
            $cfg['database'],
            $cfg['auth_source']
        );

        self::$mongodb = new $managerClass($uri);

        return self::$mongodb;
    }
}
