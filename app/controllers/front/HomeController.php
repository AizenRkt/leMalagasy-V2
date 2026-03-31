<?php

declare(strict_types=1);

namespace App\Controllers\Front;

use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\HomeFeedService;

final class HomeController
{
    public function index(): string
    {
        $service = new HomeFeedService();
        $feedData = $service->getHomeFeedData();

        return view('front/home', [
            'title' => 'Accueil',
            'seo' => [
                'title' => 'Accueil',
                'description' => 'Consultez les dernieres actualites et analyses de la redaction Le Malagasy.',
                'canonical' => absolute_url('/'),
                'type' => 'website',
            ],
            'featuredArticle' => $feedData['featuredArticle'],
            'latestArticles' => $feedData['latestArticles'],
            'spotlightArticles' => $feedData['spotlightArticles'],
        ] + $this->frontCommonData());
    }

    public function singleArticle(): string
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            if (is_string($path) && preg_match('/-([0-9]+)\.html$/', $path, $matches)) {
                $id = (int) ($matches[1] ?? 0);
            }
        }

        $service = new ArticleService();
        $articleData = $service->getFrontArticle(($id !== false && $id !== null) ? (int) $id : null);

        if ($articleData === null) {
            http_response_code(404);
            return view('errors/404', ['uri' => '/article']);
        }

        $relatedArticles = $service->getRelatedTitles((int) $articleData['id'], 4);
        $articleTitle = (string) ($articleData['title'] ?? 'Article');
        $articleId = (int) ($articleData['id'] ?? 0);
        $canonical = article_url($articleTitle, $articleId);

        $descriptionSource = (string) ($articleData['standfirst'] ?? '');
        if ($descriptionSource === '') {
            $descriptionSource = (string) ($articleData['contentHtml'] ?? '');
        }

        return view('front/singleArticle', [
            'title' => $articleTitle,
            'seo' => [
                'title' => $articleTitle,
                'description' => seo_description($descriptionSource),
                'canonical' => absolute_url($canonical),
                'type' => 'article',
                'image' => (string) ($articleData['heroImage'] ?? ''),
            ],
            'articleData' => $articleData,
            'relatedArticles' => $relatedArticles,
        ] + $this->frontCommonData());
    }

    public function singleCategory(): string
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $service = new CategoryService();
        $categoryData = $service->getCategoryPageData($id !== false ? $id : null);

        if ($categoryData === null) {
            http_response_code(404);
            return view('errors/404', ['uri' => '/category']);
        }

        $categoryName = (string) ($categoryData['name'] ?? 'Categorie');
        $categoryId = (int) ($categoryData['id'] ?? 0);
        $canonical = category_url($categoryName, $categoryId);
        $description = 'Retrouvez tous les articles de la categorie ' . $categoryName . ' sur Le Malagasy.';

        return view('front/singleCategory', [
            'title' => 'Categorie: ' . $categoryName,
            'seo' => [
                'title' => 'Categorie: ' . $categoryName,
                'description' => seo_description($description),
                'canonical' => absolute_url($canonical),
                'type' => 'website',
            ],
            'categoryData' => $categoryData,
            'featuredArticles' => $categoryData['featuredArticles'] ?? [],
            'categoryArticles' => $categoryData['categoryArticles'] ?? [],
        ] + $this->frontCommonData());
    }

    /** @return array{menuItems: array<int, array{label: string, href: string}>} */
    private function frontCommonData(): array
    {
        $categoryService = new CategoryService();
        $menuItems = [];

        foreach ($categoryService->listForMenu(8) as $category) {
            $menuItems[] = [
                'label' => $category->name,
                'href' => category_url((string) $category->name, (int) $category->id),
            ];
        }

        return ['menuItems' => $menuItems];
    }

    // /** @return array<string, array{ok: bool, message: string}> */
    // private function checkDatabases(): array
    // {
    //     $status = [
    //         'postgres' => ['ok' => false, 'message' => 'Non teste'],
    //         'mongodb' => ['ok' => false, 'message' => 'Non teste'],
    //     ];

    //     try {
    //         $pdo = Database::postgres();
    //         $value = $pdo->query('SELECT 1')->fetchColumn();
    //         $status['postgres'] = [
    //             'ok' => ((string) $value === '1'),
    //             'message' => ((string) $value === '1') ? 'Connexion OK' : 'Reponse inattendue',
    //         ];
    //     } catch (Throwable $e) {
    //         $status['postgres'] = ['ok' => false, 'message' => $e->getMessage()];
    //     }

    //     try {
    //         $manager = Database::mongodb();
    //         $commandClass = 'MongoDB\\Driver\\Command';

    //         if (!class_exists($commandClass)) {
    //             throw new \RuntimeException('MongoDB extension command class missing.');
    //         }

    //         $command = new $commandClass(['ping' => 1]);
    //         $cursor = $manager->executeCommand('admin', $command);
    //         $result = current($cursor->toArray());

    //         $ok = is_object($result) && isset($result->ok) && (float) $result->ok === 1.0;
    //         $status['mongodb'] = [
    //             'ok' => $ok,
    //             'message' => $ok ? 'Connexion OK' : 'Ping MongoDB invalide',
    //         ];
    //     } catch (Throwable $e) {
    //         $status['mongodb'] = ['ok' => false, 'message' => $e->getMessage()];
    //     }

    //     return $status;
    // }
}
