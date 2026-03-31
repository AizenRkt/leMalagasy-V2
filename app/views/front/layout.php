<!doctype html>
<html lang="fr">
<head>
    <?php
    $siteName = 'Le Malagasy';
    $defaultDescription = 'Le Malagasy: actualites, analyses et reportages sur Madagascar et le monde.';

    $seo = (isset($seo) && is_array($seo)) ? $seo : [];

    $titleValue = trim((string) ($seo['title'] ?? $title ?? $siteName));
    $metaTitle = $titleValue === '' ? $siteName : $titleValue;
    if (mb_stripos($metaTitle, $siteName, 0, 'UTF-8') === false) {
        $metaTitle .= ' | ' . $siteName;
    }

    $metaDescription = trim((string) ($seo['description'] ?? $defaultDescription));
    if ($metaDescription === '') {
        $metaDescription = $defaultDescription;
    }

    $canonicalUrl = trim((string) ($seo['canonical'] ?? current_url()));
    if ($canonicalUrl === '') {
        $canonicalUrl = current_url();
    }

    $ogType = trim((string) ($seo['type'] ?? 'website'));
    if ($ogType === '') {
        $ogType = 'website';
    }

    $socialImage = trim((string) ($seo['image'] ?? absolute_url(asset_url('/assets/front/logo.png'))));
    if ($socialImage === '') {
        $socialImage = absolute_url(asset_url('/assets/front/logo.png'));
    }
    if ($socialImage !== '' && str_starts_with($socialImage, '/')) {
        $socialImage = absolute_url($socialImage);
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">

    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($socialImage, ENT_QUOTES, 'UTF-8') ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($socialImage, ENT_QUOTES, 'UTF-8') ?>">
    <style>
        :root {
            --page-bg: #f8f7f4;
            --content-ink: #1d252e;
            --content-line: #d9dde3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--content-ink);
            font-family: Georgia, 'Times New Roman', serif;
        }

        .skip-link {
            position: absolute;
            left: 12px;
            top: -48px;
            z-index: 2000;
            padding: 10px 14px;
            border-radius: 6px;
            background: #111a23;
            color: #ffffff;
            text-decoration: none;
            font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
            font-weight: 700;
            transition: top 160ms ease;
        }

        .skip-link:focus {
            top: 10px;
        }

        a:focus-visible,
        button:focus-visible,
        [role="button"]:focus-visible {
            outline: 3px solid #0b57d0;
            outline-offset: 2px;
        }

        .page-content {
            width: min(1100px, 92vw);
            margin: 24px auto;
            background: #ffffff;
            border: 1px solid var(--content-line);
            border-radius: 8px;
            padding: 24px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (max-width: 760px) {
            .page-content {
                margin: 16px auto;
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#main-content">Aller au contenu principal</a>
    <?php require base_path('app/views/front/partials/Navbar.php'); ?>

    <main id="main-content" class="page-content">
        <?= $content ?>
    </main>

    <?php require base_path('app/views/front/partials/Footer.php'); ?>
</body>
</html>
