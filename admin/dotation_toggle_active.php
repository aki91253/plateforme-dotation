<?php
require_once '../includes/db.php';
require_once '../includes/queries.php';
require_once 'includes/admin_auth.php';

requireAdmin();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;
$isActive = $data['is_active'] ?? false;

if ($id > 0) {
    toggleProductActive($id, $isActive);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}