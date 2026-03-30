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

    public function index(): string
    {
        AuthMiddleware::check();

        $filters = [
            'title' => $_GET['title'] ?? null,
            'status' => $_GET['status'] ?? null,
        ];

        $articles = $this->articleService->listArticles($filters);

        return view('admin/articles/index', [
            'title' => 'Gestion des actualités',
            'articles' => $articles,
            'filters' => $filters,
        ]);
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

    public function edit(): string
    {
        AuthMiddleware::check();
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) throw new RuntimeException('ID d\'article manquant.');

        $article = $this->articleService->getById($id);
        if (!$article) throw new RuntimeException('Article non trouvé.');

        $db = Database::postgres();
        $categories = $db->query('SELECT * FROM categorie ORDER BY name')->fetchAll();
        $tags = $db->query('SELECT * FROM tag ORDER BY name')->fetchAll();
        $users = $db->query('SELECT * FROM utilisateur ORDER BY name')->fetchAll();

        // Get current selections
        $currentCat = $db->prepare('SELECT id_category FROM article_categories WHERE id_article = ?');
        $currentCat->execute([$id]);
        $currentCategoryId = $currentCat->fetchColumn();

        $currentAuth = $db->prepare('SELECT id_utilisateur FROM article_authors WHERE id_article = ?');
        $currentAuth->execute([$id]);
        $currentUserId = $currentAuth->fetchColumn();

        $currentT = $db->prepare('SELECT id_tag FROM article_tags WHERE id_article = ?');
        $currentT->execute([$id]);
        $currentTagIds = $currentT->fetchAll(\PDO::FETCH_COLUMN);

        return view('admin/articles/edit', [
            'title' => 'Modifier une actualité',
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags,
            'users' => $users,
            'currentCategoryId' => $currentCategoryId,
            'currentUserId' => $currentUserId,
            'currentTagIds' => $currentTagIds,
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::check();

        $data = $this->extractPostData();
        $article = new Article(null, $data['title'], $data['summary'], null, $data['content']);

        $success = $this->articleService->create($article, $data['author'], $data['category'], $data['tags']);

        if ($success) {
            header('Location: /admin/articles');
            exit;
        } else {
            throw new RuntimeException('Erreur lors de la création de l\'article.');
        }
    }

    public function update(): void
    {
        AuthMiddleware::check();
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if (!$id) throw new RuntimeException('ID d\'article manquant.');

        $article = $this->articleService->getById($id);
        if (!$article) throw new RuntimeException('Article non trouvé.');

        $data = $this->extractPostData();
        $article->title = $data['title'];
        $article->summary = $data['summary'];
        $article->content = $data['content'];

        $success = $this->articleService->update($article, $data['author'], $data['category'], $data['tags']);

        if ($success) {
            header('Location: /admin/articles');
            exit;
        } else {
            throw new RuntimeException('Erreur lors de la mise à jour.');
        }
    }

    public function changeStatus(): void
    {
        AuthMiddleware::check();

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!$id || empty($status)) throw new RuntimeException('Données invalides.');

        $this->articleService->updateStatus($id, $status);
        header('Location: /admin/articles');
        exit;
    }

    private function extractPostData(): array
    {
        $db = Database::postgres();
        $title = $_POST['title'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $userId = (int) ($_POST['user_id'] ?? 0);
        $tagIds = $_POST['tag_ids'] ?? [];

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

        return [
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'author' => $authorData,
            'category' => $categoryData,
            'tags' => $tagsData,
        ];
    }
}
