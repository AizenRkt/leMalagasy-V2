# Separation CSS des composants front

Date: 2026-03-30

## Objectif

Sortir les styles inline de `Feed` et `ArticleCard` vers des fichiers CSS dans `public/assets/front`.

## Changements realises

- Creation de `public/assets/front/feed.css`
  - Contient les styles de structure du composant feed (`news-feed*`).

- Creation de `public/assets/front/article-card.css`
  - Contient les styles du composant article card (`news-article-card*`) et les media queries associees.

- Mise a jour de `app/views/front/components/Feed.php`
  - Suppression du bloc `<style>` inline.
  - Ajout d'un chargeur `renderFrontStylesheet()` pour inclure:
    - `/assets/front/article-card.css`
    - `/assets/front/feed.css`
  - Le chargeur evite les inclusions multiples pendant un meme rendu.

- Mise a jour de `app/views/front/components/ArticleCard.php`
  - Suppression du renderer CSS inline.
  - Chargement de `/assets/front/article-card.css` via `renderFrontStylesheet()`.

## Pourquoi c'est mieux

- Separation claire entre structure PHP et presentation CSS.
- Styles reutilisables et cache navigateur plus efficace.
- Maintenance plus simple des composants front.

## Notes

- Les chemins `/assets/front/...` sont servis depuis `public/assets/front` avec la config Apache actuelle.
- Si le composant est rendu plusieurs fois, les liens CSS ne sont imprimes qu'une seule fois par requete.

## Correctif d integration Feed -> ArticleCard

Date: 2026-03-30

- `Feed.php` utilise maintenant `ArticleCard.php` comme composant source unique via `require_once`.
- La duplication locale de `renderArticleCard`, `esc` et `formatDateFr` a ete retiree de `Feed.php`.
- `ArticleCard.php` n'effectue plus de rendu automatique a l'import (sauf si `$renderArticleCardDirect === true`).

Impact:

- Un seul point de maintenance pour le rendu des cartes article.
- Plus de risque de divergence entre les deux fichiers.

## Integration Feed sur Home

Date: 2026-03-30

- `app/views/front/home.php` charge maintenant `app/views/front/components/Feed.php`.
- Le rendu du feed apparait donc dans la zone centrale du layout front, entre la Navbar et le Footer.

## Integration singleArticle avec les composants

Date: 2026-03-30

- Route front ajoutee: `/article` dans `routes/web.php`.
- Controleur corrige: `singleArticle()` pointe maintenant vers `front/singleArticle`.
- `singleArticle.php` reutilise les fonctions partagees de `ArticleCard.php` (`newsEsc`, `newsFormatDateFr`, `renderFrontStylesheet`).
- CSS de la page extrait vers `public/assets/front/single-article.css` et charge via `renderFrontStylesheet`.

Impact:

- La page article est connectee au meme systeme de composants/utilitaires que le reste du front.
- La maintenance est simplifiee grace a la suppression de duplication (helpers + style inline).

## Integration singleCategory avec les composants

Date: 2026-03-30

- Route front ajoutee: `/category` dans `routes/web.php`.
- Controleur corrige: `singleCategory()` pointe vers `front/singleCategory`.
- `singleCategory.php` reutilise les helpers partages via `ArticleCard.php` (`newsEsc`, `newsFormatDateFr`, `renderFrontStylesheet`).
- CSS de la page extrait vers `public/assets/front/single-category.css` et charge via `renderFrontStylesheet`.
- Liens internes de la categorie rediriges vers `/article` pour s'integrer au parcours front.

## Menu dynamique via CategoryService

Date: 2026-03-30

- Creation de `app/services/CategoryService.php` sur le pattern des services de donnees (acces PostgreSQL + modele `Category`).
- Methode principale ajoutee: `listForMenu(int $limit = 8)` pour alimenter la navigation front.
- `HomeController` injecte maintenant `menuItems` dynamiques vers les vues front via `frontCommonData()`.
- `Navbar.php` utilise les donnees `menuItems` recues, avec fallback statique si aucune categorie n'est disponible.

Impact:

- Les elements de navigation peuvent maintenant refleter les categories de la base.
- Le menu est centralise cote service/controleur au lieu d'etre fige dans la vue.

## singleArticle dynamique via service

Date: 2026-03-30

- `ArticleService` expose maintenant `getFrontArticle(?int $id)` pour construire un payload front depuis PostgreSQL + MongoDB.
- Le service reconstruit les champs de page (`title`, `standfirst`, `author`, `category`, `tags`, `contentHtml`, etc.) depuis les donnees mongo (`titre`, `contenu`, `auteur`, `categorie`, `tags`).
- `HomeController::singleArticle()` lit `id` depuis la query string (`/article?id=...`) et injecte les donnees dynamiques vers la vue.
- La vue `front/singleArticle.php` n'utilise plus de contenu hardcode; elle affiche le contenu HTML de MongoDB et les metadonnees dynamiques.

## Stockage des images d'article hors base

Date: 2026-03-30

- Le flux `create/update` d'`ArticleService` traite maintenant le HTML du contenu avant ecriture MongoDB.
- Les images embeddees en base64 (`data:image/...`) sont extraites et enregistrees dans `storage/uploads`.
- Les `src` des balises `<img>` sont remplaces par un chemin fichier (`/storage/uploads/<nom_fichier>`).

Impact:

- Le contenu MongoDB ne transporte plus les blobs d'image en base64.
- Le stockage est plus leger et la maintenance des medias est simplifiee.

Mise a jour:

- Les chemins d'images remplaces utilisent maintenant `config('app.base_url')` pour generer une URL absolue (ex: `http://localhost:8080/storage/uploads/...`).
