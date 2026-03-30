<?php
require_once base_path('app/views/front/components/ArticleCard.php');
renderFrontStylesheet('/assets/front/single-article.css');

$articleData = [
    'category' => 'Environnement',
    'tags' => ['Madagascar', 'Transition energetique', 'Littoral', 'Politique publique'],
    'title' => 'Comment les communes cotieres reinventent leur avenir economique',
    'standfirst' => "Dans plusieurs regions du littoral, des maires, des cooperatives et des chercheurs testent un modele qui melange peche durable, energie solaire et tourisme de proximite.",
    'author' => 'Rina Andriantsoa',
    'publishedAt' => '2026-03-30T05:35:00',
    'readingTime' => '12 min',
    'heroImage' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1600&q=80',
    'heroCaption' => 'Un village cotier au lever du jour, sur la cote est de Madagascar.',
    'inlineImage' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80',
    'inlineCaption' => 'Des installations solaires financees par une cooperative locale.',
    'sections' => [
        [
            'heading' => 'Un changement de doctrine locale',
            'paragraphs' => [
                "Pendant des annees, les economies cotieres ont fonctionne en silos: peche d'un cote, tourisme de l'autre, transport souvent neglige. Depuis deux ans, un groupe de communes teste une approche plus integree.",
                'L idee est simple: mettre les revenus des activites saisonnieres au service des besoins permanents, notamment les equipements energetiques et la logistique de base.',
            ],
        ],
        [
            'heading' => 'Des projets concrets, des resultats inegaux',
            'paragraphs' => [
                'A Mahavelona, la chaine du froid alimentee par panneaux solaires a limite les pertes de captures. Ailleurs, la mise en reseau des artisans du tourisme avance plus lentement, faute de formation et de transport regulier.',
                'Les chercheurs impliques insistent sur un point: les gains existent, mais ils dependent de la capacite des communes a mutualiser leurs moyens sur plusieurs annees.',
            ],
        ],
        [
            'heading' => 'Le test politique de 2026',
            'paragraphs' => [
                'Le gouvernement suit ces experimentations avec attention, car elles dessinent un possible cadre national pour les zones littorales. Les arbitrages budgetaires de fin d annee diront si cette voie devient une priorite.',
                'Pour les habitants rencontres, le debat est moins ideologique que pratique: conserver les emplois locaux, limiter l exode et mieux proteger les ressources.',
            ],
        ],
    ],
];

$relatedArticles = [
    'Peche artisanale: trois cooperatives passent au numerique',
    'Tourisme local: les petites structures reprennent de la vitesse',
    'Energie rurale: le solaire communautaire attire de nouveaux investisseurs',
    'Gestion des risques: comment les communes anticipent les cyclones',
];
?>

<section class="news-article-page" aria-label="Article complet">
  <header class="news-article-headline">
    <p class="news-article-category"><?= newsEsc($articleData['category']) ?></p>

    <ul class="news-article-tags" aria-label="Tags de l article">
      <?php foreach ($articleData['tags'] as $tag): ?>
        <li class="news-article-tag">#<?= newsEsc($tag) ?></li>
      <?php endforeach; ?>
    </ul>

    <h1 class="news-article-title"><?= newsEsc($articleData['title']) ?></h1>
    <p class="news-article-standfirst"><?= newsEsc($articleData['standfirst']) ?></p>

    <div class="news-article-meta">
      <span>Par <?= newsEsc($articleData['author']) ?></span>
      <span><?= newsEsc(newsFormatDateFr($articleData['publishedAt'])) ?></span>
      <span><?= newsEsc($articleData['readingTime']) ?> de lecture</span>
    </div>
  </header>

  <figure class="news-article-hero">
    <img src="<?= newsEsc($articleData['heroImage']) ?>" alt="<?= newsEsc($articleData['title']) ?>">
    <figcaption><?= newsEsc($articleData['heroCaption']) ?></figcaption>
  </figure>

  <div class="news-article-layout">
    <article class="news-article-content">
      <?php foreach ($articleData['sections'] as $index => $section): ?>
        <section>
          <h2><?= newsEsc($section['heading']) ?></h2>
          <?php foreach ($section['paragraphs'] as $paragraph): ?>
            <p><?= newsEsc($paragraph) ?></p>
          <?php endforeach; ?>

          <?php if ($index === 1): ?>
            <figure class="news-article-inline-figure">
              <img src="<?= newsEsc($articleData['inlineImage']) ?>" alt="Illustration du reportage">
              <figcaption><?= newsEsc($articleData['inlineCaption']) ?></figcaption>
            </figure>
          <?php endif; ?>
        </section>
      <?php endforeach; ?>
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
