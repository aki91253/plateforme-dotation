<?php
/**
 * gérer la soumission du formulaire de selection.php
 * Sauvegarde de la demande dans la base de données
 */
require_once 'includes/db.php';
require_once 'includes/queries.php';
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
    $establishmentAddress = trim($_POST['establishment_address'] ?? '');
    $establishmentPostal = trim($_POST['establishment_postal'] ?? '');
    $establishmentCity = trim($_POST['establishment_city'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $cartData = json_decode($_POST['cart_data'] ?? '[]', true);
    
    // Validation des champs obligatoires
    if (empty($lastName) || empty($firstName) || empty($email) || empty($phone) || empty($establishmentName) || empty($cartData)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
        exit;
    }
    
    // Génération d'un token unique pour la demande
    do {
        $token = bin2hex(random_bytes(16));
    } while (tokenExists($token));
    
    // Début de la transaction
    $pdo->beginTransaction();
    
    // Insertion de la demande principale
    $firstProductId = $cartData[0]['id'] ?? 1;
    $requestId = createRequest([
        'token' => $token,
        'product_id' => $firstProductId,
        'last_name' => $lastName,
        'first_name' => $firstName,
        'email' => $email,
        'phone' => $phone,
        'establishment_name' => $establishmentName,
        'establishment_address' => $establishmentAddress,
        'establishment_postal' => $establishmentPostal,
        'establishment_city' => $establishmentCity,
        'comment' => $comment
    ]);
    
    // Insertion des lignes de demande pour chaque produit
    foreach ($cartData as $item) {
        $quantity = $item['quantity'] ?? 1;
        createRequestLine($requestId, $item['id'], $quantity, $item['name']);
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
