<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Configuration des articles phares par categorie.">
    <title><?= htmlspecialchars($title ?? 'Articles phares par categorie', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --border: #e2e8f0;
            --ok-bg: #dcfce7;
            --ok-text: #166534;
        }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; }
        .container { max-width: 1024px; margin: 0 auto; }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }

        .btn { background: var(--primary); color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; }
        .btn:hover { opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }

        .notice-ok { background: var(--ok-bg); color: var(--ok-text); border: 1px solid #bbf7d0; border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; font-weight: 600; }

        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem; }
        .row { display: grid; grid-template-columns: 220px 1fr; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; }
        .row:last-child { margin-bottom: 0; }
        label { font-size: 0.875rem; font-weight: 700; color: #334155; }
        select { width: 100%; padding: 0.55rem 0.7rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.875rem; background: #fff; }
        .hint { margin: 0 0 0.75rem 0; color: #64748b; font-size: 0.875rem; }
        .footer-actions { display: flex; justify-content: flex-end; gap: 0.75rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="header-box">
        <h1><?= htmlspecialchars($title ?? 'Articles phares par categorie', ENT_QUOTES, 'UTF-8') ?></h1>
        <a href="/admin/dashboard" class="btn btn-outline">Dashboard</a>
    </div>

    <?php if (!empty($saved)): ?>
        <div class="notice-ok">Configuration enregistree.</div>
    <?php endif; ?>

    <form method="GET" action="/admin/category-featured" class="card">
        <div class="row">
            <label for="category_id">Categorie</label>
            <select id="category_id" name="category_id" onchange="this.form.submit()">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= (int) $selectedCategoryId === (int) $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string) ($category['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <form method="POST" action="/admin/category-featured" class="card">
        <input type="hidden" name="category_id" value="<?= (int) $selectedCategoryId ?>">
        <p class="hint">Les options affichent uniquement les articles de la categorie selectionnee. Maximum 3 articles phares.</p>

        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="row">
                <label for="featured_<?= $i + 1 ?>">Article phare <?= $i + 1 ?></label>
                <select id="featured_<?= $i + 1 ?>" name="featured[]">
                    <option value="0">-- Aucun article --</option>
                    <?php foreach ($articles as $article): ?>
                        <?php
                            $selectedId = (int) ($featured[$i] ?? 0);
                            $isSelected = $selectedId === (int) $article['id'];
                            $label = '#' . (int) $article['id'] . ' - ' . ((string) ($article['title'] ?? '') !== '' ? (string) $article['title'] : 'Sans titre');
                        ?>
                        <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endfor; ?>

        <div class="footer-actions">
            <a href="/admin/dashboard" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</div>
</body>
</html>
