<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Configuration du feed Home - Choisissez les articles a la une, les derniers et la selection redaction.">
    <title><?= htmlspecialchars($title ?? 'Configuration feed', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/assets/admin/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
<?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
<div class="container">
    <div class="header-box">
        <h1><?= htmlspecialchars($title ?? 'Configuration du feed Home', ENT_QUOTES, 'UTF-8') ?></h1>
        <a href="/admin/dashboard" class="btn btn-outline">Dashboard</a>
    </div>

    <?php if (!empty($saved)): ?>
        <div class="notice-ok">Configuration enregistree avec succes.</div>
    <?php endif; ?>

    <form method="POST" action="/admin/feed">
        <div class="grid">
            <section class="slot-group featured">
                <h2 class="section-title">A la une (FEATURED)</h2>
                <p class="section-hint">Choisissez l'article principal affiche en grand en tete du feed.</p>
                <div class="slot-row">
                    <label for="featured">Article principal</label>
                    <select id="featured" name="featured">
                        <option value="0">-- Aucun article --</option>
                        <?php foreach ($articles as $article): ?>
                            <?php
                                $isSelected = (int) ($featured ?? 0) === (int) $article['id'];
                                $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                            ?>
                            <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </section>

            <section class="slot-group">
                <h2 class="section-title">Dernieres informations (LATEST)</h2>
                <p class="section-hint">3 slots compacts affiches dans la colonne de droite.</p>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="slot-row">
                        <label for="latest_<?= $i + 1 ?>">Position <?= $i + 1 ?></label>
                        <select id="latest_<?= $i + 1 ?>" name="latest[]">
                            <option value="0">-- Aucun article --</option>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                    $currentId = (int) (($latest[$i] ?? 0));
                                    $isSelected = $currentId === (int) $article['id'];
                                    $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                                ?>
                                <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </section>

            <section class="slot-group">
                <h2 class="section-title">Selection redaction (SPOTLIGHT)</h2>
                <p class="section-hint">4 cartes standard affichees en grille en dessous.</p>
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="slot-row">
                        <label for="spotlight_<?= $i + 1 ?>">Position <?= $i + 1 ?></label>
                        <select id="spotlight_<?= $i + 1 ?>" name="spotlight[]">
                            <option value="0">-- Aucun article --</option>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                    $currentId = (int) (($spotlight[$i] ?? 0));
                                    $isSelected = $currentId === (int) $article['id'];
                                    $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                                ?>
                                <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </section>
        </div>

        <div class="footer-actions">
            <a href="/admin/dashboard" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</div>
</body>
</html>
