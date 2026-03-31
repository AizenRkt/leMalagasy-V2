<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Article;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\BSON\ObjectId;
use RuntimeException;

final class ArticleService
{
    /** @return Article[] */
    public function latest(): array
    {
        $db = Database::postgres();
        $stmt = $db->query('SELECT * FROM article ORDER BY created_at DESC LIMIT 10');
        $articles = [];
        while ($row = $stmt->fetch()) {
            $articles[] = new Article(
                (int) $row['id'],
                $row['title'],
                $row['summary'],
                $row['mongodb_id'],
                null,
                $row['published_at'],
                $row['created_at']
            );
        }
        return $articles;
    }

    public function listArticles(array $filters = []): array
    {
        $db = Database::postgres();
        $query = "SELECT a.*, s.statut FROM article a 
                  LEFT JOIN article_status s ON a.id = s.id_article 
                  LEFT JOIN article_categories ac ON a.id = ac.id_article
                  WHERE 1=1";
        $params = [];

        if (!empty($filters['title'])) {
            $query .= " AND a.title ILIKE ?";
            $params[] = '%' . $filters['title'] . '%';
        }

        if (!empty($filters['status'])) {
            $query .= " AND s.statut = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $query .= " AND ac.id_category = ?";
            $params[] = (int) $filters['category_id'];
        }

        $query .= " ORDER BY a.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?Article
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT * FROM article WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $article = new Article(
            (int) $row['id'],
            $row['title'],
            $row['summary'],
            $row['mongodb_id'],
            null,
            $row['published_at'],
            $row['created_at']
        );

        // Fetch content from MongoDB
        if ($article->mongodbId) {
            $mongo = Database::mongodb();
            $dbName = config('database.mongodb.database');
            $query = new Query(['_id' => new ObjectId($article->mongodbId)]);
            $cursor = $mongo->executeQuery("$dbName.articles", $query);
            $doc = current($cursor->toArray());
            if ($doc) {
                $article->content = $doc->contenu ?? '';
            }
        }

        return $article;
    }

    public function create(Article $article, array $author, array $category, array $tags): bool
    {
        $pg = Database::postgres();
        $mongo = Database::mongodb();
        $mongoDbName = config('database.mongodb.database');

        try {
            $pg->beginTransaction();

            $bulk = new BulkWrite();
            $mongoId = new ObjectId();
            $bulk->insert([
                '_id' => $mongoId,
                'titre' => $article->title,
                'contenu' => $article->content,
                'date_publication' => date('c'),
                'auteur' => $author,
                'categorie' => $category,
                'tags' => $tags
            ]);
            $mongo->executeBulkWrite("$mongoDbName.articles", $bulk);

            $stmt = $pg->prepare('INSERT INTO article (title, summary, mongodb_id) VALUES (?, ?, ?)');
            $stmt->execute([$article->title, $article->summary, (string) $mongoId]);
            $articleId = (int) $pg->lastInsertId();

            $pg->prepare('INSERT INTO article_status (id_article, statut) VALUES (?, ?)')
               ->execute([$articleId, 'BROUILLON']);

            if (isset($author['id'])) {
                $pg->prepare('INSERT INTO article_authors (id_article, id_utilisateur) VALUES (?, ?)')
                   ->execute([$articleId, $author['id']]);
            }
            if (isset($category['id'])) {
                $pg->prepare('INSERT INTO article_categories (id_article, id_category) VALUES (?, ?)')
                   ->execute([$articleId, $category['id']]);
            }
            foreach ($tags as $tag) {
                if (isset($tag['id'])) {
                    $pg->prepare('INSERT INTO article_tags (id_article, id_tag) VALUES (?, ?)')
                       ->execute([$articleId, $tag['id']]);
                }
            }

            $pg->commit();
            return true;
        } catch (\Exception $e) {
            if ($pg->inTransaction()) $pg->rollBack();
            throw $e;
        }
    }

    public function update(Article $article, array $author, array $category, array $tags): bool
    {
        $pg = Database::postgres();
        $mongo = Database::mongodb();
        $mongoDbName = config('database.mongodb.database');

        try {
            $pg->beginTransaction();

            // 1. Update MongoDB
            if ($article->mongodbId) {
                $bulk = new BulkWrite();
                $bulk->update(
                    ['_id' => new ObjectId($article->mongodbId)],
                    ['$set' => [
                        'titre' => $article->title,
                        'contenu' => $article->content,
                        'auteur' => $author,
                        'categorie' => $category,
                        'tags' => $tags
                    ]]
                );
                $mongo->executeBulkWrite("$mongoDbName.articles", $bulk);
            }

            // 2. Update PostgreSQL metadata
            $stmt = $pg->prepare('UPDATE article SET title = ?, summary = ? WHERE id = ?');
            $stmt->execute([$article->title, $article->summary, $article->id]);

            // 3. Update relations (sync)
            $pg->prepare('DELETE FROM article_authors WHERE id_article = ?')->execute([$article->id]);
            if (isset($author['id'])) {
                $pg->prepare('INSERT INTO article_authors (id_article, id_utilisateur) VALUES (?, ?)')->execute([$article->id, $author['id']]);
            }

            $pg->prepare('DELETE FROM article_categories WHERE id_article = ?')->execute([$article->id]);
            if (isset($category['id'])) {
                $pg->prepare('INSERT INTO article_categories (id_article, id_category) VALUES (?, ?)')->execute([$article->id, $category['id']]);
            }

            $pg->prepare('DELETE FROM article_tags WHERE id_article = ?')->execute([$article->id]);
            foreach ($tags as $tag) {
                if (isset($tag['id'])) {
                    $pg->prepare('INSERT INTO article_tags (id_article, id_tag) VALUES (?, ?)')->execute([$article->id, $tag['id']]);
                }
            }

            $pg->commit();
            return true;
        } catch (\Exception $e) {
            if ($pg->inTransaction()) $pg->rollBack();
            throw $e;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        $pg = Database::postgres();
        $stmt = $pg->prepare('UPDATE article_status SET statut = ? WHERE id_article = ?');
        return $stmt->execute([$status, $id]);
    }
}
