<?php

declare(strict_types=1);

use App\Core\Autoloader;
use App\Core\Router;

require_once dirname(__DIR__) . '/app/core/Autoloader.php';
require_once dirname(__DIR__) . '/app/core/helpers.php';

Autoloader::register();

$router = new Router();

require_once dirname(__DIR__) . '/routes/web.php';
require_once dirname(__DIR__) . '/routes/admin.php';

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
