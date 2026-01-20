<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        // Supprimer d'abord les images liées
        $pdo->prepare("DELETE FROM product_image WHERE product_id = ?")->execute([$id]);
        
        // Supprimer le produit
        $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        $stmt->execute([$id]);
        
        // Redirection avec message de succès
        header('Location: stock.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        // En cas d'erreur (clé étrangère, etc.)
        header('Location: stock.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: dotations.php');
    exit;
}