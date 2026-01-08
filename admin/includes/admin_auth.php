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
        'job_title' => $_SESSION['admin_job_title']
    ];
}

/**
 * Login admin (set session variables)
 */
function loginAdmin(int $id, string $email, string $firstName, string $lastName, string $jobTitle): void {
    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_first_name'] = $firstName;
    $_SESSION['admin_last_name'] = $lastName;
    $_SESSION['admin_job_title'] = $jobTitle;
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
 * Redirect helper
 */
function adminRedirect(string $url): void {
    header("Location: $url");
    exit;
}
?>
