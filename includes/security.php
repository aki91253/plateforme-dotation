<?php
/**
 * Fonctions de sécurité pour prévenir les injections SQL et les attaques XSS
 * Important : Inclure dans ce fichier toutes les fonctions de sécurité
 */

// Patterns souvent utilisés pour les injections SQL
// Blocks: ' " ; -- # /* */ \ % _ * =
const SQL_INJECTION_PATTERN = '/[\'";#%_\\\\*=]|--|\\/\\*/';

// Liste des caractères spéciaux autorisés pour les mots de passe
const ALLOWED_PASSWORD_SPECIAL_CHARS = '!@^&()[]{}|:,.<>?/~';

/**
 * Vérifie si l'entrée contient des caractères d'injection SQL
 * @param string $input L'entrée à vérifier
 * @return bool True si des caractères dangereux sont trouvés
 */
function containsSqlInjectionChars(string $input): bool {
    return preg_match(SQL_INJECTION_PATTERN, $input) === 1;
}

/**
 * Récupère un message d'erreur pour la détection d'injection SQL
 * @param string $fieldName Le nom du champ pour le message d'erreur
 * @return string Le message d'erreur
 */
function getSqlInjectionErrorMessage(string $fieldName): string {
    return "Le champ \"$fieldName\" contient des caractères non autorisés (' \" ; -- # /* */ \\ % _ * =).";
}

/**
 * Nettoie l'entrée en supprimant les caractères d'injection SQL
 * @param string $input L'entrée à nettoyer
 * @return string L'entrée nettoyée
 */
function sanitizeInput(string $input): string {
    // Remove dangerous patterns
    $sanitized = preg_replace(SQL_INJECTION_PATTERN, '', $input);
    return trim($sanitized);
}

/**
 * Vérifie le format de l'email (validation de base + vérification d'injection SQL)
 * @param string $email L'email à valider
 * @return string|true Retourne true si valide, ou message d'erreur
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
 * Vérifie un champ de texte (non-vide + vérification d'injection SQL)
 * @param string $value La valeur à valider
 * @param string $fieldName Le nom du champ pour les messages d'erreur
 * @param int $minLength Longueur minimale requise (par défaut 1)
 * @param int $maxLength Longueur maximale autorisée (par défaut 255)
 * @return string|true Retourne true si valide, ou message d'erreur
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
 * Vérifie la force du mot de passe
 * @param string $password Le mot de passe à valider
 * @return string|true Retourne true si valide, ou message d'erreur
 */
function validatePassword(string $password): string|true {
    // Minimum 12 characters
    if (strlen($password) < 12) {
        return 'Le mot de passe doit contenir au moins 12 caractères.';
    }
    
    // Vérifie la présence d'une majuscule
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Le mot de passe doit contenir au moins une majuscule.';
    }
    
    // Vérifie la présence d'une minuscule
    if (!preg_match('/[a-z]/', $password)) {
        return 'Le mot de passe doit contenir au moins une minuscule.';
    }
    
    // Vérifie la présence d'un chiffre
    if (!preg_match('/[0-9]/', $password)) {
        return 'Le mot de passe doit contenir au moins un chiffre.';
    }
    
    // Vérifie la présence d'un caractère spécial autorisé
    $escapedChars = preg_quote(ALLOWED_PASSWORD_SPECIAL_CHARS, '/');
    if (!preg_match('/[' . $escapedChars . ']/', $password)) {
        return 'Le mot de passe doit contenir au moins un caractère spécial (!@^&()[]{}|:,.<>?/~).';
    }
    
    // Vérifie la présence de caractères d'injection SQL
    if (containsSqlInjectionChars($password)) {
        return getSqlInjectionErrorMessage('Mot de passe');
    }
    
    return true;
}

/**
 * Vérifie que les mots de passe correspondent
 * @param string $password Le mot de passe
 * @param string $confirmPassword Le mot de passe de confirmation
 * @return string|true Retourne true si les mots de passe correspondent, ou message d'erreur
 */
function validatePasswordMatch(string $password, string $confirmPassword): string|true {
    if ($password !== $confirmPassword) {
        return 'Les mots de passe ne correspondent pas.';
    }
    return true;
}
