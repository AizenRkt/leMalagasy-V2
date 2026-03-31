<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Services\HomeFeedAdminService;

final class HomeFeedController
{
    private HomeFeedAdminService $service;

    public function __construct()
    {
        $this->service = new HomeFeedAdminService();
    }

    public function index(): string
    {
        AuthMiddleware::check();

        $articles = $this->service->listArticlesForSelection();
        $slots = $this->service->getCurrentSlots();

        return view('admin/feed/index', [
            'title' => 'Configuration du feed Home',
            'articles' => $articles,
            'featured' => $slots['FEATURED'][0] ?? 0,
            'latest' => $this->padSlots($slots['LATEST'], 3),
            'spotlight' => $this->padSlots($slots['SPOTLIGHT'], 4),
            'saved' => isset($_GET['saved']) && $_GET['saved'] === '1',
        ]);
    }

    public function save(): void
    {
        AuthMiddleware::check();

        $featured = (int) ($_POST['featured'] ?? 0);
        $latest = $_POST['latest'] ?? [];
        $spotlight = $_POST['spotlight'] ?? [];

        $this->service->saveSlots([
            'featured' => $featured,
            'latest' => is_array($latest) ? $latest : [],
            'spotlight' => is_array($spotlight) ? $spotlight : [],
        ]);

        header('Location: /admin/feed?saved=1');
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
