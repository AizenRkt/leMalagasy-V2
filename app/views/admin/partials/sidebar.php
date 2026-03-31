<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$currentAdmin = admin_user();

$menuItems = [
    ['label' => 'Dashboard', 'href' => '/admin/dashboard', 'prefixes' => ['/admin/dashboard']],
    ['label' => 'Gestion des articles', 'href' => '/admin/articles', 'prefixes' => ['/admin/articles']],
    ['label' => 'Gestion feed home', 'href' => '/admin/feed', 'prefixes' => ['/admin/feed']],
    ['label' => 'Articles phares categorie', 'href' => '/admin/category-featured', 'prefixes' => ['/admin/category-featured']],
    ['label' => 'Gestion categories', 'href' => '/admin/categories', 'prefixes' => ['/admin/categories', '/admin/taxonomy']],
    ['label' => 'Gestion tags', 'href' => '/admin/tags', 'prefixes' => ['/admin/tags', '/admin/taxonomy']],
];

$isItemActive = static function (array $prefixes, string $path): bool {
    foreach ($prefixes as $prefix) {
        if ($prefix !== '' && str_starts_with($path, $prefix)) {
            return true;
        }
    }

    return false;
};
?>

<aside class="admin-sidebar" aria-label="Menu administration">
    <h2 class="admin-sidebar-title">Admin leMalagasy</h2>
    <?php if (is_array($currentAdmin)): ?>
        <p class="admin-sidebar-user"><?= htmlspecialchars((string) ($currentAdmin['name'] ?? 'Admin'), ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <nav class="admin-sidebar-nav">
        <?php foreach ($menuItems as $item): ?>
            <?php $active = $isItemActive($item['prefixes'], $currentPath); ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="admin-sidebar-link <?= $active ? 'is-active' : '' ?>">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>

        <form method="post" action="/admin/logout" class="admin-sidebar-logout-form">
            <button type="submit" class="admin-sidebar-link admin-sidebar-logout">Se deconnecter</button>
        </form>
    </nav>
</aside>
