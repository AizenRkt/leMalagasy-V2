<?php

declare(strict_types=1);

namespace App\Middlewares;

final class RoleMiddleware
{
    public static function ensure(string $role): void
    {
        // Replace this with your user role resolution.
        $userRole = 'admin';

        if ($userRole !== $role) {
            http_response_code(403);
            exit('Forbidden');
        }
    }
}
