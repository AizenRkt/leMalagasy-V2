<?php

declare(strict_types=1);

return [
    // Temporary admin password because current schema has no password column.
    'admin_password' => (string) env('ADMIN_PASSWORD', 'admin123'),
    'allowed_roles' => ['admin', 'editor', 'superjournalist'],
];
