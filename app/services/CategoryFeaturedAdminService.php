<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class CategoryFeaturedAdminService
{
    /** @return array<int, array{id: int, name: string}> */
    public function listCategories(): array
    {
        $db = Database::postgres();
        $stmt = $db->query('SELECT id, name FROM categorie ORDER BY name ASC');

        $rows = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
            ];
        }

        return $rows;
    }

    /** @return array<int, array{id: int, title: string, status: string, published_at: string|null, created_at: string}> */
    public function listCategoryArticles(int $categoryId): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT a.id, a.title, COALESCE(s.statut::text, \'BROUILLON\') AS status, a.published_at, a.created_at
             FROM article a
             INNER JOIN article_categories ac ON ac.id_article = a.id
             LEFT JOIN article_status s ON s.id_article = a.id
             WHERE ac.id_category = ?
             ORDER BY COALESCE(a.published_at, a.created_at) DESC
             LIMIT 300'
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => (string) ($row['title'] ?? ''),
                'status' => (string) ($row['status'] ?? 'BROUILLON'),
                'published_at' => isset($row['published_at']) ? (string) $row['published_at'] : null,
                'created_at' => (string) ($row['created_at'] ?? ''),
            ];
        }

        return $rows;
    }

    /** @return int[] */
    public function getCurrentFeaturedIds(int $categoryId): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT id_article
             FROM category_featured_articles
             WHERE id_category = ?
               AND is_active = TRUE
             ORDER BY display_order ASC, id ASC'
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
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

    /**
     * @param int[] $featuredIds
     */
    public function saveForCategory(int $categoryId, array $featuredIds): void
    {
        $db = Database::postgres();

        $inputIds = array_values(array_filter(array_map('intval', $featuredIds), fn (int $id): bool => $id > 0));
        $inputIds = array_slice($inputIds, 0, 3);

        // Security rule: keep only articles that actually belong to this category.
        $allowedIds = $this->filterIdsByCategory($categoryId, $inputIds);

        $db->beginTransaction();
        try {
            $delete = $db->prepare('DELETE FROM category_featured_articles WHERE id_category = ?');
            $delete->execute([$categoryId]);

            $insert = $db->prepare(
                'INSERT INTO category_featured_articles (id_category, id_article, display_order, is_active)
                 VALUES (?, ?, ?, TRUE)'
            );

            foreach ($allowedIds as $index => $idArticle) {
                $insert->execute([$categoryId, $idArticle, $index + 1]);
            }

            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param int[] $ids
     * @return int[]
     */
    private function filterIdsByCategory(int $categoryId, array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $db = Database::postgres();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare(
            'SELECT ac.id_article
             FROM article_categories ac
             WHERE ac.id_category = ?
               AND ac.id_article IN (' . $placeholders . ')
             ORDER BY ac.id_article ASC'
        );

        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 2, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $allowed = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $allowed[] = (int) ($row['id_article'] ?? 0);
        }

        // Preserve user-selected order while keeping only allowed IDs.
        $allowedMap = array_fill_keys($allowed, true);
        $ordered = [];
        foreach ($ids as $id) {
            if (isset($allowedMap[$id])) {
                $ordered[] = $id;
            }
        }

        return array_values(array_unique($ordered));
    }
}
