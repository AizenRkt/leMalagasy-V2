# Layout Front - Mise en place et fonctionnement

Ce document explique comment le layout front a ete implemente, comment il fonctionne, et comment ajouter des vues qui s'y integrent correctement.

## Objectif

Avoir une structure commune sur les pages front:
- Navbar en haut
- Contenu de la page au centre
- Footer en bas

Cela evite de dupliquer le HTML global dans chaque vue.

## Fichiers utilises

- `app/core/helpers.php`
- `app/views/front/layout.php`
- `app/views/front/partials/Navbar.php`
- `app/views/front/partials/Footer.php`
- `app/views/front/home.php`
- `app/views/front/about.php`

## Ce qui a ete fait

1. Le helper `view()` a ete etendu dans `app/core/helpers.php`.
   - Il rend d'abord la vue demandee.
   - Il detecte ensuite si un layout doit etre applique.
   - Par defaut, toute vue qui commence par `front/` utilise `front/layout`.
   - Le contenu rendu est injecte dans la variable `$content` du layout.

2. Creation du layout global `app/views/front/layout.php`.
   - Le layout inclut directement:
     - `app/views/front/partials/Navbar.php`
     - le bloc principal `<main class="page-content">` avec `<?= $content ?>`
     - `app/views/front/partials/Footer.php`

3. Conversion des vues front en vues "contenu seulement".
   - `app/views/front/home.php` et `app/views/front/about.php` ne contiennent plus `<!doctype html>`, `<html>`, `<head>`, `<body>`.
   - Elles ne gardent que le contenu metier de la page.

## Flux d'execution

1. Le controleur appelle `view('front/home', [...])`.
2. `view()` rend le fichier `app/views/front/home.php` dans un buffer.
3. `view()` detecte `front/` et choisit automatiquement le layout `front/layout`.
4. Le layout est rendu avec:
   - `$content` = contenu de la vue
   - les autres donnees passees depuis le controleur (`$title`, etc.)
5. Le HTML final retourne au navigateur inclut Navbar + contenu + Footer.

## Forcer ou desactiver un layout

Tu peux controler le layout depuis le controleur avec la cle `_layout` dans les donnees:

```php
return view('front/home', [
    'title' => 'Accueil',
    '_layout' => 'front/layout', // force un layout precis
]);
```

Pour desactiver le layout:

```php
return view('front/home', [
    '_layout' => '', // renvoie uniquement le contenu de la vue
]);
```

## Ajouter une nouvelle page front

Exemple pour `front/contact`:

1. Creer `app/views/front/contact.php` avec uniquement le contenu de la page.
2. Ajouter une route vers le controleur.
3. Dans le controleur, faire `view('front/contact', [...])`.

Le layout sera applique automatiquement.

## Notes

- La partie admin n'est pas impactee par ce layout front.
- Si un fichier de layout est absent, `view()` leve une exception explicite (`Layout not found`).
