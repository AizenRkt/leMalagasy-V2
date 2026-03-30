<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\TaxonomyService;

final class TaxonomyController
{
    private TaxonomyService $service;

    public function __construct()
    {
        $this->service = new TaxonomyService();
    }

    public function categories(): string
    {
        return view('admin/taxonomy/index', [
            'type' => 'category',
            'title' => 'Gérer les catégories',
            'items' => $this->service->getCategories()
        ]);
    }

    public function tags(): string
    {
        return view('admin/taxonomy/index', [
            'type' => 'tag',
            'title' => 'Gérer les tags',
            'items' => $this->service->getTags()
        ]);
    }

    public function createView(): string
    {
        $type = $_GET['type'] ?? 'category';
        return view('admin/taxonomy/form', [
            'type' => $type,
            'title' => 'Ajouter ' . ($type === 'category' ? 'une catégorie' : 'un tag'),
            'item' => null
        ]);
    }

    public function store(): void
    {
        $type = $_POST['type'] ?? 'category';
        $name = $_POST['name'] ?? '';

        if (!empty($name)) {
            if ($type === 'category') {
                $this->service->createCategory($name);
            } else {
                $this->service->createTag($name);
            }
        }

        header('Location: /admin/' . ($type === 'category' ? 'categories' : 'tags'));
        exit;
    }

    public function editView(): string
    {
        $type = $_GET['type'] ?? 'category';
        $id = (int) ($_GET['id'] ?? 0);
        $item = ($type === 'category') ? $this->service->getCategory($id) : $this->service->getTag($id);

        if (!$item) {
            header('Location: /admin/' . ($type === 'category' ? 'categories' : 'tags'));
            exit;
        }

        return view('admin/taxonomy/form', [
            'type' => $type,
            'title' => 'Modifier ' . ($type === 'category' ? 'la catégorie' : 'le tag'),
            'item' => $item
        ]);
    }

    public function update(): void
    {
        $type = $_POST['type'] ?? 'category';
        $id = (int) ($_POST['id'] ?? 0);
        $name = $_POST['name'] ?? '';

        if (!empty($name) && $id > 0) {
            if ($type === 'category') {
                $this->service->updateCategory($id, $name);
            } else {
                $this->service->updateTag($id, $name);
            }
        }

        header('Location: /admin/' . ($type === 'category' ? 'categories' : 'tags'));
        exit;
    }

    public function delete(): void
    {
        $type = $_GET['type'] ?? 'category';
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            if ($type === 'category') {
                $this->service->deleteCategory($id);
            } else {
                $this->service->deleteTag($id);
            }
        }

        header('Location: /admin/' . ($type === 'category' ? 'categories' : 'tags'));
        exit;
    }
}
