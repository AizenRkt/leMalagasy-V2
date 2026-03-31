<?php
require_once base_path('app/views/front/components/ArticleCard.php');
renderFrontStylesheet('/assets/front/single-article.css');

$articleData = is_array($articleData ?? null) ? $articleData : [];
$relatedArticles = is_array($relatedArticles ?? null) ? $relatedArticles : [];

$tags = is_array($articleData['tags'] ?? null) ? $articleData['tags'] : [];
$contentHtml = (string) ($articleData['contentHtml'] ?? '');
$heroImage = (string) ($articleData['heroImage'] ?? '');

if ($contentHtml !== '' && $heroImage !== '') {
  $dom = new DOMDocument('1.0', 'UTF-8');
  libxml_use_internal_errors(true);
  $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contentHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  if ($loaded) {
    $imgs = $dom->getElementsByTagName('img');
    if ($imgs->length > 0) {
      $firstImg = $imgs->item(0);
      $firstSrc = (string) $firstImg->getAttribute('src');
      if ($firstSrc === $heroImage) {
        $parent = $firstImg->parentNode;
        if ($parent && strtolower($parent->nodeName) === 'figure') {
          if ($parent->parentNode) {
            $parent->parentNode->removeChild($parent);
          }
        } elseif ($firstImg->parentNode) {
          $firstImg->parentNode->removeChild($firstImg);
        }
      }
    }
    $contentHtml = $dom->saveHTML() ?: $contentHtml;
  }
  libxml_clear_errors();
}
?>

<section class="news-article-page" aria-label="Article complet">
  <header class="news-article-headline">
    <p class="news-article-category"><?= newsEsc((string) ($articleData['category'] ?? 'Actualites')) ?></p>

    <ul class="news-article-tags" aria-label="Tags de l article">
      <?php foreach ($tags as $tag): ?>
        <li class="news-article-tag">#<?= newsEsc($tag) ?></li>
      <?php endforeach; ?>
    </ul>

    <h1 class="news-article-title"><?= newsEsc((string) ($articleData['title'] ?? 'Article')) ?></h1>
    <p class="news-article-standfirst"><?= newsEsc((string) ($articleData['standfirst'] ?? '')) ?></p>

    <div class="news-article-meta">
      <span>Par <?= newsEsc((string) ($articleData['author'] ?? 'Redaction')) ?></span>
      <span><?= newsEsc(newsFormatDateFr((string) ($articleData['publishedAt'] ?? ''))) ?></span>
      <span><?= newsEsc((string) ($articleData['readingTime'] ?? '')) ?> de lecture</span>
    </div>
  </header>

  <?php if ($heroImage !== ''): ?>
    <figure class="news-article-hero">
      <img src="<?= newsEsc($heroImage) ?>" alt="<?= newsEsc((string) ($articleData['title'] ?? 'Article')) ?>">
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
        <?php foreach ($relatedArticles as $item): ?>
          <?php
            $title = is_array($item) ? (string) ($item['title'] ?? 'Article') : (string) $item;
            $href = is_array($item)
              ? (string) ($item['href'] ?? article_url($title, (int) ($item['id'] ?? 0)))
              : '/article';
          ?>
          <li><a href="<?= newsEsc($href) ?>"><?= newsEsc($title) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>
