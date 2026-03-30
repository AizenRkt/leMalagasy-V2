<?php

declare(strict_types=1);

use App\Controllers\Front\HomeController;
use App\Controllers\Admin\ArticleController;
use App\Controllers\Admin\DashboardController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);

$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/articles/create', [ArticleController::class, 'create']);
$router->post('/admin/articles/create', [ArticleController::class, 'store']);
