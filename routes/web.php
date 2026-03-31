<?php

declare(strict_types=1);

use App\Controllers\Front\HomeController;
use App\Controllers\Admin\ArticleController;
use App\Controllers\Admin\CategoryFeaturedController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\HomeFeedController;

use App\Controllers\Admin\TaxonomyController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/article', [HomeController::class, 'singleArticle']);
$router->get('/category', [HomeController::class, 'singleCategory']);

$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/articles', [ArticleController::class, 'index']);
$router->get('/admin/articles/create', [ArticleController::class, 'create']);
$router->post('/admin/articles/create', [ArticleController::class, 'store']);
$router->get('/admin/articles/edit', [ArticleController::class, 'edit']);
$router->post('/admin/articles/edit', [ArticleController::class, 'update']);
$router->post('/admin/articles/status', [ArticleController::class, 'changeStatus']);

$router->get('/admin/feed', [HomeFeedController::class, 'index']);
$router->post('/admin/feed', [HomeFeedController::class, 'save']);
$router->get('/admin/category-featured', [CategoryFeaturedController::class, 'index']);
$router->post('/admin/category-featured', [CategoryFeaturedController::class, 'save']);

// Taxonomy (Categories & Tags)
$router->get('/admin/categories', [TaxonomyController::class, 'categories']);
$router->get('/admin/tags', [TaxonomyController::class, 'tags']);
$router->get('/admin/taxonomy/create', [TaxonomyController::class, 'createView']);
$router->post('/admin/taxonomy/create', [TaxonomyController::class, 'store']);
$router->get('/admin/taxonomy/edit', [TaxonomyController::class, 'editView']);
$router->post('/admin/taxonomy/update', [TaxonomyController::class, 'update']);
$router->get('/admin/taxonomy/delete', [TaxonomyController::class, 'delete']);
