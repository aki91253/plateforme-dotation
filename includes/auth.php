<?php
/**
 * Fonctions d'authentification
 * Important : Inclure toutes les fonctions de connexion utilisateur ici
 */

// Lance une session si celà n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Regarde si l'utilisateur est connecté 
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirige vers une URL
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}
?>
