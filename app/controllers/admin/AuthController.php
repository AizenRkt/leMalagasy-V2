<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use PDO;

final class AuthController
{
    public function loginForm(): string
    {
        ensure_session_started();

        if (admin_is_authenticated()) {
            header('Location: /admin/dashboard');
            exit;
        }

        $error = is_string($_SESSION['admin_auth_error'] ?? null) ? $_SESSION['admin_auth_error'] : '';
        $email = is_string($_SESSION['admin_auth_email'] ?? null) ? $_SESSION['admin_auth_email'] : '';
        unset($_SESSION['admin_auth_error'], $_SESSION['admin_auth_email']);

        return view('admin/auth/login', [
            'title' => 'Connexion admin',
            'error' => $error,
            'email' => $email,
        ]);
    }

    public function login(): string
    {
        ensure_session_started();

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['admin_auth_error'] = 'Veuillez saisir votre email et votre mot de passe.';
            $_SESSION['admin_auth_email'] = $email;
            header('Location: /admin/login');
            exit;
        }

        $db = Database::postgres();
        $stmt = $db->prepare(
            'SELECT u.id, u.name, u.email, r.name AS role_name
             FROM utilisateur u
             LEFT JOIN role r ON r.id = u.id_role
             WHERE LOWER(u.email) = LOWER(?)
             LIMIT 1'
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!is_array($user)) {
            $_SESSION['admin_auth_error'] = 'Compte introuvable.';
            $_SESSION['admin_auth_email'] = $email;
            header('Location: /admin/login');
            exit;
        }

        $allowedRoles = config('auth.allowed_roles', ['admin']);
        $role = (string) ($user['role_name'] ?? '');
        if (!is_array($allowedRoles) || !in_array($role, $allowedRoles, true)) {
            $_SESSION['admin_auth_error'] = 'Ce compte ne peut pas acceder a l administration.';
            $_SESSION['admin_auth_email'] = $email;
            header('Location: /admin/login');
            exit;
        }

        $expectedPassword = (string) config('auth.admin_password', 'admin123');
        if (!hash_equals($expectedPassword, $password)) {
            $_SESSION['admin_auth_error'] = 'Mot de passe invalide.';
            $_SESSION['admin_auth_email'] = $email;
            header('Location: /admin/login');
            exit;
        }

        admin_login([
            'id' => (int) ($user['id'] ?? 0),
            'name' => (string) ($user['name'] ?? ''),
            'email' => (string) ($user['email'] ?? ''),
            'role' => $role,
        ]);

        header('Location: /admin/dashboard');
        exit;
    }

    public function logout(): string
    {
        admin_logout();
        header('Location: /admin/login');
        exit;
    }
}
