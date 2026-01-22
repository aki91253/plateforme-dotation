<?php
/**
 * Admin Dashboard - Main Index Page
 * Overview with KPI cards and recent activity
 */
require_once 'includes/admin_auth.php';
require_once '../includes/db.php';
require_once '../includes/queries.php';
requireAdmin();

// Initialiser les variables pour éviter les erreurs
$searchTerm = '';
$categoryFilter = 0;
$showInactive = false;

// Récupérer les données via les fonctions centralisées
$categories = getAllCategories();
$dashboardStats = getDashboardStats();
$totalProducts = $dashboardStats['totalProducts'];
$totalStock = $dashboardStats['totalStock'];
$pendingRequests = $dashboardStats['pendingRequests'];
$completedRequests = $dashboardStats['completedRequests'];
$lowStockItems = getLowStockItems(20, 5);
$recentRequests = getRecentRequests(5);

// Récupérer les filtres pour le graphique
$days = isset($_GET['days']) ? intval($_GET['days']) : 30;

// Filtres multi-sélection pour le graphique
$selectedStatuses = [];
if (isset($_GET['status_filter'])) {
    if (is_array($_GET['status_filter'])) {
        $selectedStatuses = array_map('intval', $_GET['status_filter']);
    } else {
        $selectedStatuses = array_map('intval', explode(',', $_GET['status_filter']));
    }
}

$selectedCategories = [];
if (isset($_GET['category_chart'])) {
    if (is_array($_GET['category_chart'])) {
        $selectedCategories = array_map('intval', $_GET['category_chart']);
    } else {
        $selectedCategories = array_map('intval', explode(',', $_GET['category_chart']));
    }
}

// Limiter le nombre de jours entre 7 et 365
$days = max(7, min(365, $days));

// Récupérer les statistiques quotidiennes via fonction centralisée
$dailyStats = getDailyRequestStats($days, $selectedStatuses, $selectedCategories);

// Préparer les données pour le graphique
$dates = [];
$totals = [];

// Remplir tous les jours
for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = date('d/m', strtotime($date));
    
    $found = false;
    foreach ($dailyStats as $stat) {
        if ($stat['date'] === $date) {
            $totals[] = (int)$stat['total'];
            $found = true;
            break;
        }
    }
    if (!$found) {
        $totals[] = 0;
    }
}

// Récupérer les statuts pour le filtre via fonction centralisée
$statuses = getAllStatuses();

include 'includes/admin_header.php';
?>

