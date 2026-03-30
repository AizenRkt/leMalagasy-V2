<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'A propos', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($title ?? 'A propos', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Cette base respecte une architecture MVC simple sans framework.</p>
    <p><a href="/">Retour a l'accueil</a></p>
</body>
</html>
