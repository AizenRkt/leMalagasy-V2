<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Models\Article;
use App\Services\ArticleService;
use App\Middlewares\AuthMiddleware;
use RuntimeException;

final class ArticleController
{
    private ArticleService $articleService;

    public function __construct()
    {
        $this->articleService = new ArticleService();
    }

    public function create(): string
    {
        AuthMiddleware::check();

        $db = Database::postgres();
        $categories = $db->query('SELECT * FROM categorie ORDER BY name')->fetchAll();
        $tags = $db->query('SELECT * FROM tag ORDER BY name')->fetchAll();
        $users = $db->query('SELECT * FROM utilisateur ORDER BY name')->fetchAll();

        return view('admin/articles/create', [
            'title' => 'Rédiger une actualité',
            'categories' => $categories,
            'tags' => $tags,
            'users' => $users,
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::check();

        $title = $_POST['title'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $userId = (int) ($_POST['user_id'] ?? 0);
        $tagIds = $_POST['tag_ids'] ?? [];

        if (empty($title) || empty($content)) {
            throw new RuntimeException('Titre et contenu sont obligatoires.');
        }

        // Fetch category and author full details for MongoDB
        $db = Database::postgres();
        $category = $db->prepare('SELECT id, name AS nom FROM categorie WHERE id = ?');
        $category->execute([$categoryId]);
        $categoryData = $category->fetch() ?: [];

        $author = $db->prepare('SELECT id, name AS nom FROM utilisateur WHERE id = ?');
        $author->execute([$userId]);
        $authorData = $author->fetch() ?: [];

        $tagsData = [];
        if (!empty($tagIds)) {
            $placeholders = implode(',', array_fill(0, count($tagIds), '?'));
            $tagsStmt = $db->prepare("SELECT id, name AS nom FROM tag WHERE id IN ($placeholders)");
            $tagsStmt->execute($tagIds);
            $tagsData = $tagsStmt->fetchAll();
        }

        $article = new Article(null, $title, $summary, null, $content);

        $success = $this->articleService->create($article, $authorData, $categoryData, $tagsData);

        if ($success) {
            header('Location: /admin/dashboard'); // Redirect to dashboard or list
            exit;
        } else {
            throw new RuntimeException('Erreur lors de la création de l\'article.');
        }
    }
}
