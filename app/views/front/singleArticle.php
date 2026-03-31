<?php
require_once base_path('app/views/front/components/ArticleCard.php');
renderFrontStylesheet('/assets/front/single-article.css');

$articleData = is_array($articleData ?? null) ? $articleData : [];
$relatedArticles = is_array($relatedArticles ?? null) ? $relatedArticles : [];

$tags = is_array($articleData['tags'] ?? null) ? $articleData['tags'] : [];
$contentHtml = (string) ($articleData['contentHtml'] ?? '');
?>

<section class="news-article-page" aria-label="Article complet">
  <header class="news-article-headline">
    <h2 class="news-article-category\"><?= newsEsc((string) ($articleData['category'] ?? 'Actualites')) ?></h2>

    <ul class="news-article-tags" aria-label="Tags de l article">
      <?php foreach ($tags as $tag): ?>
        <li class="news-article-tag">#<?= newsEsc($tag) ?></li>
      <?php endforeach; ?>
    </ul>

    <h1 class="news-article-title\"><?= newsEsc((string) ($articleData['title'] ?? 'Article')) ?></h1>
    <p class="news-article-standfirst\"><?= newsEsc((string) ($articleData['standfirst'] ?? '')) ?></p>

    <div class="news-article-meta">
      <span>Par <?= newsEsc((string) ($articleData['author'] ?? 'Redaction')) ?></span>
      <span><?= newsEsc(newsFormatDateFr((string) ($articleData['publishedAt'] ?? ''))) ?></span>
      <span><?= newsEsc((string) ($articleData['readingTime'] ?? '')) ?> de lecture</span>
    </div>
  </header>

  <?php if (!empty($articleData['heroImage'])): ?>
    <figure class="news-article-hero">
      <img src="<?= newsEsc((string) $articleData['heroImage']) ?>" alt="<?= newsEsc((string) ($articleData['title'] ?? 'Article')) ?>">
      <?php if (!empty($articleData['heroCaption'])): ?>
        <figcaption><?= newsEsc((string) $articleData['heroCaption']) ?></figcaption>
      <?php endif; ?>
    </figure>
  <?php endif; ?>

  <div class="news-article-layout">
    <article class="news-article-content">
      <?php if ($contentHtml !== ''): ?>
        <?= $contentHtml ?>
      <?php else: ?>
        <p>Le contenu de cet article est indisponible.</p>
      <?php endif; ?>
    </article>

    <aside class="news-article-aside" aria-label="Articles lies">
      <h3>A lire aussi</h3>
      <ul class="news-article-related">
        <?php foreach ($relatedArticles as $headline): ?>
          <li><a href="#"><?= newsEsc($headline) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>
