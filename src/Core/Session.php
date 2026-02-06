<?php

namespace Core;

use http\Exception\RuntimeException;

final class Session {
    private int $timeout = 1800;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE ) {
            throw new RuntimeException('Session is not started. Call session_start() to activate it.');
        }

        $this->handleTimeout();
    }

    public function get(string $key) : mixed {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value) : void {
        $_SESSION[$key] = $value;
    }

    public function flash (string $key, mixed $value) : void {
        $_SESSION['flash_' . $key] = $value;
    }

    public function pullFlash (string $key) : mixed {
        $value = $_SESSION['flash_' . $key] ?? null;
        unset($_SESSION['flash_' . $key]);
        return $value;
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['user']['id_user_role'])
            && $_SESSION['user']['id_user_role'] === 1;
    }

    public function isManager(): bool
    {
        return isset($_SESSION['user']['id_user_role'])
            && in_array($_SESSION['user']['id_user_role'], [3, 4], true);
    }

    public function isRecruteur(): bool
    {
        return isset($_SESSION['user']['id_user_role'])
            && $_SESSION['user']['id_user_role'] === 3;
    }

    public function isCollaborateur(): bool
    {
        return isset($_SESSION['user']['id_user_role'])
            && $_SESSION['user']['id_user_role'] === 2;
    }

    private function handleTimeout(): void
    {
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $this->timeout) {
                $this->destroy();
                header('Location: /login');
                exit;
            }
        }

        // Mise à jour à chaque requête valide
        $_SESSION['last_activity'] = time();
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}