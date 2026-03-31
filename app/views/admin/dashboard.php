<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tableau de bord d'administration - Vue d'ensemble et accès rapide aux outils de gestion du contenu.">
    <title><?= htmlspecialchars($title ?? 'Tableau de bord', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="/assets/admin/admin.css">
</head>
<body>
    <?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
    <h1><?= htmlspecialchars($title ?? 'Tableau de bord', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Bienvenue dans votre zone d'administration.</p>
    <ul class="dashboard-links">
        <li><a href="/admin/articles">Gestion des articles</a></li>
        <li><a href="/admin/feed">Gestion du feed home</a></li>
        <li><a href="/admin/category-featured">Articles phares par categorie</a></li>
        <li><a href="/admin/categories">Gestion des catégories</a></li>
        <li><a href="/admin/tags">Gestion des tags</a></li>
    </ul>
    <p><a href="/">Retour au site public</a></p>
</body>
</html>
