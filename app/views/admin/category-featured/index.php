<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Configuration des articles phares par categorie.">
    <title><?= htmlspecialchars($title ?? 'Articles phares par categorie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/assets/admin/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
<?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
<div class="container">
    <div class="header-box">
        <h1><?= htmlspecialchars($title ?? 'Articles phares par categorie', ENT_QUOTES, 'UTF-8') ?></h1>
        <a href="/admin/dashboard" class="btn btn-outline">Dashboard</a>
    </div>

    <?php if (!empty($saved)): ?>
        <div class="notice-ok">Configuration enregistree.</div>
    <?php endif; ?>

    <form method="GET" action="/admin/category-featured" class="card">
        <div class="row">
            <label for="category_id">Categorie</label>
            <select id="category_id" name="category_id" onchange="this.form.submit()">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= (int) $selectedCategoryId === (int) $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string) ($category['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <form method="POST" action="/admin/category-featured" class="card">
        <input type="hidden" name="category_id" value="<?= (int) $selectedCategoryId ?>">
        <p class="hint">Les options affichent uniquement les articles de la categorie selectionnee. Maximum 3 articles phares.</p>

        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="row">
                <label for="featured_<?= $i + 1 ?>">Article phare <?= $i + 1 ?></label>
                <select id="featured_<?= $i + 1 ?>" name="featured[]">
                    <option value="0">-- Aucun article --</option>
                    <?php foreach ($articles as $article): ?>
                        <?php
                            $selectedId = (int) ($featured[$i] ?? 0);
                            $isSelected = $selectedId === (int) $article['id'];
                            $label = '#' . (int) $article['id'] . ' - ' . ((string) ($article['title'] ?? '') !== '' ? (string) $article['title'] : 'Sans titre');
                        ?>
                        <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endfor; ?>

        <div class="footer-actions">
            <a href="/admin/dashboard" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</div>
</body>
</html>
