<?php
require_once base_path('app/views/front/components/ArticleCard.php');

$featuredArticle = [
		'id' => 'a1',
		'category' => 'Analyse',
		'title' => 'Madagascar: la nouvelle economie des territoires cotiers',
		'excerpt' => "Entre peche artisanale, tourisme durable et transition energetique, les regions littorales redessinent l agenda politique.",
		'author' => 'Rado Andriamihaja',
		'publishedAt' => '2026-03-29T09:40:00',
		'readingTime' => '7 min',
		'image' => 'https://randomimageurl.com/assets/images/local/20260103_0519_Random%20Natural%20Landscape_simple_compose_01ke205qahfmftrexg9rs7svjn_compressed_q80.jpeg',
];

$latestArticles = [
		[
				'id' => 'a2',
				'category' => 'Politique',
				'title' => 'Presidentielle: les trois coalitions testent leur strategie finale',
				'excerpt' => 'Les equipes de campagne se concentrent sur les indecis urbains.',
				'author' => 'Noro Ramanantsoa',
				'publishedAt' => '2026-03-29T10:20:00',
				'readingTime' => '4 min',
		],
		[
				'id' => 'a3',
				'category' => 'Societe',
				'title' => 'Universites: vers un semestre hybride dans cinq campus pilotes',
				'excerpt' => 'Le ministere annonce une feuille de route numerique progressive.',
				'author' => 'Tiana Rakotondrazaka',
				'publishedAt' => '2026-03-29T08:15:00',
				'readingTime' => '5 min',
		],
		[
				'id' => 'a4',
				'category' => 'Culture',
				'title' => 'Le cinema malagasy independant gagne du terrain a l international',
				'excerpt' => 'Portrait d une generation qui tourne avec des moyens legers.',
				'author' => 'Vola Razanadrakoto',
				'publishedAt' => '2026-03-28T18:30:00',
				'readingTime' => '6 min',
		],
];

$spotlightArticles = [
		[
				'id' => 'a5',
				'category' => 'Economie',
				'title' => 'Start-up locales: les investisseurs regardent de nouveau Antananarivo',
				'excerpt' => 'Le secteur fintech attire un premier cycle de financements regionaux.',
				'author' => 'Mickael R.',
				'publishedAt' => '2026-03-28T16:00:00',
				'readingTime' => '3 min',
		],
		[
				'id' => 'a6',
				'category' => 'Environnement',
				'title' => 'Forets seches: les communes testent un modele de gestion partagee',
				'excerpt' => 'Etat, associations et habitants signent un pacte sur cinq ans.',
				'author' => 'Hoby R.',
				'publishedAt' => '2026-03-28T14:25:00',
				'readingTime' => '5 min',
		],
		[
				'id' => 'a7',
				'category' => 'International',
				'title' => 'Canal du Mozambique: nouveaux equilibres dans le transport maritime',
				'excerpt' => 'Les flux logistiques evoluent avec la hausse des couts d assurance.',
				'author' => 'Soa M.',
				'publishedAt' => '2026-03-28T11:05:00',
				'readingTime' => '4 min',
		],
		[
				'id' => 'a8',
				'category' => 'Sport',
				'title' => 'Basket national: un championnat plus court mais plus intense',
				'excerpt' => 'Le nouveau calendrier promet des affiches plus lisibles.',
				'author' => 'Laza F.',
				'publishedAt' => '2026-03-27T19:40:00',
				'readingTime' => '3 min',
		],
];
?>
<?php
renderFrontStylesheet('/assets/front/article-card.css');
renderFrontStylesheet('/assets/front/feed.css');
?>

<section class="news-feed" aria-label="Fil d actualite">
	<div class="news-feed-topline">
		<p class="news-feed-kicker">A la une</p>
		<p class="news-feed-edition">Edition du dimanche</p>
	</div>

	<div class="news-feed-hero-layout">
		<?php renderArticleCard($featuredArticle, 'featured'); ?>

		<aside class="news-feed-latest" aria-label="Dernieres infos">
			<h2 class="news-feed-latest-title">Dernieres informations</h2>
			<div class="news-feed-latest-list">
				<?php foreach ($latestArticles as $article): ?>
					<?php renderArticleCard($article, 'compact'); ?>
				<?php endforeach; ?>
			</div>
		</aside>
	</div>

	<section class="news-feed-spotlight" aria-label="selection de la redaction">
		<div class="news-feed-spotlight-header">
			<h2>Selection de la redaction</h2>
			<span>4 sujets a lire</span>
		</div>
		<div class="news-feed-spotlight-grid">
			<?php foreach ($spotlightArticles as $article): ?>
				<?php renderArticleCard($article, 'standard'); ?>
			<?php endforeach; ?>
		</div>
	</section>
</section>
