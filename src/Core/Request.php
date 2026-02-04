<?php

namespace Core;

final class Request {

    public function path() : string {
        $path = $_SERVER['REQUEST_URI'];
        return is_string($path) && $path!== '' ? $path : '/';
    }

    public function method () : string {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function isPost () : bool {
        return $this->method() === 'POST';
    }

    public function post (string $key) : mixed {
        return $_POST[$key] ?? '';
    }

    // ========================================
    // NOUVELLES MÉTHODES
    // ========================================

    /**
     * Récupère un paramètre POST
     * @param string $key Nom du paramètre
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas
     * @return mixed
     */
    public function getPostParam(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $default;
    }

    /**
     * Récupère un paramètre GET
     * @param string $key Nom du paramètre
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas
     * @return mixed
     */
    public function getGetParam(string $key, mixed $default = null): mixed {
        return $_GET[$key] ?? $default;
    }

    /**
     * Récupère tous les paramètres POST
     * @return array
     */
    public function getAllPost(): array {
        return $_POST;
    }

    /**
     * Récupère tous les paramètres GET
     * @return array
     */
    public function getAllGet(): array {
        return $_GET;
    }

    /**
     * Vérifie si un paramètre POST existe
     * @param string $key
     * @return bool
     */
    public function hasPostParam(string $key): bool {
        return isset($_POST[$key]);
    }

    /**
     * Vérifie si un paramètre GET existe
     * @param string $key
     * @return bool
     */
    public function hasGetParam(string $key): bool {
        return isset($_GET[$key]);
    }

    /**
     * Récupère le corps de la requête (pour JSON par exemple)
     * @return string
     */
    public function getBody(): string {
        return file_get_contents('php://input') ?: '';
    }

    /**
     * Récupère le corps de la requête et le décode en JSON
     * @return array|null
     */
    public function getJsonBody(): ?array {
        $body = $this->getBody();
        if (empty($body)) {
            return null;
        }

        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Vérifie si la requête est une requête AJAX
     * @return bool
     */
    public function isAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Récupère l'IP du client
     * @return string
     */
    public function getClientIp(): string {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Vérifie si la méthode HTTP correspond
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool {
        return strtoupper($this->method()) === strtoupper($method);
    }

    /**
     * Récupère un fichier uploadé
     * @param string $key
     * @return array|null
     */
    public function getFile(string $key): ?array {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            return $_FILES[$key];
        }
        return null;
    }

    /**
     * Vérifie si un fichier a été uploadé
     * @param string $key
     * @return bool
     */
    public function hasFile(string $key): bool {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }
}