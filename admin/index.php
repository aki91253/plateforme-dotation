<?php
/**
 * Admin Dashboard - Main Index Page
 * Overview with KPI cards and recent activity
 */
require_once 'includes/admin_auth.php';
requireAdmin();

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=canope-reseau;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get statistics
    $totalProducts = $pdo->query('SELECT COUNT(*) FROM product WHERE is_active = 1')->fetchColumn();
    $totalStock = $pdo->query('SELECT SUM(stock) FROM product WHERE is_active = 1')->fetchColumn() ?? 0;
    $pendingRequests = $pdo->query("SELECT COUNT(*) FROM request WHERE status_id = 1")->fetchColumn();
    $completedRequests = $pdo->query("SELECT COUNT(*) FROM request WHERE status_id >= 4")->fetchColumn();
    
    // Get low stock items (< 20 units)
    $lowStockQuery = $pdo->query('
        SELECT name, stock as quantity 
        FROM product 
        WHERE is_active = 1 AND stock < 5
        ORDER BY stock ASC 
        LIMIT 5
    ');
    $lowStockItems = $lowStockQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Get recent requests
    $recentRequestsQuery = $pdo->query('
        SELECT r.token as request_number, r.establishment_name, r.status_id, t.libelle as status_label, r.request_date, 
               CONCAT(resp.first_name, " ", resp.last_name) as responsible_name
        FROM request r 
        LEFT JOIN responsible resp ON resp.id = r.responsible_id
        LEFT JOIN type_status t ON t.id = r.status_id
        ORDER BY r.request_date DESC 
        LIMIT 5
    ');
    $recentRequests = $recentRequestsQuery->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données: ' . $e->getMessage());
}

include 'includes/admin_header.php';
?>

<!-- Dashboard Content -->
<div class="space-y-8">
    <!-- KPI Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($totalProducts) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Dotations disponibles</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Actif
                </span>
            </div>
        </div>
        
        <!-- Total Stock Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($totalStock) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Articles en stock</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-canope-dark to-canope-teal rounded-xl flex items-center justify-center shadow-lg shadow-canope-dark/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-500">Total disponible</span>
            </div>
        </div>
        
        <!-- Pending Requests Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($pendingRequests) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Demandes en cours</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-amber-500 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    À traiter
                </span>
            </div>
        </div>
        
        <!-- Completed Requests Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($completedRequests) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Demandes traitées</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-emerald-500 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Terminées
                </span>
            </div>
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Demandes récentes</h2>
                <a href="requests.php" class="text-canope-dark hover:text-canope-olive text-sm font-medium transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php if (empty($recentRequests)): ?>
                <p class="px-6 py-8 text-gray-500 text-center">Aucune demande récente</p>
                <?php else: ?>
                <?php foreach ($recentRequests as $request): ?>
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($request['request_number']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($request['establishment_name']) ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <?php
                        $statusColors = [
                            1 => 'bg-amber-100 text-amber-700',
                            2 => 'bg-blue-100 text-blue-700',
                            3 => 'bg-indigo-100 text-indigo-700',
                            4 => 'bg-cyan-100 text-cyan-700',
                            5 => 'bg-emerald-100 text-emerald-700',
                            6 => 'bg-red-100 text-red-700'
                        ];
                        $statusClass = $statusColors[$request['status_id']] ?? 'bg-gray-100 text-gray-700';
                        $statusLabel = $request['status_label'] ?? 'Inconnu';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                            <?= $statusLabel ?>
                        </span>
                        <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y', strtotime($request['request_date'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Alertes stock faible</h2>
                <a href="stock.php" class="text-canope-dark hover:text-canope-olive text-sm font-medium transition-colors">
                    Gérer →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php if (empty($lowStockItems)): ?>
                <div class="px-6 py-8 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-gray-500">Tous les stocks sont à niveau</p>
                </div>
                <?php else: ?>
                <?php foreach ($lowStockItems as $item): ?>
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 <?= $item['quantity'] == 0 ? 'bg-red-100' : 'bg-amber-100' ?> rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?= $item['quantity'] == 0 ? 'text-red-500' : 'text-amber-500' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-sm <?= $item['quantity'] == 0 ? 'text-red-500' : 'text-amber-500' ?>">
                                <?= $item['quantity'] == 0 ? 'Rupture de stock' : 'Stock faible' ?>
                            </p>
                        </div>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold <?= $item['quantity'] == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' ?>">
                            <?= $item['quantity'] ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="products.php" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white hover:shadow-lg hover:shadow-blue-500/25 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-lg">Gérer les dotations</p>
                    <p class="text-blue-100 text-sm">Ajouter ou modifier</p>
                </div>
            </div>
        </a>
        
        <a href="stock.php" class="bg-gradient-to-r from-canope-dark to-canope-teal rounded-2xl p-6 text-white hover:shadow-lg hover:shadow-canope-dark/25 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-lg">Mettre à jour le stock</p>
                    <p class="text-green-100 text-sm">Inventaire rapide</p>
                </div>
            </div>
        </a>
        
        <a href="requests.php" class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white hover:shadow-lg hover:shadow-amber-500/25 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-lg">Traiter les demandes</p>
                    <p class="text-amber-100 text-sm"><?= $pendingRequests ?> en attente</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      