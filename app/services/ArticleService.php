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
                null, // Content loaded on demand
                $row['published_at'],
                $row['created_at']
            );
        }
        return $articles;
    }

    public function create(Article $article, array $author, array $category, array $tags): bool
    {
        $pg = Database::postgres();
        $mongo = Database::mongodb();
        $mongoDbName = config('database.mongodb.database');

        try {
            $pg->beginTransaction();

            // 1. Save to MongoDB first to get the ID
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

            // 2. Save to PostgreSQL metadata
            $stmt = $pg->prepare('INSERT INTO article (title, summary, mongodb_id) VALUES (?, ?, ?)');
            $stmt->execute([
                $article->title,
                $article->summary,
                (string) $mongoId
            ]);
            $articleId = (int) $pg->lastInsertId();

            // 3. Link collections (if needed by original schema)
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
            if ($pg->inTransaction()) {
                $pg->rollBack();
            }
            throw $e;
        }
    }
}
