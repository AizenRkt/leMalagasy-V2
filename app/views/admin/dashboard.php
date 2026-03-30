<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Zone administration.</p>
    <p><a href="/">Retour au front</a></p>
</body>
</html>
