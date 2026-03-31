<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Tag;
use App\Models\Category;
use PDO;

final class TaxonomyService
{
    /** @return Category[] */
    public function getCategories(): array
    {
        $db = Database::postgres();
        $stmt = $db->query('SELECT * FROM categorie ORDER BY name ASC');
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category((int) $row['id'], $row['name']);
        }
        return $categories;
    }

    public function getCategory(int $id): ?Category
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT * FROM categorie WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Category((int) $row['id'], $row['name']) : null;
    }

    public function createCategory(string $name): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('INSERT INTO categorie (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

    public function updateCategory(int $id, string $name): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('UPDATE categorie SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    public function deleteCategory(int $id): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('DELETE FROM categorie WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /** @return Tag[] */
    public function getTags(): array
    {
        $db = Database::postgres();
        $stmt = $db->query('SELECT * FROM tag ORDER BY name ASC');
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tag((int) $row['id'], $row['name']);
        }
        return $tags;
    }

    public function getTag(int $id): ?Tag
    {
        $db = Database::postgres();
        $stmt = $db->prepare('SELECT * FROM tag WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Tag((int) $row['id'], $row['name']) : null;
    }

    public function createTag(string $name): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('INSERT INTO tag (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

    public function updateTag(int $id, string $name): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('UPDATE tag SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    public function deleteTag(int $id): bool
    {
        $db = Database::postgres();
        $stmt = $db->prepare('DELETE FROM tag WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
