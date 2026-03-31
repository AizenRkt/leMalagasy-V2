<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Espace de rédaction - Créez de nouveaux articles avec un éditeur riche et optimisé pour le référencement.">
    <title><?= htmlspecialchars($title ?? 'Rédiger une actualité', ENT_QUOTES, 'UTF-8') ?> | leMalagasy</title>
    <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; font-size: 1.875rem; font-weight: 700; color: #111827; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
        input[type="text"], textarea, select { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; box-sizing: border-box; }
        #editor { height: 400px; margin-bottom: 1rem; border-radius: 0 0 0.5rem 0.5rem; }
        .ql-toolbar { border-radius: 0.5rem 0.5rem 0 0; background: #f1f5f9; }
        .btn { background: var(--primary); color: #fff; padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 1rem; transition: background 0.2s; }
        .btn:hover { background: var(--primary-hover); }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        
        /* Tag Pills */
        .tag-container { display: flex; flex-wrap: wrap; gap: 0.5rem; border: 1px solid #d1d5db; padding: 1rem; border-radius: 0.5rem; background: #fff; max-height: 200px; overflow-y: auto; }
        .tag-pill { display: flex; align-items: center; padding: 0.375rem 0.75rem; background: #f1f5f9; border-radius: 9999px; cursor: pointer; font-size: 0.875rem; border: 1px solid transparent; transition: all 0.2s; user-select: none; }
        .tag-pill:hover { background: #e2e8f0; }
        .tag-pill.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .tag-pill input { display: none; }
        .tag-search { margin-bottom: 0.75rem; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; width: 100%; font-size: 0.875rem; }
    </style>
</head>
<body>
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

    <script src="/assets/vendor/quill/quill.js"></script>
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
