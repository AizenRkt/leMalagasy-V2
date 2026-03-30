<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestion des <?= $type === 'category' ? 'catégories' : 'tags' ?> - Administrez facilement votre contenu.">
    <title><?= htmlspecialchars($title ?? 'Taxonomie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --border: #e2e8f0;
        }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; }
        .container { max-width: 800px; margin: 0 auto; }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
        .btn { background: var(--primary); color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: opacity 0.2s; border: none; cursor: pointer; display: inline-block; }
        .btn:hover { opacity: 0.9; }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-danger { background: #ef4444; color: #fff; }

        .table-container { background: var(--card-bg); border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; padding: 1rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 1px solid var(--border); }
        td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.875rem; }
        .actions { display: flex; gap: 0.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-box">
            <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="actions">
                <a href="/admin/taxonomy/create?type=<?= $type ?>" class="btn">Nouveau</a>
                <a href="/admin/dashboard" class="btn btn-outline">Dashboard</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr><td colspan="3" style="text-align: center; color: #64748b;">Aucun élément trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach($items as $item): ?>
                            <tr>
                                <td><?= $item->id ?></td>
                                <td><strong><?= htmlspecialchars($item->name ?? '') ?></strong></td>
                                <td class="actions">
                                    <a href="/admin/taxonomy/edit?type=<?= $type ?>&id=<?= $item->id ?>" class="btn btn-sm btn-outline">Modifier</a>
                                    <a href="/admin/taxonomy/delete?type=<?= $type ?>&id=<?= $item->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet élément ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
