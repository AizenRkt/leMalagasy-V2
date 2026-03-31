<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Espace de rédaction - Créez de nouveaux articles avec un éditeur riche et optimisé pour le référencement.">
    <title><?= htmlspecialchars($title ?? 'Rédiger une actualité', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link href="<?= htmlspecialchars(asset_url('/assets/vendor/quill/quill.snow.css'), ENT_QUOTES, 'UTF-8') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/assets/admin/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <?php require_once base_path('app/views/admin/partials/sidebar.php'); ?>
    <div class="container">
        <h1><?= htmlspecialchars($title ?? 'Rédiger une actualité', ENT_QUOTES, 'UTF-8') ?></h1>
        <form action="/admin/articles/create" method="POST" id="articleForm">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" name="title" id="title" required placeholder="Entrez le titre de l'actualité...">
            </div>

            <div class="form-group">
                <label for="summary">Résumé (facultatif)</label>
                <textarea name="summary" id="summary" rows="3" placeholder="Un court résumé pour les listes..."></textarea>
            </div>

            <div class="meta-grid">
                <div class="form-group">
                    <label for="category_id">Catégorie</label>
                    <select name="category_id" id="category_id">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_id">Auteur</label>
                    <select name="user_id" id="user_id">
                        <?php foreach($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Tags</label>
                <input type="text" id="tagSearch" class="tag-search" placeholder="Rechercher un tag...">
                <div class="tag-container" id="tagContainer">
                    <?php foreach($tags as $t): ?>
                        <label class="tag-pill">
                            <input type="checkbox" name="tag_ids[]" value="<?= $t['id'] ?>" class="tag-checkbox">
                            <?= htmlspecialchars($t['name'] ?? '') ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Contenu de l'article</label>
                <div id="editor"></div>
                <input type="hidden" name="content" id="hiddenContent">
            </div>

            <button type="submit" class="btn">Publier l'actualité</button>
            <p><a href="/admin/dashboard" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">Annuler et revenir</a></p>
        </form>
    </div>

    <script src="<?= htmlspecialchars(asset_url('/assets/vendor/quill/quill.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image', 'video'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            },
            placeholder: 'Commencez à rédiger votre actualité...'
        });

        var form = document.querySelector('#articleForm');
        form.onsubmit = function() {
            var content = document.querySelector('#hiddenContent');
            var editorHtml = quill.root.innerHTML;
            
            // SEO: Automatically add alt text to images if missing
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = editorHtml;
            var images = tempDiv.querySelectorAll('img');
            var articleTitle = document.getElementById('title').value || 'Actualité';
            
            images.forEach(function(img) {
                if (!img.getAttribute('alt')) {
                    img.setAttribute('alt', 'Illustration de : ' + articleTitle);
                }
            });
            
            content.value = tempDiv.innerHTML;
            return true;
        };

        const tagSearch = document.getElementById('tagSearch');
        const tagContainer = document.getElementById('tagContainer');
        const tagPills = document.querySelectorAll('.tag-pill');

        tagSearch.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            tagPills.forEach(pill => {
                const text = pill.textContent.trim().toLowerCase();
                pill.style.display = text.includes(term) ? 'flex' : 'none';
            });
        });

        tagPills.forEach(pill => {
            const checkbox = pill.querySelector('input');
            pill.addEventListener('click', () => {
                checkbox.checked = !checkbox.checked;
                pill.classList.toggle('active', checkbox.checked);
            });
        });
    </script>
</body>
</html>
