<?php
/**
 * Handle form submission from selection.php
 * Saves request to database
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

try {
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $establishmentName = trim($_POST['establishment_name'] ?? '');
    $className = trim($_POST['class_name'] ?? '');
    $establishmentPostal = trim($_POST['establishment_postal'] ?? '');
    $establishmentCity = trim($_POST['establishment_city'] ?? '');
    $requestType = $_POST['request_type'] ?? 'RECEVOIR';
    $comment = trim($_POST['comment'] ?? '');
    $cartData = json_decode($_POST['cart_data'] ?? '[]', true);
    
    // Validate required fields
    if (empty($email) || empty($establishmentName) || empty($className) || empty($cartData)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
        exit;
    }
    
    // Generate unique request number
    $year = date('Y');
    $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(request_number, 10) AS UNSIGNED)) as max_num FROM request WHERE request_number LIKE 'DEM-$year-%'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextNum = ($result['max_num'] ?? 0) + 1;
    $requestNumber = sprintf("DEM-%s-%04d", $year, $nextNum);
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Insert main request (use first product_id for legacy compatibility)
    $firstProductId = $cartData[0]['id'] ?? 1;
    $stmt = $pdo->prepare('INSERT INTO request (request_number, product_id, last_name, first_name, email, phone, establishment_name, establishment_address, establishment_postal, establishment_city, request_date, request_type, status, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, "EN_COURS", ?)');
    $stmt->execute([
        $requestNumber,
        $firstProductId,
        $className, // Using class name as last_name
        '', // first_name empty
        $email,
        $phone,
        $establishmentName,
        '', // address
        $establishmentPostal,
        $establishmentCity,
        $requestType,
        $comment
    ]);
    
    $requestId = $pdo->lastInsertId();
    
    // Insert request lines for each product
    $stmtLine = $pdo->prepare('INSERT INTO request_line (request_id, product_id, quantity, comment) VALUES (?, ?, 1, ?)');
    foreach ($cartData as $item) {
        $stmtLine->execute([$requestId, $item['id'], $item['name']]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Demande enregistrée avec succès',
        'request_number' => $requestNumber
    ]);
    
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()]);
}
?>
