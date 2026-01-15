<?php

namespace Helper;

/**
 * Validation Configuration
 * 
 * Centralized validation rules to maintain consistency
 * between client-side and server-side validation.
 */
class ValidationConfig
{
    /**
     * Validation patterns
     */
    public const PATTERNS = [
        'name' => '/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
        'siret' => '/^\d{14}$/',
    ];
    
    /**
     * Field length constraints
     */
    public const LENGTHS = [
        'name' => ['min' => 2, 'max' => 100],
        'type' => ['min' => 2, 'max' => 50],
        'adresse' => ['min' => 5, 'max' => 200],
        'information' => ['min' => 0, 'max' => 500],
    ];
}
