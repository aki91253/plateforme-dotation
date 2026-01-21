<?php
/**
 * Centralized SQL Queries
 * All database queries are consolidated here for better maintainability
 */

// Ensure database connection is available
if (!isset($pdo)) {
    require_once __DIR__ . '/db.php';
}

// =============================================================================
// LOOKUP QUERIES - Reference data from tables
// =============================================================================

/**
 * Get all categories
 */
function getAllCategories(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM category ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all statuses
 */
function getAllStatuses(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM type_status ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all roles
 */
function getAllRoles(): array {
    global $pdo;
    return $pdo->query("SELECT id, libelle FROM roles ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all responsibles (admins)
 */
function getAllResponsibles(): array {
    global $pdo;
    return $pdo->query("SELECT id, last_name, first_name FROM responsible ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all resource types
 */
function getAllRessources(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM type_ressource ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all languages
 */
function getAllLangues(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM langue_product ORDER BY langue")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all disciplines
 */
function getAllDisciplines(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM discipline ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get distinct product locations
 */
function getDistinctLocations(): array {
    global $pdo;
    return $pdo->query("SELECT DISTINCT location FROM product WHERE location IS NOT NULL AND location != '' ORDER BY location")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get distinct product collections
 */
function getDistinctCollections(): array {
    global $pdo;
    return $pdo->query("SELECT DISTINCT collection FROM product WHERE collection IS NOT NULL AND collection != '' ORDER BY collection")->fetchAll(PDO::FETCH_ASSOC);
}

// =============================================================================
// STATISTICS QUERIES
// =============================================================================

/**
 * Get dashboard statistics
 */
function getDashboardStats(): array {
    global $pdo;
    return [
        'totalProducts' => $pdo->query('SELECT COUNT(*) FROM product WHERE is_active = 1')->fetchColumn(),
        'totalStock' => $pdo->query('SELECT SUM(stock) FROM product WHERE is_active = 1')->fetchColumn() ?? 0,
        'pendingRequests' => $pdo->query("SELECT COUNT(*) FROM request WHERE status_id = 1")->fetchColumn(),
        'completedRequests' => $pdo->query("SELECT COUNT(*) FROM request WHERE status_id >= 4")->fetchColumn()
    ];
}

/**
 * Get stock statistics
 */
function getStockStats(): array {
    global $pdo;
    $statsQuery = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as actives,
            SUM(CASE WHEN stock < 20 THEN 1 ELSE 0 END) as stock_faible,
            SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as en_rupture
        FROM product
    ");
    return $statsQuery->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get request statistics by status
 */
function getRequestStats(): array {
    global $pdo;
    $statsQuery = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status_id = 1 THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as verifiees,
            SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as approuvees,
            SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as envoyees,
            SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as livrees,
            SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as refusees
        FROM request
    ");
    return $statsQuery->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get count of requests older than specified days
 */
function getOldRequestsCount(int $days = 30): int {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM request 
        WHERE request_date < DATE_SUB(CURDATE(), INTERVAL ? DAY)
    ");
    $stmt->execute([$days]);
    return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

/**
 * Get low stock items
 */
function getLowStockItems(int $threshold = 20, int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT name, stock as quantity 
        FROM product 
        WHERE is_active = 1 AND stock < ?
        ORDER BY stock ASC 
        LIMIT ?
    ');
    $stmt->execute([$threshold, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent requests
 */
function getRecentRequests(int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT r.token as request_number, r.establishment_name, r.status_id, t.libelle as status_label, r.request_date, 
               CONCAT(resp.first_name, " ", resp.last_name) as responsible_name
        FROM request r 
        LEFT JOIN responsible resp ON resp.id = r.responsible_id
        LEFT JOIN type_status t ON t.id = r.status_id
        ORDER BY r.request_date DESC 
        LIMIT ?
    ');
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =============================================================================
// ADMIN MANAGEMENT QUERIES
// =============================================================================

/**
 * Get all admins with their roles
 */
function getAllAdmins(): array {
    global $pdo;
    return $pdo->query("
        SELECT r.id, r.first_name, r.last_name, r.email_pro, r.job_title, 
               COALESCE(ro.libelle, 'Admin') as role_libelle,
               COALESCE(r.role_id, 1) as role_id
        FROM responsible r 
        LEFT JOIN roles ro ON r.role_id = ro.id 
        ORDER BY r.id
    ")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get admin by email (for login)
 */
function getAdminByEmail(string $email): ?array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.id, r.email_pro, r.password, r.first_name, r.last_name, r.job_title, 
               COALESCE(r.role_id, 1) as role_id, COALESCE(ro.libelle, 'Admin') as role_libelle
        FROM responsible r 
        LEFT JOIN roles ro ON r.role_id = ro.id 
        WHERE r.email_pro = ?
    ");
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

/**
 * Check if admin email exists
 */
function adminEmailExists(string $email): bool {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM responsible WHERE email_pro = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

/**
 * Create new admin
 */
function createAdmin(string $firstName, string $lastName, string $email, string $hashedPassword, string $jobTitle, int $roleId): int {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO responsible (first_name, last_name, email_pro, password, job_title, role_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $jobTitle, $roleId]);
    return (int)$pdo->lastInsertId();
}

/**
 * Delete admin by ID
 */
function deleteAdmin(int $id): bool {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM responsible WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

// =============================================================================
// REQUEST MANAGEMENT QUERIES
// =============================================================================

/**
 * Update request status
 */
function updateRequestStatus(int $requestId, int $newStatus): bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE request SET status_id = ? WHERE id = ?");
    $stmt->execute([$newStatus, $requestId]);
    return $stmt->rowCount() > 0;
}

/**
 * Delete requests older than specified days
 */
function deleteOldRequests(int $days = 30): int {
    global $pdo;
    $stmt = $pdo->prepare("
        DELETE FROM request 
        WHERE request_date < DATE_SUB(CURDATE(), INTERVAL ? DAY)
    ");
    $stmt->execute([$days]);
    return $stmt->rowCount();
}

/**
 * Get filtered requests with pagination support
 */
function getFilteredRequests(string $search = '', int $statusFilter = 0): array {
    global $pdo;
    
    $query = "SELECT r.*, t.libelle as status_label, 
              CONCAT(resp.first_name, ' ', resp.last_name) as responsible_name,
              (SELECT COUNT(*) FROM request_line WHERE request_id = r.id) as items_count,
              (SELECT MAX(changed_at) FROM historique_etat WHERE request_id = r.id) as last_status_change
              FROM request r 
              LEFT JOIN type_status t ON r.status_id = t.id
              LEFT JOIN responsible resp ON r.responsible_id = resp.id
              WHERE 1=1";
    
    $params = [];
    
    if (!empty($search)) {
        $query .= " AND (r.token LIKE :search OR r.establishment_name LIKE :search OR r.email LIKE :search OR r.last_name LIKE :search OR r.first_name LIKE :search)";
        $params['search'] = '%' . $search . '%';
    }
    
    if ($statusFilter > 0) {
        $query .= " AND r.status_id = :status";
        $params['status'] = $statusFilter;
    }
    
    $query .= " ORDER BY r.request_date DESC, r.id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get request details by ID
 */
function getRequestById(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT r.*, t.libelle as status_label,
               CONCAT(resp.first_name, " ", resp.last_name) as responsible_name
        FROM request r
        LEFT JOIN type_status t ON t.id = r.status_id
        LEFT JOIN responsible resp ON resp.id = r.responsible_id
        WHERE r.id = ?
    ');
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

/**
 * Get request lines (items) for a request
 */
function getRequestLines(int $requestId): array {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT rl.*, p.name as product_name, p.reference,
               (SELECT url FROM product_image WHERE product_id = p.id LIMIT 1) as image_url
        FROM request_line rl
        LEFT JOIN product p ON p.id = rl.product_id
        WHERE rl.request_id = ?
    ');
    $stmt->execute([$requestId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get request for email (token sending)
 */
function getRequestForEmail(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT token, email, first_name, last_name, establishment_name FROM request WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

// =============================================================================
// PRODUCT MANAGEMENT QUERIES
// =============================================================================

/**
 * Get filtered products
 */
function getFilteredProducts(string $search = '', int $categoryFilter = 0, bool $showInactive = false): array {
    global $pdo;
    
    $query = "SELECT p.*, c.name as category_name, pi.url as image_url
              FROM product p 
              LEFT JOIN category c ON p.category_id = c.id
              LEFT JOIN product_image pi ON p.id = pi.product_id
              WHERE 1=1";
    
    $params = [];
    
    if (!$showInactive) {
        $query .= " AND p.is_active = 1";
    }
    
    if (!empty($search)) {
        $query .= " AND (p.name LIKE :search OR p.reference LIKE :search)";
        $params['search'] = '%' . $search . '%';
    }
    
    if ($categoryFilter > 0) {
        $query .= " AND p.category_id = :category";
        $params['category'] = $categoryFilter;
    }
    
    $query .= " ORDER BY p.id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get product by ID
 */
function getProductById(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

/**
 * Get product image
 */
function getProductImage(int $productId): ?string {
    global $pdo;
    $stmt = $pdo->prepare("SELECT url FROM product_image WHERE product_id = ?");
    $stmt->execute([$productId]);
    $result = $stmt->fetchColumn();
    return $result ?: null;
}

/**
 * Toggle product active status
 */
function toggleProductActive(int $id, bool $isActive): bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE product SET is_active = ? WHERE id = ?");
    $stmt->execute([$isActive ? 1 : 0, $id]);
    return $stmt->rowCount() > 0;
}

/**
 * Delete product and its image
 */
function deleteProduct(int $id): bool {
    global $pdo;
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM product_image WHERE product_id = ?")->execute([$id]);
        $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        $stmt->execute([$id]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

// =============================================================================
// CHART/ANALYTICS QUERIES
// =============================================================================

/**
 * Get daily request statistics for chart
 */
function getDailyRequestStats(int $days, int $statusFilter = 0, int $categoryFilter = 0): array {
    global $pdo;
    
    $query = "
        SELECT DATE(r.request_date) as date, COUNT(*) as total
        FROM request r
        LEFT JOIN product p ON r.product_id = p.id
        WHERE r.request_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
    ";
    
    $params = [$days];
    
    if ($statusFilter > 0) {
        $query .= " AND r.status_id = ?";
        $params[] = $statusFilter;
    }
    
    if ($categoryFilter > 0) {
        $query .= " AND p.category_id = ?";
        $params[] = $categoryFilter;
    }
    
    $query .= " GROUP BY DATE(r.request_date) ORDER BY date ASC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
