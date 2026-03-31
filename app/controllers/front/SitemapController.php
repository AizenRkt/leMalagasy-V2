<?php

declare(strict_types=1);

namespace App\Controllers\Front;

use App\Core\Database;
use PDO;

final class SitemapController
{
    public function index(): string
    {
        header('Content-Type: application/xml; charset=UTF-8');

        $urls = [];
        $urls[] = [
            'loc' => absolute_url('/'),
            'changefreq' => 'hourly',
            'priority' => '1.0',
            'lastmod' => date('c'),
        ];

        $db = Database::postgres();

        $categoryStmt = $db->query('SELECT id, name FROM categorie ORDER BY id DESC');
        while ($row = $categoryStmt->fetch(PDO::FETCH_ASSOC)) {
            $id = (int) ($row['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }

            $urls[] = [
                'loc' => absolute_url(category_url((string) ($row['name'] ?? ''), $id)),
                'changefreq' => 'daily',
                'priority' => '0.7',
                'lastmod' => date('c'),
            ];
        }

        $articleStmt = $db->prepare(
            'SELECT a.id, a.title, COALESCE(a.published_at, a.created_at) AS updated_at
             FROM article a
             LEFT JOIN article_status s ON s.id_article = a.id
             WHERE s.statut IS NULL OR s.statut = ?
             ORDER BY COALESCE(a.published_at, a.created_at) DESC
             LIMIT 1000'
        );
        $articleStmt->execute(['PUBLIE']);

        while ($row = $articleStmt->fetch(PDO::FETCH_ASSOC)) {
            $id = (int) ($row['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }

            $updatedAt = (string) ($row['updated_at'] ?? '');
            $lastmod = $updatedAt !== '' ? date('c', strtotime($updatedAt)) : date('c');

            $urls[] = [
                'loc' => absolute_url(article_url((string) ($row['title'] ?? ''), $id)),
                'changefreq' => 'daily',
                'priority' => '0.8',
                'lastmod' => $lastmod,
            ];
        }

        $xml = ['<?xml version="1.0" encoding="UTF-8"?>'];
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . $this->xmlEscape((string) $url['loc']) . '</loc>';
            $xml[] = '    <lastmod>' . $this->xmlEscape((string) $url['lastmod']) . '</lastmod>';
            $xml[] = '    <changefreq>' . $this->xmlEscape((string) $url['changefreq']) . '</changefreq>';
            $xml[] = '    <priority>' . $this->xmlEscape((string) $url['priority']) . '</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml);
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
