<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Supprimer d'abord les images liées
    $pdo->prepare("DELETE FROM product_image WHERE product_id = ?")->execute([$id]);
    
    // Supprimer le produit
    $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: dotations.php');
exit;