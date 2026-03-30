<h1><?= htmlspecialchars($title ?? 'Accueil', ENT_QUOTES, 'UTF-8') ?></h1>
<p>Bienvenue dans ton application PHP native.</p>

<h2>Etat des bases de donnees</h2>
<ul>
    <li>
        PostgreSQL:
        <strong style="color: <?= ($dbStatus['postgres']['ok'] ?? false) ? 'green' : 'red' ?>;">
            <?= ($dbStatus['postgres']['ok'] ?? false) ? 'OK' : 'ECHEC' ?>
        </strong>
        <span>- <?= htmlspecialchars((string) ($dbStatus['postgres']['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
    </li>
    <li>
        MongoDB:
        <strong style="color: <?= ($dbStatus['mongodb']['ok'] ?? false) ? 'green' : 'red' ?>;">
            <?= ($dbStatus['mongodb']['ok'] ?? false) ? 'OK' : 'ECHEC' ?>
        </strong>
        <span>- <?= htmlspecialchars((string) ($dbStatus['mongodb']['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
    </li>
</ul>

<h2>Derniers articles</h2>
<ul>
    <?php foreach (($articles ?? []) as $article): ?>
        <?php
            $articleTitle = (is_object($article) && isset($article->title)) ? (string) $article->title : '';
            $articleExcerpt = (is_object($article) && isset($article->excerpt)) ? (string) $article->excerpt : '';
        ?>
        <li>
            <strong><?= htmlspecialchars($articleTitle, ENT_QUOTES, 'UTF-8') ?></strong><br>
            <span><?= htmlspecialchars($articleExcerpt, ENT_QUOTES, 'UTF-8') ?></span>
        </li>
    <?php endforeach; ?>
</ul>

<p><a href="/about">A propos</a> | <a href="/admin">Admin</a></p>
