<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Connexion admin', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/assets/admin/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
    <style>
        body.admin-auth {
            padding-left: 1rem !important;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }

        .admin-auth-card {
            width: min(460px, 92vw);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.9rem;
            padding: 1.4rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .admin-auth-card h1 {
            margin-bottom: 0.8rem;
        }

        .admin-auth-help {
            margin: 0 0 1rem;
            color: #475569;
            font-size: 0.92rem;
        }

        .admin-auth-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 0.5rem;
            padding: 0.65rem 0.8rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .admin-auth-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .admin-auth-actions a {
            color: #334155;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .admin-auth-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="admin-auth">
    <main class="admin-auth-card" aria-label="Connexion administration">
        <h1><?= htmlspecialchars($title ?? 'Connexion admin', ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="admin-auth-help">Connectez-vous avec votre email admin et le mot de passe d administration.</p>

        <?php if (!empty($error)): ?>
            <p class="admin-auth-error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="/admin/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="rado.mihaja@lemalagasy.com" autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" value="admin123" required autocomplete="current-password">
            </div>

            <div class="admin-auth-actions">
                <a href="/">Retour au site</a>
                <button class="btn" type="submit">Se connecter</button>
            </div>
        </form>
    </main>
</body>
</html>
