<?php
$rubriques = ['Actualites', 'Opinions', 'Economie'];
$aPropos = ['La redaction', 'Contact', 'Mentions legales'];
$createurs = ['ETU003289 Sanda', 'ETU003658 Patrick'];
?>

<style>
	.news-footer {
		margin-top: 40px;
		border-top: 2px solid #3f5546;
		background: #5f7a68;
		color: #ffffff;
	}

	.news-footer-grid {
		width: min(1100px, 92vw);
		margin: 0 auto;
		padding: 28px 0 18px;
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 28px;
	}

	.news-footer-title {
		margin: 0 0 10px;
		font-size: 16px;
		color: #ffffff;
	}

	.news-footer-text {
		margin: 0;
		color: rgba(255, 255, 255, 0.92);
		line-height: 1.5;
	}

	.news-footer-list {
		list-style: none;
		margin: 0;
		padding: 0;
		display: grid;
		gap: 8px;
	}

	.news-footer-list a {
		text-decoration: none;
		color: rgba(255, 255, 255, 0.92);
	}

	.news-footer-list a:hover {
		color: #ffffff;
		text-decoration: underline;
		text-decoration-color: rgba(255, 255, 255, 0.75);
		text-underline-offset: 2px;
	}

	.news-footer-bottom {
		width: min(1100px, 92vw);
		margin: 0 auto;
		border-top: 1px solid rgba(255, 255, 255, 0.35);
		padding: 12px 0 20px;
		color: rgba(255, 255, 255, 0.86);
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 12px;
	}

	@media (max-width: 820px) {
		.news-footer-grid {
			grid-template-columns: 1fr;
			gap: 18px;
		}
	}
</style>

<footer class="news-footer" aria-label="Pied de page">
	<div class="news-footer-grid">
		<section>
			<h3 class="news-footer-title">Le Malagasy</h3>
			<p class="news-footer-text">
				Quotidien numerique consacre a l'actualite, aux analyses et aux grands dossiers.
			</p>
		</section>

		<section>
			<h3 class="news-footer-title">Rubriques</h3>
			<ul class="news-footer-list">
				<?php foreach ($rubriques as $item): ?>
					<li><a href="#"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></a></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<section>
			<h3 class="news-footer-title">A propos</h3>
			<ul class="news-footer-list">
				<?php foreach ($aPropos as $item): ?>
					<li><a href="#"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></a></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<section>
			<h3 class="news-footer-title">Createur</h3>
			<ul class="news-footer-list">
				<?php foreach ($createurs as $item): ?>
					<li><a href="#"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></a></li>
				<?php endforeach; ?>
			</ul>
		</section>
	</div>

	<div class="news-footer-bottom">
		<small>&copy; 2026 Le Malagasy. Tous droits reserves.</small>
	</div>
</footer>
