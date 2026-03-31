<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Configuration du feed Home - Choisissez les articles a la une, les derniers et la selection redaction.">
    <title><?= htmlspecialchars($title ?? 'Configuration feed', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
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
        .container { max-width: 1050px; margin: 0 auto; }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
        .btn { background: var(--primary); color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: opacity 0.2s; border: none; cursor: pointer; display: inline-block; }
        .btn:hover { opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { background: #f1f5f9; }

        .notice-ok { background: var(--ok-bg); color: var(--ok-text); border: 1px solid #bbf7d0; border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; font-weight: 600; }

        form { background: var(--card-bg); border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.25rem; }
        .section-title { margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 800; color: #0f172a; }
        .section-hint { margin: 0 0 0.75rem 0; color: #64748b; font-size: 0.875rem; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        .slot-group { border: 1px solid var(--border); border-radius: 0.625rem; padding: 1rem; background: #ffffff; }
        .slot-row { display: grid; grid-template-columns: 180px 1fr; gap: 0.75rem; align-items: center; margin-bottom: 0.625rem; }
        .slot-row:last-child { margin-bottom: 0; }
        .slot-row label { font-size: 0.875rem; font-weight: 700; color: #334155; }
        .slot-row select { width: 100%; padding: 0.55rem 0.7rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.875rem; background: #fff; }
        .status-pill { border-radius: 9999px; font-size: 0.7rem; padding: 0.2rem 0.45rem; font-weight: 700; margin-left: 0.35rem; }
        .status-PUBLIE { background: #dcfce7; color: #166534; }
        .status-BROUILLON { background: #f1f5f9; color: #475569; }
        .status-ARCHIVE { background: #fee2e2; color: #991b1b; }

        .footer-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem; }

        @media (min-width: 860px) {
            .grid { grid-template-columns: 1fr 1fr; }
            .slot-group.featured { grid-column: 1 / -1; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-box">
        <h1><?= htmlspecialchars($title ?? 'Configuration du feed Home', ENT_QUOTES, 'UTF-8') ?></h1>
        <a href="/admin/dashboard" class="btn btn-outline">Dashboard</a>
    </div>

    <?php if (!empty($saved)): ?>
        <div class="notice-ok">Configuration enregistree avec succes.</div>
    <?php endif; ?>

    <form method="POST" action="/admin/feed">
        <div class="grid">
            <section class="slot-group featured">
                <h2 class="section-title">A la une (FEATURED)</h2>
                <p class="section-hint">Choisissez l'article principal affiche en grand en tete du feed.</p>
                <div class="slot-row">
                    <label for="featured">Article principal</label>
                    <select id="featured" name="featured">
                        <option value="0">-- Aucun article --</option>
                        <?php foreach ($articles as $article): ?>
                            <?php
                                $isSelected = (int) ($featured ?? 0) === (int) $article['id'];
                                $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                            ?>
                            <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </section>

            <section class="slot-group">
                <h2 class="section-title">Dernieres informations (LATEST)</h2>
                <p class="section-hint">3 slots compacts affiches dans la colonne de droite.</p>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="slot-row">
                        <label for="latest_<?= $i + 1 ?>">Position <?= $i + 1 ?></label>
                        <select id="latest_<?= $i + 1 ?>" name="latest[]">
                            <option value="0">-- Aucun article --</option>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                    $currentId = (int) (($latest[$i] ?? 0));
                                    $isSelected = $currentId === (int) $article['id'];
                                    $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                                ?>
                                <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </section>

            <section class="slot-group">
                <h2 class="section-title">Selection redaction (SPOTLIGHT)</h2>
                <p class="section-hint">4 cartes standard affichees en grille en dessous.</p>
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="slot-row">
                        <label for="spotlight_<?= $i + 1 ?>">Position <?= $i + 1 ?></label>
                        <select id="spotlight_<?= $i + 1 ?>" name="spotlight[]">
                            <option value="0">-- Aucun article --</option>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                    $currentId = (int) (($spotlight[$i] ?? 0));
                                    $isSelected = $currentId === (int) $article['id'];
                                    $label = '#' . (int) $article['id'] . ' - ' . ($article['title'] !== '' ? $article['title'] : 'Sans titre');
                                ?>
                                <option value="<?= (int) $article['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </section>
        </div>

        <div class="footer-actions">
            <a href="/admin/dashboard" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</div>
</body>
</html>
