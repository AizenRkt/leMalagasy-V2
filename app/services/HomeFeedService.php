<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class HomeFeedService
{
    /**
     * @return array{
     *   featuredArticle: array<string, mixed>|null,
     *   latestArticles: array<int, array<string, mixed>>,
     *   spotlightArticles: array<int, array<string, mixed>>
     * }
     */
    public function getHomeFeedData(): array
    {
        $configured = $this->configuredFeedSlots();
        $usedIds = [];

        $featuredId = $configured['FEATURED'][0] ?? null;
        if ($featuredId !== null) {
            $usedIds[$featuredId] = true;
        }

        $latestIds = $this->takeUnique($configured['LATEST'], 3, $usedIds);
        $spotlightIds = $this->takeUnique($configured['SPOTLIGHT'], 4, $usedIds);

        $fallbackIds = $this->latestPublishedArticleIds(20);

        if ($featuredId === null) {
            $featuredId = $this->takeFirstMissing($fallbackIds, $usedIds);
            if ($featuredId !== null) {
                $usedIds[$featuredId] = true;
            }
        }

        $latestIds = $this->fillWithFallback($latestIds, 3, $fallbackIds, $usedIds);
        $spotlightIds = $this->fillWithFallback($spotlightIds, 4, $fallbackIds, $usedIds);

        $articleService = new ArticleService();

        return [
            'featuredArticle' => $this->mapIdToCard($articleService, $featuredId),
            'latestArticles' => $this->mapManyIdsToCards($articleService, $latestIds),
            'spotlightArticles' => $this->mapManyIdsToCards($articleService, $spotlightIds),
        ];
    }

    /** @return array{FEATURED: int[], LATEST: int[], SPOTLIGHT: int[]} */
    private function configuredFeedSlots(): array
    {
        $db = Database::postgres();
        $query = <<<'SQL'
            SELECT hf.slot, hf.id_article
            FROM home_feed hf
            WHERE hf.is_active = TRUE
              AND (hf.starts_at IS NULL OR hf.starts_at <= NOW())
              AND (hf.ends_at IS NULL OR hf.ends_at >= NOW())
            ORDER BY hf.slot, hf.display_order ASC, hf.id ASC
        SQL;

        $slots = [
            'FEATURED' => [],
            'LATEST' => [],
            'SPOTLIGHT' => [],
        ];

        $stmt = $db->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $slot = strtoupper((string) ($row['slot'] ?? ''));
            $articleId = (int) ($row['id_article'] ?? 0);

            if ($articleId <= 0 || !isset($slots[$slot])) {
                continue;
            }

            $slots[$slot][] = $articleId;
        }

        return $slots;
    }

    /** @return int[] */
    private function latestPublishedArticleIds(int $limit): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT a.id
             FROM article a
             LEFT JOIN article_status s ON s.id_article = a.id
             WHERE s.statut IS NULL OR s.statut = ?
             ORDER BY COALESCE(a.published_at, a.created_at) DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, 'PUBLIE');
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
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

    /**
     * @param int[] $ids
     * @param array<int, bool> $usedIds
     * @return int[]
     */
    private function takeUnique(array $ids, int $limit, array &$usedIds): array
    {
        $result = [];
        foreach ($ids as $id) {
            if (isset($usedIds[$id])) {
                continue;
            }

            $result[] = $id;
            $usedIds[$id] = true;

            if (count($result) >= $limit) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param int[] $current
     * @param int[] $fallbackIds
     * @param array<int, bool> $usedIds
     * @return int[]
     */
    private function fillWithFallback(array $current, int $limit, array $fallbackIds, array &$usedIds): array
    {
        if (count($current) >= $limit) {
            return $current;
        }

        foreach ($fallbackIds as $id) {
            if (isset($usedIds[$id])) {
                continue;
            }

            $current[] = $id;
            $usedIds[$id] = true;

            if (count($current) >= $limit) {
                break;
            }
        }

        return $current;
    }

    /**
     * @param int[] $ids
     * @return array<int, array<string, mixed>>
     */
    private function mapManyIdsToCards(ArticleService $articleService, array $ids): array
    {
        $cards = [];
        foreach ($ids as $id) {
            $card = $this->mapIdToCard($articleService, $id);
            if ($card !== null) {
                $cards[] = $card;
            }
        }

        return $cards;
    }

    /** @return array<string, mixed>|null */
    private function mapIdToCard(ArticleService $articleService, ?int $id): ?array
    {
        if ($id === null || $id <= 0) {
            return null;
        }

        $article = $articleService->getFrontArticle($id);
        if ($article === null) {
            return null;
        }

        return [
            'id' => (int) ($article['id'] ?? $id),
            'category' => (string) ($article['category'] ?? 'Actualites'),
            'title' => (string) ($article['title'] ?? 'Article'),
            'excerpt' => (string) ($article['standfirst'] ?? ''),
            'author' => (string) ($article['author'] ?? 'Redaction'),
            'publishedAt' => (string) ($article['publishedAt'] ?? date(DATE_ATOM)),
            'readingTime' => (string) ($article['readingTime'] ?? '4 min'),
            'image' => $article['heroImage'] ?? null,
            'href' => article_url((string) ($article['title'] ?? 'Article'), (int) ($article['id'] ?? $id)),
        ];
    }

    /**
     * @param int[] $ids
     * @param array<int, bool> $usedIds
     */
    private function takeFirstMissing(array $ids, array $usedIds): ?int
    {
        foreach ($ids as $id) {
            if (!isset($usedIds[$id])) {
                return $id;
            }
        }

        return null;
    }
}
