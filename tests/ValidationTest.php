<?php
/**
 * Simple Test for Validation and Security Features
 * 
 * This file tests the validation and security implementations.
 * Run with: php tests/ValidationTest.php
 */

require_once __DIR__ . '/../autoload.php';

use Helper\Validator;
use Helper\Csrf;
use Helper\ValidationConfig;

// Color output helpers
function success($msg) {
    echo "✓ \033[32m$msg\033[0m\n";
}

function failure($msg) {
    echo "✗ \033[31m$msg\033[0m\n";
}

function testHeader($msg) {
    echo "\n\033[1m=== $msg ===\033[0m\n";
}

// Start session for CSRF tests
session_start();

// Test Counter
$passed = 0;
$failed = 0;

// ===== VALIDATOR TESTS =====
testHeader("Testing Validator Class");

// Test 1: Valid string validation
$validator = new Validator();
$result = $validator->validateString('test', 'ValidName', 2, 100);
if ($result === true && !$validator->hasErrors()) {
    success("Valid string accepted");
    $passed++;
} else {
    failure("Valid string rejected");
    $failed++;
}

// Test 2: Invalid string (too short)
$validator = new Validator();
$result = $validator->validateString('test', 'A', 2, 100);
if ($result === false && $validator->hasErrors()) {
    success("String too short rejected");
    $passed++;
} else {
    failure("String too short accepted");
    $failed++;
}

// Test 3: Invalid string (too long)
$validator = new Validator();
$longString = str_repeat('A', 101);
$result = $validator->validateString('test', $longString, 2, 100);
if ($result === false && $validator->hasErrors()) {
    success("String too long rejected");
    $passed++;
} else {
    failure("String too long accepted");
    $failed++;
}

// Test 4: Valid SIRET
$validator = new Validator();
$result = $validator->validateSiret('siret', '12345678901234');
if ($result === true && !$validator->hasErrors()) {
    success("Valid SIRET accepted");
    $passed++;
} else {
    failure("Valid SIRET rejected");
    $failed++;
}

// Test 5: Invalid SIRET (not 14 digits)
$validator = new Validator();
$result = $validator->validateSiret('siret', '123456789');
if ($result === false && $validator->hasErrors()) {
    success("Invalid SIRET rejected");
    $passed++;
} else {
    failure("Invalid SIRET accepted");
    $failed++;
}

// Test 6: Invalid SIRET (contains letters)
$validator = new Validator();
$result = $validator->validateSiret('siret', '1234567890ABCD');
if ($result === false && $validator->hasErrors()) {
    success("SIRET with letters rejected");
    $passed++;
} else {
    failure("SIRET with letters accepted");
    $failed++;
}

// Test 7: XSS sanitization
$xssInput = '<script>alert("XSS")</script>';
$sanitized = Validator::sanitize($xssInput);
if ($sanitized === '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;') {
    success("XSS input properly sanitized");
    $passed++;
} else {
    failure("XSS input not properly sanitized: " . $sanitized);
    $failed++;
}

// ===== CSRF TESTS =====
testHeader("Testing CSRF Protection");

// Test 8: Token generation
$token1 = Csrf::generateToken();
if (strlen($token1) === 64 && ctype_xdigit($token1)) {
    success("CSRF token generated (64 hex characters)");
    $passed++;
} else {
    failure("CSRF token generation failed");
    $failed++;
}

// Test 9: Token retrieval
$token2 = Csrf::getToken();
if ($token1 === $token2) {
    success("CSRF token retrieved from session");
    $passed++;
} else {
    failure("CSRF token retrieval failed");
    $failed++;
}

// Test 10: Valid token verification
if (Csrf::verifyToken($token1)) {
    success("Valid CSRF token verified");
    $passed++;
} else {
    failure("Valid CSRF token rejected");
    $failed++;
}

// Test 11: Invalid token rejection
if (!Csrf::verifyToken('invalid_token_12345')) {
    success("Invalid CSRF token rejected");
    $passed++;
} else {
    failure("Invalid CSRF token accepted");
    $failed++;
}

// Test 12: Null token rejection
if (!Csrf::verifyToken(null)) {
    success("Null CSRF token rejected");
    $passed++;
} else {
    failure("Null CSRF token accepted");
    $failed++;
}

// ===== VALIDATION CONFIG TESTS =====
testHeader("Testing Validation Configuration");

// Test 13: Pattern constants exist
if (isset(ValidationConfig::PATTERNS['name']) && isset(ValidationConfig::PATTERNS['siret'])) {
    success("Validation patterns defined");
    $passed++;
} else {
    failure("Validation patterns not defined");
    $failed++;
}

// Test 14: Length constants exist
if (isset(ValidationConfig::LENGTHS['name']) && isset(ValidationConfig::LENGTHS['type'])) {
    success("Validation lengths defined");
    $passed++;
} else {
    failure("Validation lengths not defined");
    $failed++;
}

// Test 15: Name pattern validation
$validator = new Validator();
$validator->validateString('name', 'Jean-Pierre O\'Brien', 
    ValidationConfig::LENGTHS['name']['min'], 
    ValidationConfig::LENGTHS['name']['max'], 
    ValidationConfig::PATTERNS['name']
);
if (!$validator->hasErrors()) {
    success("Name with hyphens and apostrophes accepted");
    $passed++;
} else {
    failure("Valid name rejected: " . json_encode($validator->getErrors()));
    $failed++;
}

// Test 16: Name with special characters rejected
$validator = new Validator();
$validator->validateString('name', 'John@Doe#123', 
    ValidationConfig::LENGTHS['name']['min'], 
    ValidationConfig::LENGTHS['name']['max'], 
    ValidationConfig::PATTERNS['name']
);
if ($validator->hasErrors()) {
    success("Name with special characters rejected");
    $passed++;
} else {
    failure("Name with special characters accepted");
    $failed++;
}

// ===== SUMMARY =====
testHeader("Test Summary");
$total = $passed + $failed;
echo "Total tests: $total\n";
echo "Passed: \033[32m$passed\033[0m\n";
echo "Failed: \033[31m$failed\033[0m\n";

if ($failed === 0) {
    echo "\n\033[1;32m✓ All tests passed!\033[0m\n\n";
    exit(0);
} else {
    echo "\n\033[1;31m✗ Some tests failed!\033[0m\n\n";
    exit(1);
}
