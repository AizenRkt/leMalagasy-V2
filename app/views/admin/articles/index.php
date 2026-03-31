<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tableau de bord de gestion des actualités - Gérer, filtrer et modifier vos articles en toute simplicité.">
    <title><?= htmlspecialchars($title ?? 'Gestion des actualités', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="/assets/admin/admin.css">
</head>
<body>
    <?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
    <div class="container">
        <div class="header-box">
            <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
            <a href="/admin/articles/create" class="btn">Nouvelle actualité</a>
        </div>

        <form method="GET" class="filters">
            <div class="form-group">
                <label>Recherche</label>
                <input type="text" name="title" value="<?= htmlspecialchars($filters['title'] ?? '') ?>" placeholder="Titre de l'article...">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="status">
                    <option value="">Tous les statuts</option>
                    <option value="BROUILLON" <?= ($filters['status'] ?? '') === 'BROUILLON' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="PUBLIE" <?= ($filters['status'] ?? '') === 'PUBLIE' ? 'selected' : '' ?>>Publié</option>
                    <option value="ARCHIVE" <?= ($filters['status'] ?? '') === 'ARCHIVE' ? 'selected' : '' ?>>Archivé</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <select name="category_id">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-outline">Filtrer</button>
            <a href="/admin/articles" class="btn btn-outline">Réinitialiser</a>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date de création</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?>
                        <tr><td colspan="4" style="text-align: center; color: #64748b;">Aucune actualité trouvée.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($article['title'] ?? '') ?></td>
                            <td style="color: #64748b;"><?= date('d/m/Y H:i', strtotime($article['created_at'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= $article['statut'] ?>">
                                    <?= htmlspecialchars($article['statut'] ?? 'INCONNU') ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/articles/edit?id=<?= $article['id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Modifier</a>
                                    
                                    <form action="/admin/articles/status" method="POST" class="action-form">
                                        <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                        <?php if ($article['statut'] === 'BROUILLON'): ?>
                                            <button type="submit" name="status" value="PUBLIE" class="btn" style="padding: 0.4rem 0.8rem; background: #16a34a;">Publier</button>
                                        <?php elseif ($article['statut'] === 'PUBLIE'): ?>
                                            <button type="submit" name="status" value="ARCHIVE" class="btn" style="padding: 0.4rem 0.8rem; background: #ef4444;">Archiver</button>
                                        <?php else: ?>
                                            <button type="submit" name="status" value="PUBLIE" class="btn" style="padding: 0.4rem 0.8rem; background: #16a34a;">Rétablir</button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
