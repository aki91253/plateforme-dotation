<?php
/**
 * Admin Authentication Helper Functions
 * Manages admin sessions separately from regular user sessions
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Check if current admin is a superadmin
 */
function isSuperAdmin(): bool {
    return isAdminLoggedIn() && isset($_SESSION['admin_role_id']) && $_SESSION['admin_role_id'] == 2;
}

/**
 * Get current logged-in admin data
 */
function getCurrentAdmin(): ?array {
    if (!isAdminLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['admin_id'],
        'email' => $_SESSION['admin_email'],
        'first_name' => $_SESSION['admin_first_name'],
        'last_name' => $_SESSION['admin_last_name'],
        'job_title' => $_SESSION['admin_job_title'],
        'role_id' => $_SESSION['admin_role_id'] ?? 1,
        'role_libelle' => $_SESSION['admin_role_libelle'] ?? 'Admin'
    ];
}

/**
 * Login admin (set session variables)
 */
function loginAdmin(int $id, string $email, string $firstName, string $lastName, string $jobTitle, int $roleId = 1, string $roleLibelle = 'Admin'): void {
    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_first_name'] = $firstName;
    $_SESSION['admin_last_name'] = $lastName;
    $_SESSION['admin_job_title'] = $jobTitle;
    $_SESSION['admin_role_id'] = $roleId;
    $_SESSION['admin_role_libelle'] = $roleLibelle;
}

/**
 * Logout admin (clear admin session data)
 */
function logoutAdmin(): void {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_first_name']);
    unset($_SESSION['admin_last_name']);
    unset($_SESSION['admin_job_title']);
    unset($_SESSION['admin_role_id']);
    unset($_SESSION['admin_role_libelle']);
}

/**
 * Require admin login - redirect to login if not authenticated
 */
function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
}

/**
 * Require superadmin role - redirect if not superadmin
 */
function requireSuperAdmin(): void {
    requireAdmin();
    if (!isSuperAdmin()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Redirect helper
 */
function adminRedirect(string $url): void {
    header("Location: $url");
    exit;
}
?>
