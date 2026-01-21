<?php
require_once '../includes/db.php';
require_once '../includes/queries.php';
require_once 'includes/admin_auth.php';

requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        deleteProduct($id);
        header('Location: stock.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        header('Location: stock.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: dotations.php');
    exit;
}