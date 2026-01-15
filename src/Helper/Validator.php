<?php

namespace Helper;

class Validator
{
    private array $errors = [];
    
    /**
     * Validate a string field
     */
    public function validateString(string $fieldName, ?string $value, int $minLength, int $maxLength, ?string $pattern = null): bool
    {
        $value = $value !== null ? trim($value) : '';
        
        if (empty($value)) {
            $this->errors[$fieldName] = "Le champ {$fieldName} est requis";
            return false;
        }
        
        if (strlen($value) < $minLength || strlen($value) > $maxLength) {
            $this->errors[$fieldName] = "Le champ {$fieldName} doit contenir entre {$minLength} et {$maxLength} caractères";
            return false;
        }
        
        if ($pattern !== null && !preg_match($pattern, $value)) {
            $this->errors[$fieldName] = "Le format du champ {$fieldName} est invalide";
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate SIRET number (14 digits)
     */
    public function validateSiret(string $fieldName, ?string $value): bool
    {
        $value = $value !== null ? trim($value) : '';
        
        if (empty($value)) {
            $this->errors[$fieldName] = "Le numéro SIRET est requis";
            return false;
        }
        
        if (!preg_match('/^\d{14}$/', $value)) {
            $this->errors[$fieldName] = "Le numéro SIRET doit contenir exactement 14 chiffres";
            return false;
        }
        
        return true;
    }
    
    /**
     * Get all validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Check if there are any errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    public static function sanitize(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
