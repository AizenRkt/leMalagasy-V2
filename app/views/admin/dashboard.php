<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tableau de bord d'administration - Vue d'ensemble et accès rapide aux outils de gestion du contenu.">
    <title><?= htmlspecialchars($title ?? 'Tableau de bord', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
</head>
<body>
    <h1><?= htmlspecialchars($title ?? 'Tableau de bord', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Bienvenue dans votre zone d'administration.</p>
    <p><a href="/admin/articles">Gérer les actualités</a></p>
    <p><a href="/">Retour au site public</a></p>
</body>
</html>
