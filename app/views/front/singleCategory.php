<?php
require_once base_path('app/views/front/components/ArticleCard.php');
renderFrontStylesheet('/assets/front/single-category.css');

$categoryData = (isset($categoryData) && is_array($categoryData))
  ? $categoryData
  : ['name' => 'Categorie', 'tags' => []];
$featuredArticles = (isset($featuredArticles) && is_array($featuredArticles)) ? $featuredArticles : [];
$categoryArticles = (isset($categoryArticles) && is_array($categoryArticles)) ? $categoryArticles : [];

$initialTagsCount = 4;
$categoryTags = isset($categoryData['tags']) && is_array($categoryData['tags']) ? $categoryData['tags'] : [];
$hasMoreTags = count($categoryTags) > $initialTagsCount;
?>

<section class="news-category-page" aria-label="Page categorie">
  <header class="news-category-head">
    <p class="news-category-kicker">Categorie</p>
    <h1 class="news-category-title"><?= newsEsc($categoryData['name']) ?></h1>

    <div class="news-category-tags-container">
      <ul class="news-category-tags" aria-label="Tags de categorie" data-tags-list>
        <?php foreach ($categoryTags as $index => $tag): ?>
          <li class="news-category-tag <?= $index >= $initialTagsCount ? 'is-hidden' : '' ?>" data-tag-item>
            #<?= newsEsc($tag) ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <?php if ($hasMoreTags): ?>
        <button
          class="news-category-tags-toggle"
          type="button"
          data-tags-toggle
          aria-expanded="false"
          aria-label="Afficher plus de tags"
        >
          + Voir plus
        </button>
      <?php endif; ?>
    </div>
  </header>

  <section class="news-category-featured" aria-label="Articles phares">
    <h2>Articles phares</h2>
    <div class="news-category-featured-grid">
      <?php if ($featuredArticles === []): ?>
        <p>Aucun article phare pour cette categorie.</p>
      <?php else: ?>
        <?php foreach ($featuredArticles as $article): ?>
          <a href="<?= newsEsc(article_url((string) ($article['title'] ?? ''), (int) ($article['id'] ?? 0))) ?>" class="news-category-featured-card">
            <?php if (!empty($article['image']) && is_string($article['image'])): ?>
              <img src="<?= newsEsc($article['image']) ?>" alt="<?= newsEsc((string) ($article['title'] ?? 'Article')) ?>">
            <?php endif; ?>
            <div class="news-category-featured-content">
              <p class="news-category-featured-meta"><?= newsEsc(newsFormatDateFr((string) ($article['publishedAt'] ?? ''))) ?></p>
              <h3><?= newsEsc((string) ($article['title'] ?? 'Article')) ?></h3>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="news-category-list" aria-label="Liste d articles de la categorie">
    <h2>Tous les articles de la categorie</h2>
    <ul class="news-category-list-items">
      <?php if ($categoryArticles === []): ?>
        <li class="news-category-list-item">
          <div>
            <h4>Aucun article disponible dans cette categorie.</h4>
          </div>
        </li>
      <?php else: ?>
        <?php foreach ($categoryArticles as $article): ?>
          <li class="news-category-list-item">
            <a href="<?= newsEsc(article_url((string) ($article['title'] ?? ''), (int) ($article['id'] ?? 0))) ?>">
              <h4><?= newsEsc((string) ($article['title'] ?? 'Article')) ?></h4>
              <p class="news-category-list-meta">
                Par <?= newsEsc((string) ($article['author'] ?? 'Redaction')) ?> • <?= newsEsc(newsFormatDateFr((string) ($article['publishedAt'] ?? ''))) ?> • <?= newsEsc((string) ($article['readingTime'] ?? '4 min')) ?> de lecture
              </p>
              <p class="news-category-list-excerpt"><?= newsEsc((string) ($article['excerpt'] ?? '')) ?></p>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </section>
</section>

<script>
  (function () {
    var toggleButton = document.querySelector('[data-tags-toggle]');
    if (!toggleButton) return;

    var hiddenTags = Array.prototype.slice.call(document.querySelectorAll('[data-tag-item].is-hidden'));
    var showAll = false;

    toggleButton.addEventListener('click', function () {
      showAll = !showAll;

      hiddenTags.forEach(function (tag) {
        tag.classList.toggle('is-hidden', !showAll);
      });

      toggleButton.textContent = showAll ? '− Voir moins' : '+ Voir plus';
      toggleButton.setAttribute('aria-expanded', showAll ? 'true' : 'false');
      toggleButton.setAttribute(
        'aria-label',
        showAll ? 'Afficher moins de tags' : 'Afficher plus de tags'
      );
    });
  })();
</script>
