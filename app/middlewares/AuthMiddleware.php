<?php

declare(strict_types=1);

namespace App\Middlewares;

final class AuthMiddleware
{
    public static function check(): void
    {
        ensure_session_started();
        $isAuthenticated = admin_is_authenticated();

        if (!$isAuthenticated) {
            header('Location: /admin/login');
            exit;
        }
    }
}
