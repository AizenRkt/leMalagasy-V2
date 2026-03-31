<?php
require_once base_path('app/views/front/components/ArticleCard.php');

$featuredArticle = $featuredArticle ?? null;
$latestArticles = (isset($latestArticles) && is_array($latestArticles)) ? $latestArticles : [];
$spotlightArticles = (isset($spotlightArticles) && is_array($spotlightArticles)) ? $spotlightArticles : [];
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
		<section class="news-feed-featured" aria-label="Article a la une">
			<h2 class="sr-only">Article a la une</h2>
			<?php if (is_array($featuredArticle)): ?>
				<?php renderArticleCard($featuredArticle, 'featured'); ?>
			<?php endif; ?>
		</section>

		<aside class="news-feed-latest" aria-label="Dernieres infos">
			<h2 class="news-feed-latest-title">Dernieres informations</h2>
			<div class="news-feed-latest-list">
				<?php if ($latestArticles === []): ?>
					<p>Aucun article recent disponible.</p>
				<?php else: ?>
					<?php foreach ($latestArticles as $article): ?>
						<?php renderArticleCard($article, 'compact'); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</aside>
	</div>

	<section class="news-feed-spotlight" aria-label="selection de la redaction">
		<div class="news-feed-spotlight-header">
			<h2>Séléction de la redaction</h2>
			<span><?= newsEsc((string) count($spotlightArticles)) ?> sujets a lire</span>
		</div>
		<div class="news-feed-spotlight-grid">
			<?php if ($spotlightArticles === []): ?>
				<p>Aucun article en selection pour le moment.</p>
			<?php else: ?>
				<?php foreach ($spotlightArticles as $article): ?>
					<?php renderArticleCard($article, 'standard'); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
</section>
