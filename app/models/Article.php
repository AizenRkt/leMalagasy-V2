<?php

declare(strict_types=1);

namespace App\Models;

final class Article
{
    public function __construct(
        public int $id,
        public string $title,
        public string $excerpt,
    ) {
    }
}
