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
 * GET les données de l'utilisateur connecté
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'etablissement' => $_SESSION['user_etablissement']
    ];
}

/**
 * Login utlisateur (set les variables de session)
 */
function loginUser(int $id, string $email, string $etablissement): void {
    $_SESSION['user_id'] = $id;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_etablissement'] = $etablissement;
}

/**
 * Logout utlisateur (détruit la session)
 */
function logoutUser(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Redirige vers une URL
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}
?>
