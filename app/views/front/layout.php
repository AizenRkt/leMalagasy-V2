<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($title ?? 'Le Malagasy'), ENT_QUOTES, 'UTF-8') ?></title>
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

        .page-content {
            width: min(1100px, 92vw);
            margin: 24px auto;
            background: #ffffff;
            border: 1px solid var(--content-line);
            border-radius: 8px;
            padding: 24px;
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
    <?php require base_path('app/views/front/partials/Navbar.php'); ?>

    <main class="page-content">
        <?= $content ?>
    </main>

    <?php require base_path('app/views/front/partials/Footer.php'); ?>
</body>
</html>
