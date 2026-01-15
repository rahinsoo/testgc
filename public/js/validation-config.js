/**
 * Validation Configuration
 * 
 * This file contains validation rules and patterns used across the application.
 * Keep client-side and server-side validation in sync by referencing these rules.
 */

// Validation Patterns
const VALIDATION_PATTERNS = {
    // Name: Letters (with accents), spaces, hyphens, apostrophes
    name: /^[a-zA-ZÀ-ÿ\s\-']+$/,
    
    // SIRET: Exactly 14 digits
    siret: /^\d{14}$/
};

// Validation Lengths
const VALIDATION_LENGTHS = {
    name: { min: 2, max: 100 },
    type: { min: 2, max: 50 },
    adresse: { min: 5, max: 200 },
    information: { min: 0, max: 500 }
};

// Export for use in validation
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { VALIDATION_PATTERNS, VALIDATION_LENGTHS };
}
