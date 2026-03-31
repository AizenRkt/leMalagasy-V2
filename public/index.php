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

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

$seoRoute = $_GET['route'] ?? null;
$seoId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (is_string($seoRoute) && $seoId > 0) {
	if ($seoRoute === 'category') {
		$_GET['id'] = $seoId;
		$requestUri = '/category?id=' . (string) $seoId;
	} elseif ($seoRoute === 'article') {
		$_GET['id'] = $seoId;
		$requestUri = '/article?id=' . (string) $seoId;
	}
}

$router->dispatch($requestMethod, $requestUri);
