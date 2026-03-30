<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
</head>
<body>
    <h1>404</h1>
    <p>La page <?= htmlspecialchars($uri ?? '', ENT_QUOTES, 'UTF-8') ?> est introuvable.</p>
    <p><a href="/">Retour a l'accueil</a></p>
</body>
</html>
