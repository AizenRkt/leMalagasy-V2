<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class HomeFeedAdminService
{
    /** @return array<int, array{id: int, title: string, status: string, published_at: string|null, created_at: string}> */
    public function listArticlesForSelection(): array
    {
        $db = Database::postgres();
        $sql = <<<'SQL'
            SELECT a.id, a.title, COALESCE(s.statut::text, 'BROUILLON') AS status, a.published_at, a.created_at
            FROM article a
            LEFT JOIN article_status s ON s.id_article = a.id
            ORDER BY COALESCE(a.published_at, a.created_at) DESC
            LIMIT 300
        SQL;

        $stmt = $db->query($sql);
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

    /**
     * @return array{FEATURED: int[], LATEST: int[], SPOTLIGHT: int[]}
     */
    public function getCurrentSlots(): array
    {
        $db = Database::postgres();
        $sql = <<<'SQL'
            SELECT slot, id_article
            FROM home_feed
            WHERE is_active = TRUE
            ORDER BY slot, display_order ASC, id ASC
        SQL;

        $slots = [
            'FEATURED' => [],
            'LATEST' => [],
            'SPOTLIGHT' => [],
        ];

        $stmt = $db->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $slot = strtoupper((string) ($row['slot'] ?? ''));
            $idArticle = (int) ($row['id_article'] ?? 0);
            if ($idArticle > 0 && isset($slots[$slot])) {
                $slots[$slot][] = $idArticle;
            }
        }

        return $slots;
    }

    /**
     * @param array{featured: int, latest: int[], spotlight: int[]} $payload
     */
    public function saveSlots(array $payload): void
    {
        $db = Database::postgres();

        $featured = $payload['featured'] > 0 ? [$payload['featured']] : [];
        $latest = array_values(array_filter(array_map('intval', $payload['latest']), fn (int $id): bool => $id > 0));
        $spotlight = array_values(array_filter(array_map('intval', $payload['spotlight']), fn (int $id): bool => $id > 0));

        $latest = array_slice($latest, 0, 3);
        $spotlight = array_slice($spotlight, 0, 4);

        $db->beginTransaction();
        try {
            $db->exec('DELETE FROM home_feed');

            $insert = $db->prepare(
                'INSERT INTO home_feed (id_article, slot, display_order, is_active, starts_at, ends_at) VALUES (?, ?, ?, TRUE, NULL, NULL)'
            );

            foreach ($featured as $index => $idArticle) {
                $insert->execute([$idArticle, 'FEATURED', $index + 1]);
            }

            foreach ($latest as $index => $idArticle) {
                $insert->execute([$idArticle, 'LATEST', $index + 1]);
            }

            foreach ($spotlight as $index => $idArticle) {
                $insert->execute([$idArticle, 'SPOTLIGHT', $index + 1]);
            }

            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }
}
