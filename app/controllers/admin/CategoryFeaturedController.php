<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Services\CategoryFeaturedAdminService;

final class CategoryFeaturedController
{
    private CategoryFeaturedAdminService $service;

    public function __construct()
    {
        $this->service = new CategoryFeaturedAdminService();
    }

    public function index(): string
    {
        AuthMiddleware::check();

        $categories = $this->service->listCategories();

        $selectedCategoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
        if ($selectedCategoryId === false || $selectedCategoryId === null || $selectedCategoryId <= 0) {
            $selectedCategoryId = isset($categories[0]) ? (int) $categories[0]['id'] : 0;
        }

        $articles = $selectedCategoryId > 0 ? $this->service->listCategoryArticles($selectedCategoryId) : [];
        $featured = $selectedCategoryId > 0 ? $this->service->getCurrentFeaturedIds($selectedCategoryId) : [];

        return view('admin/category-featured/index', [
            'title' => 'Articles phares par categorie',
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
            'articles' => $articles,
            'featured' => $this->padSlots($featured, 3),
            'saved' => isset($_GET['saved']) && $_GET['saved'] === '1',
        ]);
    }

    public function save(): void
    {
        AuthMiddleware::check();

        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $featured = $_POST['featured'] ?? [];

        if ($categoryId > 0) {
            $this->service->saveForCategory($categoryId, is_array($featured) ? $featured : []);
        }

        header('Location: /admin/category-featured?category_id=' . $categoryId . '&saved=1');
        exit;
    }

    /** @return int[] */
    private function padSlots(array $ids, int $size): array
    {
        $result = array_values(array_map('intval', $ids));
        while (count($result) < $size) {
            $result[] = 0;
        }

        return array_slice($result, 0, $size);
    }
}
