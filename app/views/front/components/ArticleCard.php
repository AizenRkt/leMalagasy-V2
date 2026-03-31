<?php
if (!function_exists('newsEsc')) {
		function newsEsc($value)
		{
				return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
		}
}

if (!function_exists('newsFormatDateFr')) {
		function newsFormatDateFr($inputDate)
		{
				if (empty($inputDate)) {
						return '';
				}

				try {
						$date = new DateTime((string) $inputDate);
				} catch (Exception $e) {
						return '';
				}

				if (class_exists('IntlDateFormatter')) {
						$formatter = new IntlDateFormatter(
								'fr_FR',
								IntlDateFormatter::LONG,
								IntlDateFormatter::NONE,
								$date->getTimezone()->getName(),
								IntlDateFormatter::GREGORIAN,
								'dd MMMM yyyy'
						);

						$formatted = $formatter->format($date);
						if ($formatted !== false) {
								return $formatted;
						}
				}

				$months = [
						1 => 'janvier',
						2 => 'fevrier',
						3 => 'mars',
						4 => 'avril',
						5 => 'mai',
						6 => 'juin',
						7 => 'juillet',
						8 => 'aout',
						9 => 'septembre',
						10 => 'octobre',
						11 => 'novembre',
						12 => 'decembre',
				];

				$day = $date->format('d');
				$month = $months[(int) $date->format('n')] ?? '';
				$year = $date->format('Y');

				return trim($day . ' ' . $month . ' ' . $year);
		}
}

if (!function_exists('renderFrontStylesheet')) {
		function renderFrontStylesheet($href)
		{
				static $printed = [];
				if (!is_string($href) || $href === '' || isset($printed[$href])) {
						return;
				}

				$printed[$href] = true;
				echo '<link rel="stylesheet" href="' . newsEsc(asset_url($href)) . '">';
		}
}

if (!function_exists('renderArticleCard')) {
		function renderArticleCard($article = [], $variant = 'standard')
		{
				$articleId = isset($article['id']) ? (int) $article['id'] : 0;
				$link = $article['href'] ?? ($articleId > 0 ? article_url((string) ($article['title'] ?? ''), $articleId) : null);

				$safeArticle = [
						'category' => $article['category'] ?? 'Actualites',
						'title' => $article['title'] ?? 'Titre article',
						'excerpt' => $article['excerpt'] ?? 'Resume article',
						'author' => $article['author'] ?? 'Redaction',
						'publishedAt' => $article['publishedAt'] ?? date(DATE_ATOM),
						'readingTime' => $article['readingTime'] ?? '4 min',
						'image' => $article['image'] ?? null,
						'link' => is_string($link) && $link !== '' ? $link : null,
				];

				$allowedVariants = ['featured', 'standard', 'compact'];
				$finalVariant = in_array($variant, $allowedVariants, true) ? $variant : 'standard';
				$variantClass = 'is-' . $finalVariant;

				renderFrontStylesheet('/assets/front/article-card.css');
				?>
				<article class="news-article-card <?= newsEsc($variantClass) ?>">
					<div class="news-article-card-meta">
						<span class="news-article-card-category"><?= newsEsc($safeArticle['category']) ?></span>
						<span><?= newsEsc(newsFormatDateFr($safeArticle['publishedAt'])) ?></span>
						<span><?= newsEsc($safeArticle['readingTime']) ?> de lecture</span>
					</div>

					<?php if ($finalVariant === 'featured' && !empty($safeArticle['image'])): ?>
						<figure class="news-article-card-featured-media">
							<?php if ($safeArticle['link'] !== null): ?>
								<a class="news-article-card-link" href="<?= newsEsc($safeArticle['link']) ?>" aria-label="Lire l article: <?= newsEsc($safeArticle['title']) ?>">
									<img src="<?= newsEsc($safeArticle['image']) ?>" alt="<?= newsEsc($safeArticle['title']) ?>" loading="lazy">
								</a>
							<?php else: ?>
								<img src="<?= newsEsc($safeArticle['image']) ?>" alt="<?= newsEsc($safeArticle['title']) ?>" loading="lazy">
							<?php endif; ?>
						</figure>
					<?php endif; ?>

					<h3 class="news-article-card-title">
						<?php if ($safeArticle['link'] !== null): ?>
							<a class="news-article-card-link" href="<?= newsEsc($safeArticle['link']) ?>"><?= newsEsc($safeArticle['title']) ?></a>
						<?php else: ?>
							<?= newsEsc($safeArticle['title']) ?>
						<?php endif; ?>
					</h3>
					<p class="news-article-card-excerpt"><?= newsEsc($safeArticle['excerpt']) ?></p>

					<div class="news-article-card-footer">
						<span>Par <?= newsEsc($safeArticle['author']) ?></span>
					</div>
				</article>
				<?php
		}
}

if (!empty($renderArticleCardDirect) && $renderArticleCardDirect === true) {
		$variant = $variant ?? 'standard';
		$article = $article ?? [];
		renderArticleCard($article, $variant);
}
?>
