<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Category;
use PDO;

final class CategoryService
{
    /** @return Category[] */
    public function listForMenu(int $limit = 8): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT id, name FROM categorie ORDER BY name ASC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category((int) $row['id'], (string) $row['name']);
        }

        return $categories;
    }

    public function getById(int $id): ?Category
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT id, name FROM categorie WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Category((int) $row['id'], (string) $row['name']) : null;
    }

    /**
     * @return array{
     *   id: int,
     *   name: string,
     *   tags: string[],
     *   featuredArticles: array<int, array{id: int, title: string, image: ?string, publishedAt: string}>,
     *   categoryArticles: array<int, array{id: int, title: string, excerpt: string, author: string, publishedAt: string, readingTime: string}>
     * }|null
     */
    public function getCategoryPageData(?int $categoryId): ?array
    {
        $db = Database::postgres();

        $category = null;
        if ($categoryId !== null && $categoryId > 0) {
            $category = $this->getById($categoryId);
        }

        if ($category === null) {
            $stmt = $db->query('SELECT id, name FROM categorie ORDER BY name ASC LIMIT 1');
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            $category = new Category((int) $row['id'], (string) $row['name']);
        }

        $featuredIds = $this->listFeaturedArticleIds($category->id, 3);
        if ($featuredIds === []) {
            $featuredIds = $this->listCategoryArticleIds($category->id, 3);
        }

        $listIds = $this->listCategoryArticleIds($category->id, 24);

        $articleService = new ArticleService();

        $featuredArticles = [];
        foreach ($featuredIds as $articleId) {
            $article = $articleService->getFrontArticle($articleId);
            if ($article === null) {
                continue;
            }

            $featuredArticles[] = [
                'id' => (int) ($article['id'] ?? $articleId),
                'title' => (string) ($article['title'] ?? ''),
                'image' => isset($article['heroImage']) && is_string($article['heroImage']) && $article['heroImage'] !== ''
                    ? $article['heroImage']
                    : null,
                'publishedAt' => (string) ($article['publishedAt'] ?? ''),
            ];
        }

        $categoryArticles = [];
        foreach ($listIds as $articleId) {
            $article = $articleService->getFrontArticle($articleId);
            if ($article === null) {
                continue;
            }

            $categoryArticles[] = [
                'id' => (int) ($article['id'] ?? $articleId),
                'title' => (string) ($article['title'] ?? ''),
                'excerpt' => (string) ($article['standfirst'] ?? ''),
                'author' => (string) ($article['author'] ?? 'Redaction'),
                'publishedAt' => (string) ($article['publishedAt'] ?? ''),
                'readingTime' => (string) ($article['readingTime'] ?? '4 min'),
            ];
        }

        $tags = $this->listCategoryTags($category->id, 12);

        return [
            'id' => $category->id,
            'name' => $category->name,
            'tags' => $tags,
            'featuredArticles' => $featuredArticles,
            'categoryArticles' => $categoryArticles,
        ];
    }

    /** @return int[] */
    private function listCategoryArticleIds(int $categoryId, int $limit): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT a.id
             FROM article a
             INNER JOIN article_categories ac ON ac.id_article = a.id
             LEFT JOIN article_status s ON s.id_article = a.id
             WHERE ac.id_category = ?
               AND (s.statut IS NULL OR s.statut = ?)
             ORDER BY COALESCE(a.published_at, a.created_at) DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, 'PUBLIE');
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $ids = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = (int) ($row['id'] ?? 0);
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    /** @return int[] */
    private function listFeaturedArticleIds(int $categoryId, int $limit): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT cfa.id_article
             FROM category_featured_articles cfa
             INNER JOIN article_categories ac ON ac.id_article = cfa.id_article
             LEFT JOIN article_status s ON s.id_article = cfa.id_article
             WHERE cfa.id_category = ?
               AND cfa.is_active = TRUE
               AND ac.id_category = ?
               AND (s.statut IS NULL OR s.statut = ?)
             ORDER BY cfa.display_order ASC, cfa.id ASC
             LIMIT ?'
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(3, 'PUBLIE');
        $stmt->bindValue(4, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $ids = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = (int) ($row['id_article'] ?? 0);
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    /** @return string[] */
    private function listCategoryTags(int $categoryId, int $limit): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT DISTINCT t.name
             FROM tag t
             INNER JOIN article_tags at ON at.id_tag = t.id
             INNER JOIN article_categories ac ON ac.id_article = at.id_article
             WHERE ac.id_category = ?
             ORDER BY t.name ASC
             LIMIT ?'
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name !== '') {
                $tags[] = $name;
            }
        }

        return $tags;
    }
}
