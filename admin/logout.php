<?php
/**
 * Admin Logout
 * Clear admin session and redirect to login
 */
require_once 'includes/admin_auth.php';

logoutAdmin();
header('Location: ../login.php');
exit;
?>
