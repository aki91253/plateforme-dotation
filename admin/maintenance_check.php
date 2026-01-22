<?php
// maintenance_check.php - À inclure en haut de chaque page publique
$maintenanceFile = __DIR__ . '/maintenance.lock';

// Vérifier si le mode maintenance est activé
if (file_exists($maintenanceFile)) {
    // Autoriser l'accès au back-office
    $currentPath = $_SERVER['REQUEST_URI'];
    if (strpos($currentPath, '/admin/') === false) {
        // Rediriger vers la page de maintenance
        header('Location: /maintenance_page.php');
        exit;
    }
}