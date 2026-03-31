<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestion des <?= $type === 'category' ? 'catégories' : 'tags' ?> - Administrez facilement votre contenu.">
    <title><?= htmlspecialchars($title ?? 'Taxonomie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/assets/admin/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
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
