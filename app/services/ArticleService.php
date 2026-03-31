<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Article;
use PDO;

final class ArticleService
{
    private const MAX_IMAGE_DIMENSION = 1920;
    private const MAX_IMAGE_BYTES = 900000;

    /**
     * Returns a front-ready article payload by id or the latest article when id is null.
     *
     * @return array<string, mixed>|null
     */
    public function getFrontArticle(?int $id = null): ?array
    {
        $db = Database::postgres();

        if ($id === null) {
            $stmt = $db->query('SELECT id FROM article ORDER BY COALESCE(published_at, created_at) DESC LIMIT 1');
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            $id = (int) $row['id'];
        }

        $stmt = $db->prepare('SELECT * FROM article WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $data = [
            'id' => (int) $row['id'],
            'category' => 'Actualites',
            'tags' => [],
            'title' => (string) ($row['title'] ?? ''),
            'standfirst' => (string) ($row['summary'] ?? ''),
            'author' => 'Redaction',
            'publishedAt' => (string) (($row['published_at'] ?? '') !== '' ? $row['published_at'] : ($row['created_at'] ?? '')),
            'readingTime' => '4 min',
            'heroImage' => null,
            'heroCaption' => '',
            'contentHtml' => '',
        ];

        if (!empty($row['mongodb_id'])) {
            $mongo = Database::mongodb();
            $dbName = (string) config('database.mongodb.database', 'lemalagasy_db');
            $queryClass = 'MongoDB\\Driver\\Query';
            $objectIdClass = 'MongoDB\\BSON\\ObjectId';
            if (!class_exists($queryClass) || !class_exists($objectIdClass)) {
                return $data;
            }

            $query = new $queryClass(['_id' => new $objectIdClass((string) $row['mongodb_id'])]);
            $cursor = $mongo->executeQuery($dbName . '.articles', $query);
            $doc = current($cursor->toArray());

            if ($doc) {
                $data['title'] = (string) ($doc->titre ?? $data['title']);
                $data['contentHtml'] = (string) ($doc->contenu ?? '');
                $data['standfirst'] = $data['standfirst'] !== ''
                    ? $data['standfirst']
                    : $this->extractStandfirst($data['contentHtml']);
                $data['publishedAt'] = (string) ($doc->date_publication ?? $data['publishedAt']);
                $data['author'] = (string) ($doc->auteur->nom ?? $data['author']);
                $data['category'] = (string) ($doc->categorie->nom ?? $data['category']);
                $data['tags'] = $this->extractTagNames($doc->tags ?? []);
                $data['heroImage'] = $this->extractFirstImageSource($data['contentHtml']);
                $data['readingTime'] = $this->estimateReadingTime($data['contentHtml']);
            }
        }

        return $data;
    }

    /** @return string[] */
    public function getRelatedTitles(int $excludeId, int $limit = 4): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT title FROM article WHERE id <> ? ORDER BY COALESCE(published_at, created_at) DESC LIMIT ?');
        $stmt->bindValue(1, $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $titles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $titles[] = (string) $row['title'];
        }

