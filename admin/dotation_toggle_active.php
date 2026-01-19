<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;
$isActive = $data['is_active'] ?? false;

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE product SET is_active = ? WHERE id = ?");
    $stmt->execute([$isActive ? 1 : 0, $id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}