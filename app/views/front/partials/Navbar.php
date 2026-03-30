<?php
$menuItems = [
		['label' => 'International', 'href' => '#home'],
		['label' => 'Politique', 'href' => '#home'],
		['label' => 'Economie', 'href' => '#home'],
		['label' => 'Culture', 'href' => '#home'],
		['label' => 'Sports', 'href' => '#home'],
		['label' => 'Societe', 'href' => '#home'],
		['label' => 'Categorie', 'href' => '#category'],
		['label' => 'Article', 'href' => '#article'],
];

$liveItems = [
		[
				'id' => 'l1',
				'time' => '06:23',
				'label' => 'Live en cours',
				'title' => 'Guerre au Moyen-Orient : les dernieres informations',
		],
		[
				'id' => 'l2',
				'time' => '06:00',
				'title' => 'Adrien Bilal est le laureat du Prix du meilleur jeune economiste 2026',
		],
		[
				'id' => 'l3',
				'time' => '05:45',
				'title' => "Un avion d'Air China decolle pour Pyongyang une premiere depuis 2020",
		],
		[
				'id' => 'l4',
				'time' => '05:35',
				'label' => 'Alerte',
				'title' => 'Entre attaques racistes et alternances sous tension, les nouveaux elus installent un climat electrique',
		],
		[
				'id' => 'l5',
				'time' => '01:54',
				'title' => 'Serbie : le president Aleksandar Vucic proclame la victoire aux municipales',
		],
];
?>

<style>
	.news-navbar-wrapper {
		border-top: 1px solid var(--line, #d9dde3);
		border-bottom: 1px solid var(--line, #d9dde3);
		background: rgba(252, 251, 248, 0.95);
		backdrop-filter: blur(4px);
	}

	.news-navbar-meta {
		width: min(1100px, 92vw);
		margin: 0 auto;
		padding: 6px 0;
		display: flex;
		align-items: center;
		gap: 10px;
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 12px;
		letter-spacing: 0.02em;
		color: var(--ink-500, #5c6670);
	}

	.news-navbar-brand-row {
		width: min(1100px, 92vw);
		margin: 0 auto;
		padding: 14px 0 12px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		border-top: 1px solid var(--line, #d9dde3);
	}

	.news-navbar-brand {
		text-decoration: none;
		font-size: clamp(30px, 4.3vw, 50px);
		line-height: 1;
		font-weight: 700;
		letter-spacing: 0.01em;
		color: var(--ink-900, #111a23);
	}

	.news-navbar-subscribe {
		border: 1px solid var(--ink-900, #111a23);
		background: var(--ink-900, #111a23);
		color: #fff;
		border-radius: 999px;
		padding: 8px 16px;
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 13px;
		cursor: pointer;
		transition: transform 180ms ease, opacity 180ms ease;
	}

	.news-navbar-subscribe:hover {
		transform: translateY(-1px);
		opacity: 0.92;
	}

	.news-navbar-menu {
		width: min(1100px, 92vw);
		margin: 0 auto;
		padding: 10px 0;
		border-top: 1px solid var(--line, #d9dde3);
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 14px;
	}

	.news-menu-link {
		text-decoration: none;
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 13px;
		font-weight: 600;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		color: var(--ink-700, #2f3a45);
	}

	.news-menu-link.is-active,
	.news-menu-link:hover {
		color: var(--ink-900, #111a23);
	}

	.news-live-strip {
		border-top: 1px solid var(--line, #d9dde3);
		border-bottom: 1px solid var(--line, #d9dde3);
		background: #f1f2f4;
	}

	.news-live-track {
		width: min(1200px, 96vw);
		margin: 0 auto;
		display: grid;
		grid-auto-flow: column;
		grid-auto-columns: minmax(260px, 1fr);
		align-items: stretch;
		overflow-x: auto;
		scroll-behavior: smooth;
		scrollbar-width: none;
		-ms-overflow-style: none;
		cursor: grab;
	}

	.news-live-track:active {
		cursor: grabbing;
	}

	.news-live-track::-webkit-scrollbar {
		display: none;
	}

	.news-live-item {
		padding: 10px 16px 11px;
		border-right: 1px solid #d7dbe0;
		transition: background-color 200ms ease, transform 150ms ease;
	}

	.news-live-item:hover {
		background-color: rgba(255, 255, 255, 0.5);
		transform: translateY(-1px);
	}

	.news-live-meta {
		margin: 0 0 6px;
		display: flex;
		align-items: center;
		gap: 8px;
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 11px;
	}

	.news-live-time {
		color: #111a23;
		font-weight: 700;
	}

	.news-live-label {
		color: #d22121;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		font-weight: 700;
	}

	.news-live-title {
		margin: 0;
		font-size: 16px;
		line-height: 1.32;
		font-weight: 500;
		color: #1a2430;
	}

	.news-live-more {
		margin: auto 14px;
		height: 36px;
		padding: 0 18px;
		border: 1px solid #bcc5cf;
		border-radius: 999px;
		background: #f8f9fb;
		color: #1a2430;
		font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 13px;
		font-weight: 600;
		white-space: nowrap;
		cursor: pointer;
		transition: background-color 180ms ease, transform 150ms ease;
	}

	.news-live-more:hover {
		background: #ffffff;
		transform: translateY(-1px);
	}

	@media (max-width: 820px) {
		.news-navbar-meta {
			font-size: 11px;
		}

		.news-navbar-brand-row {
			padding: 14px 0 12px;
		}

		.news-navbar-subscribe {
			padding: 7px 12px;
			font-size: 12px;
		}
	}

	@media (max-width: 760px) {
		.news-live-track {
			grid-auto-columns: 85vw;
		}

		.news-live-title {
			font-size: 14px;
		}

		.news-live-item {
			padding: 9px 14px 10px;
		}
	}
</style>

<header class="news-navbar-wrapper">
	<div class="news-navbar-meta" aria-label="Informations edition">
		<span class="news-meta-item">Edition numerique</span>
		<span class="news-meta-divider" aria-hidden="true">|</span>
		<span class="news-meta-item">Mis a jour: 29 mars 2026</span>
	</div>

	<div class="news-navbar-brand-row">
		<a href="#home" class="news-navbar-brand" aria-label="Le Malagasy accueil">Le Malagasy</a>
		<button class="news-navbar-subscribe" type="button">Soutenir</button>
	</div>

	<nav class="news-navbar-menu" aria-label="Navigation principale">
		<a href="#home" class="news-menu-link is-active">Actu</a>
		<?php foreach ($menuItems as $item): ?>
			<a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="news-menu-link">
				<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<section class="news-live-strip" aria-label="En continu">
		<div class="news-live-track" data-live-track>
			<?php foreach ($liveItems as $item): ?>
				<article class="news-live-item" data-id="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>">
					<p class="news-live-meta">
						<span class="news-live-time"><?= htmlspecialchars($item['time'], ENT_QUOTES, 'UTF-8') ?></span>
						<?php if (!empty($item['label'])): ?>
							<span class="news-live-label"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
						<?php endif; ?>
					</p>
					<h3 class="news-live-title"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
				</article>
			<?php endforeach; ?>

			<button type="button" class="news-live-more">Voir plus</button>
		</div>
	</section>
</header>

<script>
	(function () {
		var track = document.querySelector('[data-live-track]');
		if (!track) return;

		track.addEventListener(
			'wheel',
			function (event) {
				if (track.scrollWidth <= track.clientWidth) return;

				event.preventDefault();
				track.scrollLeft += event.deltaY > 0 ? 80 : -80;
			},
			{ passive: false }
		);
	})();
</script>
