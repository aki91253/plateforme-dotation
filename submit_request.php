<?php
/**
 * gérer la soumission du formulaire de selection.php
 * Sauvegarde de la demande dans la base de données
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

try {
    // Get données du formulaire
    $lastName = trim($_POST['nom'] ?? '');
    $firstName = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $establishmentName = trim($_POST['establishment_name'] ?? '');
    $establishmentPostal = trim($_POST['establishment_postal'] ?? '');
    $establishmentCity = trim($_POST['establishment_city'] ?? '');
    $requestType = $_POST['request_type'] ?? 'RECEVOIR';
    $comment = trim($_POST['comment'] ?? '');
    $cartData = json_decode($_POST['cart_data'] ?? '[]', true);
    
    // Validation des champs obligatoires
    if (empty($lastName) || empty($firstName) || empty($email) || empty($phone) || empty($establishmentName) || empty($cartData)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
        exit;
    }
    
    // Génération d'un token unique pour la demande
    // Boucle pour s'assurer que le token n'existe pas déjà
    do {
        $token = bin2hex(random_bytes(16)); // Génère un token hexadécimal de 32 caractères
        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM request WHERE token = ?');
        $checkStmt->execute([$token]);
        $tokenExists = $checkStmt->fetchColumn() > 0;
    } while ($tokenExists);
    
    // Début de la transaction
    $pdo->beginTransaction();
    
    // Insertion de la demande principale (utilisation du premier product_id pour la compatibilité avec l'historique)
    $firstProductId = $cartData[0]['id'] ?? 1;
    $stmt = $pdo->prepare('INSERT INTO request (token, product_id, last_name, first_name, email, phone, establishment_name, establishment_address, establishment_postal, establishment_city, request_date, request_type, status, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, "EN_COURS", ?)');
    $stmt->execute([
        $token,
        $firstProductId,
        $lastName,
        $firstName,
        $email,
        $phone,
        $establishmentName,
        '', // addresse vide
        $establishmentPostal,
        $establishmentCity,
        $requestType,
        $comment
    ]);
    
    $requestId = $pdo->lastInsertId();
    
    // Insertion des lignes de demande pour chaque produit
    $stmtLine = $pdo->prepare('INSERT INTO request_line (request_id, product_id, quantity, comment) VALUES (?, ?, ?, ?)');
    foreach ($cartData as $item) {
        $quantity = $item['quantity'] ?? 1;
        $stmtLine->execute([$requestId, $item['id'], $quantity, $item['name']]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Demande enregistrée avec succès',
        'token' => $token
    ]);
    
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()]);
}
?>
