<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Gestion des actualités', ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --border: #e2e8f0;
        }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
        .btn { background: var(--primary); color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: opacity 0.2s; border: none; cursor: pointer; }
        .btn:hover { opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { background: #f1f5f9; }
        
        /* Filters */
        .filters { background: var(--card-bg); padding: 1.25rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: flex-end; }
        .form-group { display: flex; flex-direction: column; gap: 0.375rem; flex-grow: 1; }
        .form-group label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; }
        .form-group input, .form-group select { padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.875rem; }
        
        /* Table */
        .table-container { background: var(--card-bg); border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; padding: 1rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 1px solid var(--border); }
        td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.875rem; }
        tr:last-child td { border-bottom: none; }
        
        .status-badge { padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .status-PUBLIE { background: #dcfce7; color: #166534; }
        .status-BROUILLON { background: #f1f5f9; color: #475569; }
        .status-ARCHIVE { background: #fee2e2; color: #991b1b; }
        
        .actions { display: flex; gap: 0.5rem; }
        .action-form { display: inline; }
    </style>
</head>
<body>
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
