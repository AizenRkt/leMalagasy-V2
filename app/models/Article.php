<?php

declare(strict_types=1);

namespace App\Models;

final class Article
{
    public function __construct(
        public ?int $id = null,
        public string $title = '',
        public string $summary = '',
        public ?string $mongodbId = null,
        public ?string $content = null, // Will hold MongoDB content
        public ?string $publishedAt = null,
        public ?string $createdAt = null,
    ) {
    }
}
