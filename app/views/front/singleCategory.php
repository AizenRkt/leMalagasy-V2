<?php
require_once base_path('app/views/front/components/ArticleCard.php');
renderFrontStylesheet('/assets/front/single-category.css');

$categoryData = [
    'name' => 'Economie',
    'tags' => [
        'Croissance',
        'Investissement',
        'PME',
        'Emploi local',
        'Startup',
        'Innovation',
        'Fintech',
        'Commerce',
        'Secteur public',
        'Developpement durable',
    ],
];

$featuredArticles = [
    [
        'id' => 'c1',
        'title' => 'Les ports secondaires deviennent strategiques pour les exportateurs',
        'image' => 'https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=900&q=80',
        'publishedAt' => '2026-03-29T10:20:00',
    ],
    [
        'id' => 'c2',
        'title' => 'Fintech: trois solutions locales simplifient le paiement des factures',
        'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80',
        'publishedAt' => '2026-03-29T08:40:00',
    ],
    [
        'id' => 'c3',
        'title' => 'Agribusiness: le modele cooperatif attire de nouveaux profils',
        'image' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=900&q=80',
        'publishedAt' => '2026-03-28T18:15:00',
    ],
];

$categoryArticles = [
    [
        'id' => 'c4',
        'title' => 'Industrie textile: les ateliers misent sur des series courtes plus rentables',
        'excerpt' => 'Face a la volatilite des commandes, plusieurs entreprises adaptent leur production pour reduire les risques logistiques.',
        'author' => 'Rindra A.',
        'publishedAt' => '2026-03-28T16:10:00',
        'readingTime' => '6 min',
    ],
    [
        'id' => 'c5',
        'title' => 'Petites banques: une offensive numerique pour capter les jeunes actifs',
        'excerpt' => 'Applications simplifiees, frais plus lisibles et micro-credit rapide: les nouveaux usages accelerent la concurrence.',
        'author' => 'Mamy R.',
        'publishedAt' => '2026-03-28T14:25:00',
        'readingTime' => '5 min',
    ],
    [
        'id' => 'c6',
        'title' => 'Tourisme d affaires: les villes secondaires veulent leur part du marche',
        'excerpt' => 'Hotels, transport et services digitaux se coordonnent pour attirer les conferences regionales.',
        'author' => 'Nantenaina V.',
        'publishedAt' => '2026-03-27T19:40:00',
        'readingTime' => '4 min',
    ],
    [
        'id' => 'c7',
        'title' => 'Energie et PME: comment lisser la facture sans freiner la croissance',
        'excerpt' => 'Des contrats hybrides emergent pour stabiliser les couts et planifier l investissement sur plusieurs trimestres.',
        'author' => 'Tsiry F.',
        'publishedAt' => '2026-03-27T11:30:00',
        'readingTime' => '7 min',
    ],
];

$initialTagsCount = 4;
$hasMoreTags = count($categoryData['tags']) > $initialTagsCount;
?>

<section class="news-category-page" aria-label="Page categorie">
  <header class="news-category-head">
    <p class="news-category-kicker">Categorie</p>
    <h1 class="news-category-title"><?= newsEsc($categoryData['name']) ?></h1>

    <div class="news-category-tags-container">
      <ul class="news-category-tags" aria-label="Tags de categorie" data-tags-list>
        <?php foreach ($categoryData['tags'] as $index => $tag): ?>
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
      <?php foreach ($featuredArticles as $article): ?>
        <a href="/article" class="news-category-featured-card">
          <img src="<?= newsEsc($article['image']) ?>" alt="<?= newsEsc($article['title']) ?>">
          <div class="news-category-featured-content">
            <p class="news-category-featured-meta"><?= newsEsc(newsFormatDateFr($article['publishedAt'])) ?></p>
            <h3><?= newsEsc($article['title']) ?></h3>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="news-category-list" aria-label="Liste d articles de la categorie">
    <h2>Tous les articles de la categorie</h2>
    <ul class="news-category-list-items">
      <?php foreach ($categoryArticles as $article): ?>
        <li class="news-category-list-item">
          <a href="/article">
            <h4><?= newsEsc($article['title']) ?></h4>
            <p class="news-category-list-meta">
              Par <?= newsEsc($article['author']) ?> • <?= newsEsc(newsFormatDateFr($article['publishedAt'])) ?> • <?= newsEsc($article['readingTime']) ?> de lecture
            </p>
            <p class="news-category-list-excerpt"><?= newsEsc($article['excerpt']) ?></p>
          </a>
        </li>
      <?php endforeach; ?>
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
