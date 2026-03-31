<?php

declare(strict_types=1);

namespace App\Controllers\Front;

use App\Services\ArticleService;
use App\Services\CategoryService;

final class HomeController
{
    public function index(): string
    {
        $service = new ArticleService();
        $articles = $service->latest();

        return view('front/home', [
            'title' => 'Accueil',
            'articles' => $articles,
        ] + $this->frontCommonData());
    }

    public function about(): string
    {
        return view('front/about', [
            'title' => 'A propos',
        ] + $this->frontCommonData());
    }

    public function singleArticle(): string
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $service = new ArticleService();
        $articleData = $service->getFrontArticle($id !== false ? $id : null);

        if ($articleData === null) {
            http_response_code(404);
            return view('errors/404', ['uri' => '/article']);
        }

        $relatedArticles = $service->getRelatedTitles((int) $articleData['id'], 4);

        return view('front/singleArticle', [
            'title' => (string) ($articleData['title'] ?? 'Article'),
            'articleData' => $articleData,
            'relatedArticles' => $relatedArticles,
        ] + $this->frontCommonData());
    }

    public function singleCategory(): string
    {
        return view('front/singleCategory', [
            'title' => 'Categorie unique',
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
                'href' => '/category?id=' . (string) $category->id,
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
