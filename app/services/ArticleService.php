<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;

final class ArticleService
{
    /** @return Article[] */
    public function latest(): array
    {
        return [
            new Article(1, 'Bienvenue sur leMalagasy', 'Premier contenu de demonstration.'),
            new Article(2, 'Architecture MVC maison', 'Projet PHP natif sans framework.'),
        ];
    }
}
