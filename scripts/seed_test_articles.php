<?php

declare(strict_types=1);

use App\Core\Autoloader;
use App\Core\Database;
use App\Models\Article;
use App\Services\ArticleService;

require_once __DIR__ . '/../app/core/Autoloader.php';
require_once __DIR__ . '/../app/core/helpers.php';

Autoloader::register();

$service = new ArticleService();
$db = Database::postgres();

$seedItems = [
    [
        'title' => '[TEST FEED] Diplomatie regionale: Antananarivo relance le dialogue maritime',
        'summary' => 'Le gouvernement annonce une coordination technique avec les pays riverains pour mieux securiser les routes commerciales.',
        'content' => '<p>Le ministere des affaires etrangeres a lance une serie de consultations regionales pour harmoniser la surveillance maritime et simplifier les procedures de transit.</p><p>Selon les autorites, cette approche doit reduire les couts logistiques et limiter les retards sur les flux d import-export.</p>',
    ],
    [
        'title' => '[TEST FEED] Energie: une feuille de route pour stabiliser l approvisionnement',
        'summary' => 'Un plan en trois phases vise a ameliorer la disponibilite en electricite dans les centres urbains et periurbains.',
        'content' => '<p>Le plan prevoit la modernisation de plusieurs postes de distribution et un calendrier de maintenance preventif plus strict.</p><p>Les entreprises locales attendent une meilleure previsibilite pour leurs activites industrielles.</p>',
    ],
    [
        'title' => '[TEST FEED] Filiere rizicole: vers une hausse de productivite en 2026',
        'summary' => 'Les cooperatives testent de nouveaux itineraires techniques pour augmenter les rendements sans alourdir les charges.',
        'content' => '<p>Des essais pilotes sont en cours dans plusieurs regions afin de comparer les pratiques culturales et la qualite des semences.</p><p>Les resultats preliminaires montrent une progression encourageante des rendements.</p>',
    ],
    [
        'title' => '[TEST FEED] Education numerique: cinq academies passent en mode hybride',
        'summary' => 'Le ministere deploye des ressources en ligne pour completer les cours en presentiel dans les lycees cibles.',
        'content' => '<p>Les etablissements selectionnes recevront un accompagnement technique et pedagogique sur deux semestres.</p><p>L objectif est de mieux adapter les rythmes d apprentissage et de renforcer les competences numeriques.</p>',
    ],
    [
        'title' => '[TEST FEED] Export textile: les ateliers locaux gagnent des parts de marche',
        'summary' => 'La filiere enregistre une reprise des commandes grace a une meilleure coordination entre producteurs et logisticiens.',
        'content' => '<p>Les industriels soulignent l importance des delais de livraison et de la qualite de finition pour conserver leur competitivite.</p><p>Un programme de formation continue est en preparation pour les equipes de production.</p>',
    ],
    [
        'title' => '[TEST FEED] Sante publique: campagne de prevention renforcee en milieu rural',
        'summary' => 'Les centres de sante communautaires intensifient les actions de sensibilisation et de depistage de proximite.',
        'content' => '<p>Les equipes mobiles couvrent des zones difficilement accessibles afin d ameliorer la detection precoce des cas prioritaires.</p><p>Les premiers retours indiquent une hausse de frequentation des centres de consultation.</p>',
    ],
    [
        'title' => '[TEST FEED] Tourisme durable: nouveaux circuits dans les hauts plateaux',
        'summary' => 'Des acteurs locaux construisent une offre axee sur le patrimoine, l artisanat et l experience communautaire.',
        'content' => '<p>Les communes partenaires souhaitent allonger la duree moyenne des sejours tout en preservant les ressources locales.</p><p>Des standards de qualite sont en cours d adoption pour structurer l accueil.</p>',
    ],
    [
        'title' => '[TEST FEED] Fintech: les paiements mobiles poursuivent leur croissance',
        'summary' => 'Le nombre de transactions augmente dans les zones urbaines, avec une adoption progressive chez les petits commerces.',
        'content' => '<p>Les operateurs investissent dans la fiabilite des plateformes et dans la formation des marchands aux bonnes pratiques numeriques.</p><p>Le secteur anticipe une acceleration des usages avec l extension des services de proximite.</p>',
    ],
];

$inserted = 0;
$skipped = 0;

for ($i = 0; $i < count($seedItems); $i++) {
    $item = $seedItems[$i];

    $existsStmt = $db->prepare('SELECT id FROM article WHERE title = ? LIMIT 1');
    $existsStmt->execute([$item['title']]);
    $existingId = $existsStmt->fetchColumn();

    if ($existingId !== false) {
        $skipped++;
        echo 'SKIP: ' . $item['title'] . PHP_EOL;
        continue;
    }

    $categoryId = ($i % 7) + 1;
    $authorId = ($i % 5) + 1;
    $tagIds = [($i % 12) + 1, (($i + 4) % 12) + 1];

    $categoryStmt = $db->prepare('SELECT id, name AS nom FROM categorie WHERE id = ? LIMIT 1');
    $categoryStmt->execute([$categoryId]);
    $category = $categoryStmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'nom' => 'International'];

    $authorStmt = $db->prepare('SELECT id, name AS nom FROM utilisateur WHERE id = ? LIMIT 1');
    $authorStmt->execute([$authorId]);
    $author = $authorStmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'nom' => 'Redaction'];

    $placeholders = implode(',', array_fill(0, count($tagIds), '?'));
    $tagsStmt = $db->prepare("SELECT id, name AS nom FROM tag WHERE id IN ($placeholders)");
    $tagsStmt->execute($tagIds);
    $tags = $tagsStmt->fetchAll(PDO::FETCH_ASSOC);

    $article = new Article(
        null,
        $item['title'],
        $item['summary'],
        null,
        $item['content']
    );

    $service->create($article, $author, $category, $tags);
    $inserted++;
    echo 'OK: ' . $item['title'] . PHP_EOL;
}

echo 'Inserted: ' . $inserted . PHP_EOL;
echo 'Skipped: ' . $skipped . PHP_EOL;