        return $titles;
    }

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
            $queryClass = 'MongoDB\\Driver\\Query';
            $objectIdClass = 'MongoDB\\BSON\\ObjectId';
            if (!class_exists($queryClass) || !class_exists($objectIdClass)) {
                return $article;
            }

            $query = new $queryClass(['_id' => new $objectIdClass($article->mongodbId)]);
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

            $bulkWriteClass = 'MongoDB\\Driver\\BulkWrite';
            $objectIdClass = 'MongoDB\\BSON\\ObjectId';
            if (!class_exists($bulkWriteClass) || !class_exists($objectIdClass)) {
                throw new \RuntimeException('MongoDB classes are unavailable in current runtime.');
            }

            $normalizedContent = $this->persistContentImages((string) ($article->content ?? ''));

            $bulk = new $bulkWriteClass();
            $mongoId = new $objectIdClass();
            $bulk->insert([
                '_id' => $mongoId,
                'titre' => $article->title,
                'contenu' => $normalizedContent,
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
            $normalizedContent = $this->persistContentImages((string) ($article->content ?? ''));
            $article->content = $normalizedContent;

            // 1. Update MongoDB
            if ($article->mongodbId) {
                $bulkWriteClass = 'MongoDB\\Driver\\BulkWrite';
                $objectIdClass = 'MongoDB\\BSON\\ObjectId';
                if (!class_exists($bulkWriteClass) || !class_exists($objectIdClass)) {
                    throw new \RuntimeException('MongoDB classes are unavailable in current runtime.');
                }

                $bulk = new $bulkWriteClass();
                $bulk->update(
                    ['_id' => new $objectIdClass($article->mongodbId)],
                    ['$set' => [
                        'titre' => $article->title,
                        'contenu' => $normalizedContent,
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

    /** @return string[] */
    private function extractTagNames(mixed $tags): array
    {
        if (!is_array($tags)) {
            return [];
        }

        $result = [];
        foreach ($tags as $tag) {
            if (is_object($tag) && isset($tag->nom)) {
                $result[] = (string) $tag->nom;
            }
        }

        return $result;
    }

    private function extractFirstImageSource(string $contentHtml): ?string
    {
        if ($contentHtml === '') {
            return null;
        }

        if (!preg_match('/<img[^>]+src="([^"]+)"/i', $contentHtml, $matches)) {
            return null;
        }

        $src = (string) ($matches[1] ?? '');
        if ($src === '' || str_starts_with($src, 'data:')) {
            return null;
        }

        return $src;
    }

    private function extractStandfirst(string $contentHtml): string
    {
        $text = trim(strip_tags($contentHtml));
        if ($text === '') {
            return '';
        }

        return mb_substr($text, 0, 220);
    }

    private function estimateReadingTime(string $contentHtml): string
    {
        $words = str_word_count(strip_tags($contentHtml));
        $minutes = max(1, (int) ceil($words / 200));

        return (string) $minutes . ' min';
    }

    private function persistContentImages(string $contentHtml): string
    {
        if ($contentHtml === '' || stripos($contentHtml, 'data:image/') === false) {
            return $contentHtml;
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $prevUseInternal = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contentHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        /** @var \DOMNodeList<\DOMElement> $images */
        $images = $dom->getElementsByTagName('img');
        $uploadsDir = base_path('storage/uploads');
        $baseUrl = rtrim((string) config('app.base_url', ''), '/');
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0775, true);
        }

        foreach ($images as $img) {
            if (!($img instanceof \DOMElement)) {
                continue;
            }

            $src = (string) $img->getAttribute('src');
            if (!str_starts_with($src, 'data:image/')) {
                continue;
            }

            if (!preg_match('#^data:image/([a-zA-Z0-9.+-]+);base64,(.*)$#s', $src, $matches)) {
                continue;
            }

            $mimeExt = strtolower((string) ($matches[1] ?? 'jpg'));
            $rawData = base64_decode((string) ($matches[2] ?? ''), true);
            if ($rawData === false) {
                continue;
            }

            $optimized = $this->optimizeImageBinary($rawData, $mimeExt);
            if ($optimized === null) {
                continue;
            }

            $rawData = $optimized['binary'];
            $extension = $optimized['extension'];

            $filename = sprintf('article_%s_%s.%s', date('Ymd_His'), bin2hex(random_bytes(6)), $extension);
            $fullPath = $uploadsDir . DIRECTORY_SEPARATOR . $filename;

            if (file_put_contents($fullPath, $rawData) === false) {
                continue;
            }

            $relativePath = '/storage/uploads/' . $filename;
            $publicPath = $baseUrl !== '' ? $baseUrl . $relativePath : $relativePath;
            $img->setAttribute('src', $publicPath);
        }

        $updatedHtml = $dom->saveHTML() ?: $contentHtml;
        libxml_clear_errors();
        libxml_use_internal_errors($prevUseInternal);

        return $updatedHtml;
    }

    /**
     * @return array{binary: string, extension: string}|null
     */
    private function optimizeImageBinary(string $rawData, string $mimeExt): ?array
    {
        $extensionMap = [
            'jpeg' => 'jpg',
            'jpg' => 'jpg',
            'png' => 'png',
            'gif' => 'gif',
            'webp' => 'webp',
        ];
        $fallbackExtension = $extensionMap[$mimeExt] ?? 'jpg';

        if (!function_exists('imagecreatefromstring')) {
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        $source = @imagecreatefromstring($rawData);
        if ($source === false) {
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);
        if ($srcWidth <= 0 || $srcHeight <= 0) {
            imagedestroy($source);
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        $targetWidth = $srcWidth;
        $targetHeight = $srcHeight;
        $maxDimension = self::MAX_IMAGE_DIMENSION;
        if (max($srcWidth, $srcHeight) > $maxDimension) {
            $scale = $maxDimension / max($srcWidth, $srcHeight);
            $targetWidth = max(1, (int) round($srcWidth * $scale));
            $targetHeight = max(1, (int) round($srcHeight * $scale));
        }

        $working = imagecreatetruecolor($targetWidth, $targetHeight);
        if ($working === false) {
            imagedestroy($source);
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        // Keep alpha channel for transparent formats.
        imagealphablending($working, false);
        imagesavealpha($working, true);
        $transparent = imagecolorallocatealpha($working, 0, 0, 0, 127);
        imagefilledrectangle($working, 0, 0, $targetWidth, $targetHeight, $transparent);

        if (!imagecopyresampled($working, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight)) {
            imagedestroy($source);
            imagedestroy($working);
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        imagedestroy($source);

        $maxBytes = self::MAX_IMAGE_BYTES;
        $binary = null;
        $extension = 'jpg';

        if (function_exists('imagewebp')) {
            foreach ([82, 74, 66, 58, 50] as $quality) {
                $candidate = $this->encodeImageResource($working, 'webp', $quality);
                if ($candidate === null) {
                    continue;
                }

                $binary = $candidate;
                $extension = 'webp';
                if (strlen($candidate) <= $maxBytes) {
                    break;
                }
            }
        }

        if ($binary === null && function_exists('imagejpeg')) {
            foreach ([82, 74, 66, 58, 50] as $quality) {
                $candidate = $this->encodeImageResource($working, 'jpg', $quality);
                if ($candidate === null) {
                    continue;
                }

                $binary = $candidate;
                $extension = 'jpg';
                if (strlen($candidate) <= $maxBytes) {
                    break;
                }
            }
        }

        if ($binary === null && function_exists('imagepng')) {
            foreach ([6, 8, 9] as $compressionLevel) {
                $candidate = $this->encodeImageResource($working, 'png', $compressionLevel);
                if ($candidate === null) {
                    continue;
                }

                $binary = $candidate;
                $extension = 'png';
                if (strlen($candidate) <= $maxBytes) {
                    break;
                }
            }
        }

        imagedestroy($working);

        if ($binary === null) {
            return ['binary' => $rawData, 'extension' => $fallbackExtension];
        }

        return ['binary' => $binary, 'extension' => $extension];
    }

    private function encodeImageResource(\GdImage $image, string $format, int $quality): ?string
    {
        ob_start();

        $ok = false;
        if ($format === 'webp') {
            $ok = imagewebp($image, null, $quality);
        } elseif ($format === 'jpg') {
            $ok = imagejpeg($image, null, $quality);
        } elseif ($format === 'png') {
            $ok = imagepng($image, null, $quality);
        }

        $content = ob_get_clean();
        if (!$ok || !is_string($content) || $content === '') {
            return null;
        }

        return $content;
    }
}
