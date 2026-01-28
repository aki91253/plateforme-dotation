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
            SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as en_preparation,
            SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as traitees,
            SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as refusees
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
    // Use direct query with integer values embedded (safe because function parameters are typed as int)
    $threshold = (int)$threshold;
    $limit = (int)$limit;
    $sql = "SELECT name, stock as quantity 
            FROM product 
            WHERE is_active = 1 AND stock < $threshold
            ORDER BY stock ASC 
            LIMIT $limit";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent requests
 */
function getRecentRequests(int $limit = 5): array {
    global $pdo;
    // Use direct query with integer value embedded (safe because function parameter is typed as int)
    $limit = (int)$limit;
    $sql = "SELECT r.token as request_number, r.establishment_name, r.status_id, t.libelle as status_label, r.request_date, 
               CONCAT(resp.first_name, ' ', resp.last_name) as responsible_name
        FROM request r 
        LEFT JOIN responsible resp ON resp.id = r.responsible_id
        LEFT JOIN type_status t ON t.id = r.status_id
        WHERE t.libelle = 'En attente'
        ORDER BY r.request_date DESC 
        LIMIT $limit";
    $stmt = $pdo->query($sql);
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
 * Get admin by ID
 */
function getAdminById(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM responsible WHERE id = ?");
    $stmt->execute([$id]);
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
 * Supports multi-select filters via arrays
 */
function getDailyRequestStats(int $days, array $statusFilters = [], array $categoryFilters = []): array {
    global $pdo;
    
    $query = "
        SELECT DATE(r.request_date) as date, COUNT(*) as total
        FROM request r
        LEFT JOIN request_line rl ON r.id = rl.request_id
        LEFT JOIN product p ON rl.product_id = p.id
        WHERE r.request_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
    ";
    
    $params = ['days' => $days];
    
    // Status filter (multi-select)
    if (!empty($statusFilters)) {
        $placeholders = [];
        foreach ($statusFilters as $i => $statusId) {
            $placeholders[] = ":status$i";
            $params["status$i"] = $statusId;
        }
        $query .= " AND r.status_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Category filter (multi-select)
    if (!empty($categoryFilters)) {
        $placeholders = [];
        foreach ($categoryFilters as $i => $catId) {
            $placeholders[] = ":cat$i";
            $params["cat$i"] = $catId;
        }
        $query .= " AND p.category_id IN (" . implode(',', $placeholders) . ")";
    }
    
    $query .= " GROUP BY DATE(r.request_date) ORDER BY date ASC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =============================================================================
// DONATIONS PAGE QUERIES (Complex Filtering)
// =============================================================================

/**
 * Get filtered donations/products for the public catalog
 * Supports: categories, resource types, languages, disciplines, collections, search
 */
function getDonationsWithFilters(array $filters, int $page = 1, int $perPage = 12): array {
    global $pdo;
    
    $query = "SELECT p.*, c.name as category_name, rt.libelle as resource_type_name, 
              l.langue as language_name, d.libelle as discipline_name,
              pi.url as image_url, pi.alt_text as image_alt
              FROM product p 
              LEFT JOIN category c ON p.category_id = c.id 
              LEFT JOIN type_ressource rt ON p.id_ressource = rt.id
              LEFT JOIN langue_product l ON p.langue_id = l.id
              LEFT JOIN discipline d ON p.discipline_id = d.id
              LEFT JOIN product_image pi ON p.id = pi.product_id
              WHERE p.is_active = 1 AND p.stock > 0";
    
    $params = [];
    
    // Category filter
    if (!empty($filters['categories'])) {
        $placeholders = [];
        foreach ($filters['categories'] as $i => $catId) {
            $placeholders[] = ":cat$i";
            $params["cat$i"] = $catId;
        }
        $query .= " AND p.category_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Resource type filter
    if (!empty($filters['resourceTypes'])) {
        $placeholders = [];
        foreach ($filters['resourceTypes'] as $i => $rtId) {
            $placeholders[] = ":rt$i";
            $params["rt$i"] = $rtId;
        }
        $query .= " AND p.id_ressource IN (" . implode(',', $placeholders) . ")";
    }
    
    // Language filter
    if (!empty($filters['languages'])) {
        $placeholders = [];
        foreach ($filters['languages'] as $i => $langId) {
            $placeholders[] = ":lang$i";
            $params["lang$i"] = $langId;
        }
        $query .= " AND p.langue_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Discipline filter
    if (!empty($filters['disciplines'])) {
        $placeholders = [];
        foreach ($filters['disciplines'] as $i => $discId) {
            $placeholders[] = ":disc$i";
            $params["disc$i"] = $discId;
        }
        $query .= " AND p.discipline_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Collection filter
    if (!empty($filters['collections'])) {
        $placeholders = [];
        foreach ($filters['collections'] as $i => $coll) {
            $placeholders[] = ":coll$i";
            $params["coll$i"] = $coll;
        }
        $query .= " AND p.collection IN (" . implode(',', $placeholders) . ")";
    }
    
    // Search filter
    if (!empty($filters['search'])) {
        $query .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.reference LIKE :search)";
        $params['search'] = '%' . $filters['search'] . '%';
    }
    
    // Add ordering and pagination
    $offset = ($page - 1) * $perPage;
    $query .= " ORDER BY p.name LIMIT $perPage OFFSET $offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Count total donations matching filters (for pagination)
 */
function countDonationsWithFilters(array $filters): int {
    global $pdo;
    
    $query = "SELECT COUNT(DISTINCT p.id) as total
              FROM product p 
              LEFT JOIN category c ON p.category_id = c.id 
              LEFT JOIN type_ressource rt ON p.id_ressource = rt.id
              LEFT JOIN langue_product l ON p.langue_id = l.id
              LEFT JOIN discipline d ON p.discipline_id = d.id
              LEFT JOIN product_image pi ON p.id = pi.product_id
              WHERE p.is_active = 1 AND p.stock > 0";
    
    $params = [];
    
    // Category filter
    if (!empty($filters['categories'])) {
        $placeholders = [];
        foreach ($filters['categories'] as $i => $catId) {
            $placeholders[] = ":cat$i";
            $params["cat$i"] = $catId;
        }
        $query .= " AND p.category_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Resource type filter
    if (!empty($filters['resourceTypes'])) {
        $placeholders = [];
        foreach ($filters['resourceTypes'] as $i => $rtId) {
            $placeholders[] = ":rt$i";
            $params["rt$i"] = $rtId;
        }
        $query .= " AND p.id_ressource IN (" . implode(',', $placeholders) . ")";
    }
    
    // Language filter
    if (!empty($filters['languages'])) {
        $placeholders = [];
        foreach ($filters['languages'] as $i => $langId) {
            $placeholders[] = ":lang$i";
            $params["lang$i"] = $langId;
        }
        $query .= " AND p.langue_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Discipline filter
    if (!empty($filters['disciplines'])) {
        $placeholders = [];
        foreach ($filters['disciplines'] as $i => $discId) {
            $placeholders[] = ":disc$i";
            $params["disc$i"] = $discId;
        }
        $query .= " AND p.discipline_id IN (" . implode(',', $placeholders) . ")";
    }
    
    // Collection filter
    if (!empty($filters['collections'])) {
        $placeholders = [];
        foreach ($filters['collections'] as $i => $coll) {
            $placeholders[] = ":coll$i";
            $params["coll$i"] = $coll;
        }
        $query .= " AND p.collection IN (" . implode(',', $placeholders) . ")";
    }
    
    // Search filter
    if (!empty($filters['search'])) {
        $query .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.reference LIKE :search)";
        $params['search'] = '%' . $filters['search'] . '%';
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// =============================================================================
// PRODUCT CRUD QUERIES
// =============================================================================

/**
 * Get full product details with all related data (for details page)
 */
function getProductWithFullDetails(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT p.*, p.stock as stock_quantity, c.name as category_name, 
               l.langue as langue, d.libelle as discipline, t.libelle as ressource,
               pi.url as image_url, pi.alt_text as image_alt
        FROM product p
        LEFT JOIN category c ON p.category_id = c.id
        LEFT JOIN langue_product l ON p.langue_id = l.id
        LEFT JOIN discipline d ON p.discipline_id = d.id 
        LEFT JOIN type_ressource t ON p.id_ressource = t.id
        LEFT JOIN product_image pi ON p.id = pi.product_id
        WHERE p.id = ?
    ');
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

/**
 * Create a new product with all fields
 */
function createProduct(array $data): int {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO product 
        (name, reference, description, collection, category_id, location, 
         responsible_id, quantite_totale, stock, langue_id, id_ressource, 
         discipline_id, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['name'],
        $data['reference'] ?? '',
        $data['description'] ?? '',
        $data['collection'] ?? '',
        $data['category_id'],
        $data['location'] ?? '',
        $data['responsible_id'] > 0 ? $data['responsible_id'] : null,
        $data['quantite_totale'] ?? 0,
        $data['stock'] ?? 0,
        $data['langue_id'] > 0 ? $data['langue_id'] : null,
        $data['id_ressource'],
        $data['discipline_id'] > 0 ? $data['discipline_id'] : null,
        $data['is_active'] ?? 1
    ]);
    
    return (int)$pdo->lastInsertId();
}

/**
 * Update an existing product
 */
function updateProduct(int $id, array $data): bool {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE product 
        SET name = ?, description = ?, category_id = ?, location = ?, 
            responsible_id = ?, quantite_totale = ?, stock = ?, 
            is_active = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $data['name'],
        $data['description'] ?? '',
        $data['category_id'],
        $data['location'] ?? '',
        $data['responsible_id'] ?? null,
        $data['quantite_totale'] ?? 0,
        $data['stock'] ?? 0,
        $data['is_active'] ?? 1,
        $id
    ]);
    
    return $stmt->rowCount() >= 0;
}

/**
 * Create product image record
 */
function createProductImage(int $productId, string $url): bool {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO product_image (product_id, url) VALUES (?, ?)");
    $stmt->execute([$productId, $url]);
    return $stmt->rowCount() > 0;
}

/**
 * Check if product image exists
 */
function productImageExists(int $productId): bool {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM product_image WHERE product_id = ?");
    $stmt->execute([$productId]);
    return $stmt->fetch() !== false;
}

/**
 * Update existing product image
 */
function updateProductImageUrl(int $productId, string $url): bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE product_image SET url = ? WHERE product_id = ?");
    $stmt->execute([$url, $productId]);
    return $stmt->rowCount() >= 0;
}

/**
 * Update or create product image
 */
function upsertProductImage(int $productId, string $url): bool {
    if (productImageExists($productId)) {
        return updateProductImageUrl($productId, $url);
    } else {
        return createProductImage($productId, $url);
    }
}

/**
 * Add modification history entry
 */
function addProductModificationHistory(int $productId): bool {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO historique_modif (product_id, date_modif) VALUES (?, CURDATE())");
    $stmt->execute([$productId]);
    return $stmt->rowCount() > 0;
}

// =============================================================================
// REQUEST TRACKING QUERIES (demande.php)
// =============================================================================

/**
 * Get request by token for tracking page
 */
function getRequestByToken($token) {
    global $pdo;
    
    $sql = "SELECT 
                r.id,
                r.token,
                r.product_id,
                CONCAT(r.last_name, ' ', r.first_name) AS demandeur_nom,
                r.email AS demandeur_email,
                r.phone AS demandeur_phone,
                r.establishment_name AS demandeur_institution,
                r.request_date,
                r.comment,
                r.status_id,
                ts.libelle AS status,
                r.responsible_id,
                CONCAT(resp.last_name, ' ', resp.first_name) AS responsable_nom,
                resp.email_pro AS responsable_email,
                resp.job_title AS responsable_fonction,
                rl.quantity
            FROM request r
            JOIN request_line rl ON r.id = rl.request_id
            LEFT JOIN type_status ts ON r.status_id = ts.id
            LEFT JOIN responsible resp ON r.responsible_id = resp.id
            WHERE r.token = :token";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get request status history
 */
function getRequestStatusHistory(string $token): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            re.token,
            h.changed_at,
            t.libelle
        FROM request re
        JOIN historique_etat h ON re.id = h.request_id
        JOIN type_status t ON h.status_id = t.id
        WHERE token = :token 
        ORDER BY h.changed_at DESC
    ");
    $stmt->execute(['token' => $token]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get products in a request (request lines)
 */
function getRequestProductsByRequestId(int $requestId): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT rl.*, p.name as product_name, p.reference
        FROM request_line rl
        JOIN product p ON p.id = rl.product_id
        WHERE rl.request_id = :id
    ");
    $stmt->execute(['id' => $requestId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =============================================================================
// REQUEST SUBMISSION QUERIES (submit_request.php)
// =============================================================================

/**
 * Check if a token already exists
 */
function tokenExists(string $token): bool {
    global $pdo;
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM request WHERE token = ?');
    $stmt->execute([$token]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Create a new request (main record)
 */
function createRequest(array $data): int {
    global $pdo;
    $stmt = $pdo->prepare('
        INSERT INTO request 
        (token, product_id, last_name, first_name, email, phone, 
         establishment_name, establishment_address, establishment_postal, 
         establishment_city, request_date, comment, status_id, responsible_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, 1, 1)
    ');
    $stmt->execute([
        $data['token'],
        $data['product_id'],
        $data['last_name'],
        $data['first_name'],
        $data['email'],
        $data['phone'],
        $data['establishment_name'],
        $data['establishment_address'] ?? '',
        $data['establishment_postal'] ?? '',
        $data['establishment_city'] ?? '',
        $data['comment'] ?? ''
    ]);
    return (int)$pdo->lastInsertId();
}

/**
 * Create a request line (product in request)
 */
function createRequestLine(int $requestId, int $productId, int $quantity, string $comment = ''): bool {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO request_line (request_id, product_id, quantity, comment) VALUES (?, ?, ?, ?)');
    $stmt->execute([$requestId, $productId, $quantity, $comment]);
    return $stmt->rowCount() > 0;
}
?>
