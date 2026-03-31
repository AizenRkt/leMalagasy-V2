<?php

declare(strict_types=1);

use App\Core\Autoloader;
use App\Core\Router;

require_once dirname(__DIR__) . '/app/core/Autoloader.php';
require_once dirname(__DIR__) . '/app/core/helpers.php';

Autoloader::register();
ensure_session_started();

$router = new Router();

require_once dirname(__DIR__) . '/routes/web.php';
require_once dirname(__DIR__) . '/routes/admin.php';

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = (string) (parse_url($requestUri, PHP_URL_PATH) ?? '/');

if (str_starts_with($requestPath, '/admin')) {
	$isLoginPath = ($requestPath === '/admin/login');

	if (!admin_is_authenticated() && !$isLoginPath) {
		header('Location: /admin/login');
		exit;
	}

	if (admin_is_authenticated() && $isLoginPath && strtoupper($requestMethod) === 'GET') {
		header('Location: /admin/dashboard');
		exit;
	}
}

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