<!-- Dashboard Content -->
<div class="space-y-8">
    <!-- KPI Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products Card -->
         <a href="dotation_create.php">
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
</a>
        <!-- Total Stock Card -->
         <a href="stock.php">
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
</a>
        <!-- Pending Requests Card -->
         <a href="requests.php">
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
</a>
        <!-- Completed Requests Card -->
         <a href="requests.php">
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
</a>
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Demandes récentes</h2>
                <a href="requests.php" class="text-canope-dark hover:text-canope-olive text-sm font-medium
          transition-all duration-200 ease-out
          transform hover:scale-105">
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
                <a href="stock.php" class="text-canope-dark hover:text-canope-olive text-sm font-medium
          transition-all duration-200 ease-out
          transform hover:scale-105">
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
        <a href="dotation_create.php" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white hover:shadow-lg hover:shadow-blue-500/25 transition-all group">
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
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    
    <!-- Filter dropdown styles -->
    <style>
        .chart-filter-dropdown {
            position: relative;
            display: inline-block;
        }
        .chart-filter-dropdown-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 14px;
            min-width: 140px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.2s ease;
        }
        .chart-filter-dropdown-btn:hover {
            border-color: #3b82f6;
        }
        .chart-filter-dropdown-btn.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(59, 130, 246, 0.12) 100%);
            border-color: #3b82f6;
            color: #1e40af;
        }
        .chart-filter-dropdown-btn svg {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }
        .chart-filter-dropdown.open .chart-filter-dropdown-btn svg {
            transform: rotate(180deg);
        }
        .chart-filter-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 180px;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            z-index: 50;
            display: none;
            padding: 6px;
        }
        .chart-filter-dropdown.open .chart-filter-dropdown-menu {
            display: block;
            animation: dropdownFade 0.15s ease;
        }
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chart-filter-menu-item {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 13px;
            color: #475569;
            border-radius: 8px;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }
        .chart-filter-menu-item:hover {
            background: #f1f5f9;
            color: #1e40af;
        }
        .chart-filter-menu-item.selected {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12) 0%, rgba(59, 130, 246, 0.18) 100%);
            color: #1e40af;
            font-weight: 600;
        }
        .chart-filter-menu-item.selected::before {
            content: '✓';
            margin-right: 8px;
            font-weight: bold;
        }
        .chart-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.15) 100%);
            color: #1e40af;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .chart-filter-tag-remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.2);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .chart-filter-tag-remove:hover {
            background: #ef4444;
        }
        .chart-filter-tag-remove:hover svg {
            color: white;
        }
        .chart-filter-tag-remove svg {
            width: 10px;
            height: 10px;
            color: #1e40af;
        }
    </style>
    
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Demandes sur les derniers jours</h3>
        
        <!-- Filtres du graphique -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Nombre de jours -->
            <div class="flex items-center gap-2">
                <label for="days" class="text-sm text-gray-700 font-medium whitespace-nowrap">Période :</label>
                <select id="days-select" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="7" <?= $days == 7 ? 'selected' : '' ?>>7 jours</option>
                    <option value="14" <?= $days == 14 ? 'selected' : '' ?>>14 jours</option>
                    <option value="30" <?= $days == 30 ? 'selected' : '' ?>>30 jours</option>
                    <option value="60" <?= $days == 60 ? 'selected' : '' ?>>60 jours</option>
                    <option value="90" <?= $days == 90 ? 'selected' : '' ?>>90 jours</option>
                    <option value="180" <?= $days == 180 ? 'selected' : '' ?>>6 mois</option>
                    <option value="365" <?= $days == 365 ? 'selected' : '' ?>>1 an</option>
                </select>
            </div>

            <!-- Filtre par statut (multi-select) -->
            <div class="chart-filter-dropdown" data-dropdown="status_filter">
                <button type="button" class="chart-filter-dropdown-btn <?= !empty($selectedStatuses) ? 'active' : '' ?>">
                    <span>Statut<?= !empty($selectedStatuses) ? ' (' . count($selectedStatuses) . ')' : '' ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="chart-filter-dropdown-menu">
                    <?php foreach ($statuses as $status): ?>
                        <div class="chart-filter-menu-item <?= in_array($status['id'], $selectedStatuses) ? 'selected' : '' ?>"
                             data-filter="status_filter" data-value="<?= $status['id'] ?>">
                            <?= htmlspecialchars($status['libelle']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Filtre par catégorie (multi-select) -->
            <div class="chart-filter-dropdown" data-dropdown="category_chart">
                <button type="button" class="chart-filter-dropdown-btn <?= !empty($selectedCategories) ? 'active' : '' ?>">
                    <span>Catégorie<?= !empty($selectedCategories) ? ' (' . count($selectedCategories) . ')' : '' ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="chart-filter-dropdown-menu">
                    <?php foreach ($categories as $cat): ?>
                        <div class="chart-filter-menu-item <?= in_array($cat['id'], $selectedCategories) ? 'selected' : '' ?>"
                             data-filter="category_chart" data-value="<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bouton Réinitialiser -->
            <?php $hasActiveFilters = $days != 30 || !empty($selectedStatuses) || !empty($selectedCategories); ?>
            <a href="index.php" id="reset-filters-btn" class="px-3 py-1.5 text-sm text-gray-500 hover:text-red-500 font-medium transition-colors" style="<?= $hasActiveFilters ? '' : 'display:none' ?>">
                ✕ Réinitialiser
            </a>
        </div>
    </div>

    <!-- Tags des filtres actifs -->
    <div id="filter-tags-container" class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-gray-100 <?= empty($selectedStatuses) && empty($selectedCategories) ? 'hidden' : '' ?>">
        <span class="text-xs text-gray-500 font-medium">Filtres actifs :</span>
        
        <?php foreach ($selectedStatuses as $statusId): 
            $statusName = '';
            foreach ($statuses as $s) {
                if ($s['id'] == $statusId) { $statusName = $s['libelle']; break; }
            }
        ?>
            <span class="chart-filter-tag">
                <?= htmlspecialchars($statusName) ?>
                <span class="chart-filter-tag-remove" data-filter="status_filter" data-value="<?= $statusId ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </span>
        <?php endforeach; ?>
        
        <?php foreach ($selectedCategories as $catId): 
            $catName = '';
            foreach ($categories as $c) {
                if ($c['id'] == $catId) { $catName = $c['name']; break; }
            }
        ?>
            <span class="chart-filter-tag">
                <?= htmlspecialchars($catName) ?>
                <span class="chart-filter-tag-remove" data-filter="category_chart" data-value="<?= $catId ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </span>
        <?php endforeach; ?>
    </div>

    <!-- Graphique -->
    <canvas id="demandesChart" height="80"></canvas>
    
    <!-- Résumé sous le graphique -->
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-sm">
        <div class="flex items-center gap-6">
            <div>
                <span class="text-gray-500">Total des demandes :</span>
                <span class="font-bold text-gray-900 ml-1" data-stat="total"><?= array_sum($totals) ?></span>
            </div>
            <div>
                <span class="text-gray-500">Moyenne par jour :</span>
                <span class="font-bold text-gray-900 ml-1" data-stat="average"><?= $days > 0 ? round(array_sum($totals) / $days, 1) : 0 ?></span>
            </div>
            <div>
                <span class="text-gray-500">Pic :</span>
                <span class="font-bold text-gray-900 ml-1" data-stat="peak"><?= !empty($totals) ? max($totals) : 0 ?></span>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('demandesChart').getContext('2d');
const demandesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Nombre de demandes',
            data: <?= json_encode($totals) ?>,
            borderColor: 'rgb(37, 99, 235)',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(37, 99, 235)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleColor: '#fff',
                bodyColor: '#fff',
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' demande(s)';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Multi-select filter dropdown functionality
document.querySelectorAll('.chart-filter-dropdown').forEach(dropdown => {
    const btn = dropdown.querySelector('.chart-filter-dropdown-btn');
    
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        // Close other dropdowns
        document.querySelectorAll('.chart-filter-dropdown').forEach(d => {
            if (d !== dropdown) d.classList.remove('open');
        });
        // Toggle current dropdown
        dropdown.classList.toggle('open');
    });
});

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.chart-filter-dropdown')) {
        document.querySelectorAll('.chart-filter-dropdown').forEach(d => d.classList.remove('open'));
    }
});

