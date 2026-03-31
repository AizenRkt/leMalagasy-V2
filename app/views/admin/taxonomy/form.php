<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Taxonomie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="/assets/admin/admin.css">
</head>
<body>
    <?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
    <?php
        $taxonomyItem = $item ?? null;
        $itemId = null;
        $itemName = '';
        if (is_object($taxonomyItem)) {
            $itemId = $taxonomyItem->id ?? null;
            $itemName = (string) ($taxonomyItem->name ?? '');
        } elseif (is_array($taxonomyItem)) {
            $itemId = $taxonomyItem['id'] ?? null;
            $itemName = (string) ($taxonomyItem['name'] ?? '');
        }
    ?>
    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <form action="/admin/taxonomy/<?= $item ? 'update' : 'create' ?>" method="POST">
            <input type="hidden" name="type" value="<?= $type ?>">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= (int) ($itemId ?? 0) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nom de la <?= $type === 'category' ? 'catégorie' : 'tag' ?></label>
                <input type="text" name="name" id="name" required value="<?= htmlspecialchars((string) $itemName, ENT_QUOTES, 'UTF-8') ?>" placeholder="Entrez le nom...">
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn"><?= $item ? 'Enregistrer' : 'Ajouter' ?></button>
                <a href="/admin/<?= $type === 'category' ? 'categories' : 'tags' ?>" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
