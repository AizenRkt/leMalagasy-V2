<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Category;
use PDO;

final class CategoryService
{
    /** @return Category[] */
    public function listForMenu(int $limit = 8): array
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT id, name FROM categorie ORDER BY name ASC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category((int) $row['id'], (string) $row['name']);
        }

        return $categories;
    }

    public function getById(int $id): ?Category
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT id, name FROM categorie WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Category((int) $row['id'], (string) $row['name']) : null;
    }
}