// Track current filter state
let currentFilters = {
    days: <?= $days ?>,
    status_filter: <?= json_encode($selectedStatuses) ?>,
    category_chart: <?= json_encode($selectedCategories) ?>
};

// Function to update chart via AJAX
async function updateChartData() {
    // Build query string
    const params = new URLSearchParams();
    params.set('days', currentFilters.days);
    currentFilters.status_filter.forEach(v => params.append('status_filter[]', v));
    currentFilters.category_chart.forEach(v => params.append('category_chart[]', v));
    
    try {
        const response = await fetch('ajax_chart_data.php?' + params.toString());
        const data = await response.json();
        
        if (data.success) {
            // Update chart data
            demandesChart.data.labels = data.dates;
            demandesChart.data.datasets[0].data = data.totals;
            demandesChart.update('none'); // 'none' for no animation, or 'default' for animation
            
            // Update summary stats
            document.querySelector('[data-stat="total"]').textContent = data.summary.total;
            document.querySelector('[data-stat="average"]').textContent = data.summary.average;
            document.querySelector('[data-stat="peak"]').textContent = data.summary.peak;
            
            // Update URL without reload (for bookmarking/sharing)
            const newUrl = 'index.php' + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    } catch (error) {
        console.error('Error fetching chart data:', error);
    }
}

// Function to update filter UI (tags, button states)
function updateFilterUI() {
    // Update status dropdown button
    const statusBtn = document.querySelector('[data-dropdown="status_filter"] .chart-filter-dropdown-btn');
    const statusCount = currentFilters.status_filter.length;
    statusBtn.querySelector('span').textContent = 'Statut' + (statusCount > 0 ? ' (' + statusCount + ')' : '');
    statusBtn.classList.toggle('active', statusCount > 0);
    
    // Update category dropdown button
    const catBtn = document.querySelector('[data-dropdown="category_chart"] .chart-filter-dropdown-btn');
    const catCount = currentFilters.category_chart.length;
    catBtn.querySelector('span').textContent = 'Catégorie' + (catCount > 0 ? ' (' + catCount + ')' : '');
    catBtn.classList.toggle('active', catCount > 0);
    
    // Update menu item selections
    document.querySelectorAll('[data-filter="status_filter"]').forEach(item => {
        item.classList.toggle('selected', currentFilters.status_filter.includes(parseInt(item.dataset.value)));
    });
    document.querySelectorAll('[data-filter="category_chart"]').forEach(item => {
        item.classList.toggle('selected', currentFilters.category_chart.includes(parseInt(item.dataset.value)));
    });
    
    // Update filter tags
    updateFilterTags();
    
    // Show/hide reset button
    const hasFilters = currentFilters.days !== 30 || statusCount > 0 || catCount > 0;
    const resetBtn = document.getElementById('reset-filters-btn');
    if (resetBtn) {
        resetBtn.style.display = hasFilters ? 'inline' : 'none';
    }
}

// Function to update filter tags dynamically
function updateFilterTags() {
    const tagsContainer = document.getElementById('filter-tags-container');
    if (!tagsContainer) return;
    
    let tagsHtml = '';
    
    // Status tags
    currentFilters.status_filter.forEach(statusId => {
        const item = document.querySelector(`[data-filter="status_filter"][data-value="${statusId}"]`);
        if (item) {
            const name = item.textContent.trim();
            tagsHtml += `
                <span class="chart-filter-tag">
                    ${name}
                    <span class="chart-filter-tag-remove" data-filter="status_filter" data-value="${statusId}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </span>
            `;
        }
    });
    
    // Category tags
    currentFilters.category_chart.forEach(catId => {
        const item = document.querySelector(`[data-filter="category_chart"][data-value="${catId}"]`);
        if (item) {
            const name = item.textContent.trim();
            tagsHtml += `
                <span class="chart-filter-tag">
                    ${name}
                    <span class="chart-filter-tag-remove" data-filter="category_chart" data-value="${catId}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </span>
            `;
        }
    });
    
    if (tagsHtml) {
        tagsContainer.innerHTML = '<span class="text-xs text-gray-500 font-medium">Filtres actifs :</span>' + tagsHtml;
        tagsContainer.classList.remove('hidden');
        // Re-attach tag removal listeners
        attachTagRemoveListeners();
    } else {
        tagsContainer.classList.add('hidden');
    }
}

// Handle filter item clicks - toggle selection via AJAX
document.querySelectorAll('.chart-filter-menu-item').forEach(item => {
    item.addEventListener('click', (e) => {
        const filterType = item.dataset.filter;
        const filterValue = parseInt(item.dataset.value);
        const filterArray = currentFilters[filterType];
        
        const index = filterArray.indexOf(filterValue);
        if (index > -1) {
            // Remove from array
            filterArray.splice(index, 1);
        } else {
            // Add to array
            filterArray.push(filterValue);
        }
        
        updateFilterUI();
        updateChartData();
    });
});

// Function to attach tag removal listeners (needed after dynamic update)
function attachTagRemoveListeners() {
    document.querySelectorAll('.chart-filter-tag-remove').forEach(removeBtn => {
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const filterType = removeBtn.dataset.filter;
            const filterValue = parseInt(removeBtn.dataset.value);
            
            const filterArray = currentFilters[filterType];
            const index = filterArray.indexOf(filterValue);
            if (index > -1) {
                filterArray.splice(index, 1);
            }
            
            updateFilterUI();
            updateChartData();
        });
    });
}

// Attach initial tag removal listeners
attachTagRemoveListeners();

// Handle days select change - update via AJAX
document.getElementById('days-select').addEventListener('change', (e) => {
    currentFilters.days = parseInt(e.target.value);
    updateChartData();
});

// Handle reset button
const resetBtn = document.getElementById('reset-filters-btn');
if (resetBtn) {
    resetBtn.addEventListener('click', (e) => {
        e.preventDefault();
        currentFilters = { days: 30, status_filter: [], category_chart: [] };
        document.getElementById('days-select').value = '30';
        updateFilterUI();
        updateChartData();
    });
}
</script>
</div>

<?php include 'includes/admin_footer.php'; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      