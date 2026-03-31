<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Taxonomie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; font-size: 1.875rem; font-weight: 700; color: #111827; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
        input[type="text"] { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; box-sizing: border-box; }
        .btn { background: var(--primary); color: #fff; padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 1rem; transition: background 0.2s; }
        .btn:hover { background: var(--primary-hover); }
        .btn-outline { background: transparent; border: 1px solid #d1d5db; color: #64748b; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; display: inline-block; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <form action="/admin/taxonomy/<?= $item ? 'update' : 'create' ?>" method="POST">
            <input type="hidden" name="type" value="<?= $type ?>">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= $item->id ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nom de la <?= $type === 'category' ? 'catégorie' : 'tag' ?></label>
                <input type="text" name="name" id="name" required value="<?= htmlspecialchars($item->name ?? '') ?>" placeholder="Entrez le nom...">
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn"><?= $item ? 'Enregistrer' : 'Ajouter' ?></button>
                <a href="/admin/<?= $type === 'category' ? 'categories' : 'tags' ?>" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
