<?php

declare(strict_types=1);

namespace App\Controllers\Front;

use App\Core\Database;
use App\Services\ArticleService;
use Throwable;

final class HomeController
{
    public function index(): string
    {
        $service = new ArticleService();
        $articles = $service->latest();
        $dbStatus = $this->checkDatabases();

        return view('front/home', [
            'title' => 'Accueil',
            'articles' => $articles,
            'dbStatus' => $dbStatus,
        ]);
    }

    public function about(): string
    {
        return view('front/about', [
            'title' => 'A propos',
        ]);
    }

    /** @return array<string, array{ok: bool, message: string}> */
    private function checkDatabases(): array
    {
        $status = [
            'postgres' => ['ok' => false, 'message' => 'Non teste'],
            'mongodb' => ['ok' => false, 'message' => 'Non teste'],
        ];

        try {
            $pdo = Database::postgres();
            $value = $pdo->query('SELECT 1')->fetchColumn();
            $status['postgres'] = [
                'ok' => ((string) $value === '1'),
                'message' => ((string) $value === '1') ? 'Connexion OK' : 'Reponse inattendue',
            ];
        } catch (Throwable $e) {
            $status['postgres'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        try {
            $manager = Database::mongodb();
            $commandClass = 'MongoDB\\Driver\\Command';

            if (!class_exists($commandClass)) {
                throw new \RuntimeException('MongoDB extension command class missing.');
            }

            $command = new $commandClass(['ping' => 1]);
            $cursor = $manager->executeCommand('admin', $command);
            $result = current($cursor->toArray());

            $ok = is_object($result) && isset($result->ok) && (float) $result->ok === 1.0;
            $status['mongodb'] = [
                'ok' => $ok,
                'message' => $ok ? 'Connexion OK' : 'Ping MongoDB invalide',
            ];
        } catch (Throwable $e) {
            $status['mongodb'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        return $status;
    }
}
