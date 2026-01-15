<?php

namespace Helper;

class Csrf
{
    private const TOKEN_NAME = 'csrf_token';
    
    /**
     * Generate and store a CSRF token in the session
     */
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_NAME] = $token;
        
        return $token;
    }
    
    /**
     * Get the current CSRF token from the session
     */
    public static function getToken(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION[self::TOKEN_NAME] ?? null;
    }
    
    /**
     * Verify that the provided token matches the session token
     */
    public static function verifyToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $sessionToken = $_SESSION[self::TOKEN_NAME] ?? null;
        
        if ($sessionToken === null || $token === null) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Delete the CSRF token from the session
     */
    public static function deleteToken(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION[self::TOKEN_NAME]);
    }
}
