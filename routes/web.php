<?php

declare(strict_types=1);

use App\Controllers\Front\HomeController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
