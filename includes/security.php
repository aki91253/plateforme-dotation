<?php
/**
 * Security helper functions to prevent SQL injection and XSS attacks
 * Include this file in any PHP file that handles user input
 */

// SQL injection dangerous characters pattern
// Blocks: ' " ; -- # /* */ \ % _ * =
const SQL_INJECTION_PATTERN = '/[\'";#%_\\\\*=]|--|\\/\\*/';

// List of safe special characters for passwords
const ALLOWED_PASSWORD_SPECIAL_CHARS = '!@^&()[]{}|:,.<>?/~';

/**
 * Check if input contains SQL injection characters
 * @param string $input The input to check
 * @return bool True if dangerous characters are found
 */
function containsSqlInjectionChars(string $input): bool {
    return preg_match(SQL_INJECTION_PATTERN, $input) === 1;
}

/**
 * Get error message for SQL injection detection
 * @param string $fieldName The name of the field for the error message
 * @return string The error message
 */
function getSqlInjectionErrorMessage(string $fieldName): string {
    return "Le champ \"$fieldName\" contient des caractères non autorisés (' \" ; -- # /* */ \\ % _ * =).";
}

/**
 * Sanitize input by removing SQL injection characters
 * Use this for optional cleanup, but validation should still be done
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitizeInput(string $input): string {
    // Remove dangerous patterns
    $sanitized = preg_replace(SQL_INJECTION_PATTERN, '', $input);
    return trim($sanitized);
}

/**
 * Validate email format (basic validation + SQL injection check)
 * @param string $email The email to validate
 * @return string|true Returns true if valid, or error message string
 */
function validateEmail(string $email): string|true {
    if (empty($email)) {
        return 'L\'adresse email est requise.';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Adresse email invalide.';
    }
    
    if (containsSqlInjectionChars($email)) {
        return getSqlInjectionErrorMessage('Email');
    }
    
    return true;
}

/**
 * Validate a text field (non-empty + SQL injection check)
 * @param string $value The value to validate
 * @param string $fieldName The name of the field for error messages
 * @param int $minLength Minimum required length (default 1)
 * @param int $maxLength Maximum allowed length (default 255)
 * @return string|true Returns true if valid, or error message string
 */
function validateTextField(string $value, string $fieldName, int $minLength = 1, int $maxLength = 255): string|true {
    $value = trim($value);
    
    if (strlen($value) < $minLength) {
        return "Le champ \"$fieldName\" doit contenir au moins $minLength caractère(s).";
    }
    
    if (strlen($value) > $maxLength) {
        return "Le champ \"$fieldName\" ne doit pas dépasser $maxLength caractères.";
    }
    
    if (containsSqlInjectionChars($value)) {
        return getSqlInjectionErrorMessage($fieldName);
    }
    
    return true;
}

/**
 * Validate password strength
 * @param string $password The password to validate
 * @return string|true Returns true if valid, or error message string
 */
function validatePassword(string $password): string|true {
    // Minimum 12 characters
    if (strlen($password) < 12) {
        return 'Le mot de passe doit contenir au moins 12 caractères.';
    }
    
    // Check for uppercase
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Le mot de passe doit contenir au moins une majuscule.';
    }
    
    // Check for lowercase
    if (!preg_match('/[a-z]/', $password)) {
        return 'Le mot de passe doit contenir au moins une minuscule.';
    }
    
    // Check for digit
    if (!preg_match('/[0-9]/', $password)) {
        return 'Le mot de passe doit contenir au moins un chiffre.';
    }
    
    // Check for special character from allowed list
    $escapedChars = preg_quote(ALLOWED_PASSWORD_SPECIAL_CHARS, '/');
    if (!preg_match('/[' . $escapedChars . ']/', $password)) {
        return 'Le mot de passe doit contenir au moins un caractère spécial (!@^&()[]{}|:,.<>?/~).';
    }
    
    // Block dangerous SQL injection characters
    if (containsSqlInjectionChars($password)) {
        return getSqlInjectionErrorMessage('Mot de passe');
    }
    
    return true;
}

/**
 * Validate that passwords match
 * @param string $password The password
 * @param string $confirmPassword The confirmation password
 * @return string|true Returns true if match, or error message string
 */
function validatePasswordMatch(string $password, string $confirmPassword): string|true {
    if ($password !== $confirmPassword) {
        return 'Les mots de passe ne correspondent pas.';
    }
    return true;
}
