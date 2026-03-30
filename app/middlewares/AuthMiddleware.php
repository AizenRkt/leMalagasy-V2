<?php

declare(strict_types=1);

namespace App\Middlewares;

final class AuthMiddleware
{
    public static function check(): void
    {
        // Replace this with real session-based authentication.
        $isAuthenticated = true;

        if (!$isAuthenticated) {
            http_response_code(401);
            exit('Unauthorized');
        }
    }
}
