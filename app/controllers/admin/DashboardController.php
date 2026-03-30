<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;

final class DashboardController
{
    public function index(): string
    {
        AuthMiddleware::check();

        return view('admin/dashboard', [
            'title' => 'Dashboard admin',
        ]);
    }
}
