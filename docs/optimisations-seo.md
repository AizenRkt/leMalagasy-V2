# Optimisations et SEO (leMalagasy)

## SEO
- Ajout des meta essentiels (title, description, canonical) avec fallback propre.
- Balises Open Graph + Twitter Card pour un meilleur partage social.
- Robots.txt avec sitemap et exclusion de /admin/.
- Sitemap.xml pour l indexation.
- Donnees structurees (schema) pretes pour enrichir les resultats.

## Accessibilite
- Lien d evasion (skip link) vers le contenu principal.
- Hierarchie des titres corrigee (H1/H2/H3 coherents).
- Contraste du footer renforce.
- Styles focus-visible ajoutes pour la navigation clavier.

## Performance front
- CSS critiques inline pour l above-the-fold.
- CSS non critiques chargees en mode non bloquant (preload + onload).
- Bundling du CSS (home-feed.bundle.css).
- Versioning des assets via filemtime (asset_url) pour le cache busting.

## Performance serveur
- Compression Brotli + Deflate activees.
- Cache long pour assets statiques (immutable 1 an).
- No-cache sur HTML/PHP pour eviter les pages obsoletes.

## Divers
- Optimisations sur la page article (overflow, recadrage images, contenus plus robustes).
- Auth admin securisee (login/logout, session, middleware) pour la zone /admin/.
