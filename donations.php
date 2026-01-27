<?php
require_once 'includes/db.php';
require_once 'includes/queries.php';
require_once 'admin/maintenance_check.php';

// GET les filtres sélectionnés depuis l'URL
$selectedCategories = [];
if (isset($_GET['niveau'])) {
    if (is_array($_GET['niveau'])) {
        $selectedCategories = array_map('intval', $_GET['niveau']);
    } else {
        $selectedCategories = array_map('intval', explode(',', $_GET['niveau']));
    }
}

$selectedResourceTypes = [];
if (isset($_GET['resource_type'])) {
    if (is_array($_GET['resource_type'])) {
        $selectedResourceTypes = array_map('intval', $_GET['resource_type']);
    } else {
        $selectedResourceTypes = array_map('intval', explode(',', $_GET['resource_type']));
    }
}

$selectedLanguages = [];
if (isset($_GET['langue'])) {
    if (is_array($_GET['langue'])) {
        $selectedLanguages = array_map('intval', $_GET['langue']);
    } else {
        $selectedLanguages = array_map('intval', explode(',', $_GET['langue']));
    }
}

$selectedDisciplines = [];
if (isset($_GET['discipline'])) {
    if (is_array($_GET['discipline'])) {
        $selectedDisciplines = array_map('intval', $_GET['discipline']);
    } else {
        $selectedDisciplines = array_map('intval', explode(',', $_GET['discipline']));
    }
}

$selectedCollections = [];
if (isset($_GET['collection'])) {
    if (is_array($_GET['collection'])) {
        $selectedCollections = $_GET['collection'];
    } else {
        $selectedCollections = explode(',', $_GET['collection']);
    }
}

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Récupérer les options des filtres avec les fonctions centralisées
$categories = getAllCategories();
$resourceTypes = getAllRessources();
$languages = getAllLangues();
$disciplines = getAllDisciplines();

// Récupérer les collections distinctes des produits
$collectionsData = getDistinctCollections();
$collections = array_column($collectionsData, 'collection');

// Construire le tableau de filtres pour les fonctions centralisées
$filters = [
    'categories' => $selectedCategories,
    'resourceTypes' => $selectedResourceTypes,
    'languages' => $selectedLanguages,
    'disciplines' => $selectedDisciplines,
    'collections' => $selectedCollections,
    'search' => $searchTerm
];

// Pagination settings
$productsPerPage = 12;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Compter le total de produits et récupérer les produits avec les fonctions centralisées
$totalProducts = countDonationsWithFilters($filters);
$totalPages = ceil($totalProducts / $productsPerPage);
$products = getDonationsWithFilters($filters, $currentPage, $productsPerPage);

// Vérifie si des filtres sont actifs
$hasActiveFilters = !empty($selectedCategories) || !empty($selectedResourceTypes) || 
                    !empty($selectedLanguages) || !empty($selectedDisciplines) || !empty($selectedCollections) || !empty($searchTerm);

// Fonction pour construire l'URL sans un filtre spécifique
function buildFilterUrl($filterType, $valueToRemove) {
    global $selectedResourceTypes, $selectedLanguages, $selectedDisciplines, $selectedCollections, $selectedCategories, $searchTerm;
    
    $params = [];
    
    // Ajoute les paramètres resource_type
    foreach ($selectedResourceTypes as $val) {
        if ($filterType !== 'resource_type' || $val != $valueToRemove) {
            $params[] = 'resource_type[]=' . urlencode($val);
        }
    }
    
    // Ajoute les paramètres langue
    foreach ($selectedLanguages as $val) {
        if ($filterType !== 'langue' || $val != $valueToRemove) {
            $params[] = 'langue[]=' . urlencode($val);
        }
    }
    
    // Ajoute les paramètres discipline
    foreach ($selectedDisciplines as $val) {
        if ($filterType !== 'discipline' || $val != $valueToRemove) {
            $params[] = 'discipline[]=' . urlencode($val);
        }
    }
    
    // Ajoute les paramètres collection
    foreach ($selectedCollections as $val) {
        if ($filterType !== 'collection' || $val != $valueToRemove) {
            $params[] = 'collection[]=' . urlencode($val);
        }
    }
    
    // Ajoute les paramètres niveau
    foreach ($selectedCategories as $val) {
        if ($filterType !== 'niveau' || $val != $valueToRemove) {
            $params[] = 'niveau[]=' . urlencode($val);
        }
    }
    
    // Ajoute le paramètre de recherche
    if (!empty($searchTerm)) {
        $params[] = 'search=' . urlencode($searchTerm);
    }
    
    return 'donations.php' . (!empty($params) ? '?' . implode('&', $params) : '');
}

include 'includes/header.php';
?>

<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg stroke="#FFFFFF" class="w-16 h-16 mx-auto ml-1.5 -mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"">
						<path d="M8.627,7.885C8.499,8.388,7.873,8.101,8.13,8.177L4.12,7.143c-0.218-0.057-0.351-0.28-0.293-0.498c0.057-0.218,0.279-0.351,0.497-0.294l4.011,1.037C8.552,7.444,8.685,7.667,8.627,7.885 M8.334,10.123L4.323,9.086C4.105,9.031,3.883,9.162,3.826,9.38C3.769,9.598,3.901,9.82,4.12,9.877l4.01,1.037c-0.262-0.062,0.373,0.192,0.497-0.294C8.685,10.401,8.552,10.18,8.334,10.123 M7.131,12.507L4.323,11.78c-0.218-0.057-0.44,0.076-0.497,0.295c-0.057,0.218,0.075,0.439,0.293,0.495l2.809,0.726c-0.265-0.062,0.37,0.193,0.495-0.293C7.48,12.784,7.35,12.562,7.131,12.507M18.159,3.677v10.701c0,0.186-0.126,0.348-0.306,0.393l-7.755,1.948c-0.07,0.016-0.134,0.016-0.204,0l-7.748-1.948c-0.179-0.045-0.306-0.207-0.306-0.393V3.677c0-0.267,0.249-0.461,0.509-0.396l7.646,1.921l7.654-1.921C17.91,3.216,18.159,3.41,18.159,3.677 M9.589,5.939L2.656,4.203v9.857l6.933,1.737V5.939z M17.344,4.203l-6.939,1.736v9.859l6.939-1.737V4.203z M16.168,6.645c-0.058-0.218-0.279-0.351-0.498-0.294l-4.011,1.037c-0.218,0.057-0.351,0.28-0.293,0.498c0.128,0.503,0.755,0.216,0.498,0.292l4.009-1.034C16.092,7.085,16.225,6.863,16.168,6.645 M16.168,9.38c-0.058-0.218-0.279-0.349-0.498-0.294l-4.011,1.036c-0.218,0.057-0.351,0.279-0.293,0.498c0.124,0.486,0.759,0.232,0.498,0.294l4.009-1.037C16.092,9.82,16.225,9.598,16.168,9.38 M14.963,12.385c-0.055-0.219-0.276-0.35-0.495-0.294l-2.809,0.726c-0.218,0.056-0.351,0.279-0.293,0.496c0.127,0.506,0.755,0.218,0.498,0.293l2.807-0.723C14.89,12.825,15.021,12.603,14.963,12.385"></path>
					</svg>
            </div>
            <h1 class="text-3xl font-semibold text-white">Notre catalogue</h1>
        </div>
        <p class="text-white/80 text-sm ml-13">Bienvenue sur notre liste de dotations </p>
    </div>
</div>

    <style>
        @keyframes page1 {
            10% { transform: rotateZ(0deg); }
            100% { transform: rotateZ(-180deg); }
        }
        @keyframes page2 {
            10% { transform: rotateZ(0deg); }
            100% { transform: rotateZ(-180deg); }
        }
        .animate-page1 {
            animation: page1 0.7s ease-out infinite;
        }
        .animate-page2 {
            animation: page2 0.8s ease-out infinite;
        }
    </style>
    
    <?php if (!empty($searchTerm)): ?>
        <div class="mb-4 text-sm text-gray-600">
            Résultats pour: <strong><?php echo htmlspecialchars($searchTerm); ?></strong>
        </div>
    <?php endif; ?>
    
    <!-- Filter Bar -->
    <style>
        .filter-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(59, 85, 109, 0.08);
        }
        .filter-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(59, 85, 109, 0.1);
        }
        .filter-header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3B556D 0%, #5a7a9a 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(59, 85, 109, 0.25);
        }
        .filter-header-icon svg {
            width: 18px;
            height: 18px;
            color: white;
        }
        .filter-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #3B556D;
            margin: 0;
        }
        .filter-dropdown {
            position: relative;
            display: inline-block;
        }
        .filter-dropdown-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 18px;
            min-width: 170px;
            background: white;
            border: 2px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
        }
        .filter-dropdown-btn:hover {
            background: white;
            border-color: rgba(59, 85, 109, 0.3);
            box-shadow: 0 4px 12px rgba(59, 85, 109, 0.12);
            transform: translateY(-1px);
        }
        .filter-dropdown-btn.active {
            background: linear-gradient(135deg, rgba(59, 85, 109, 0.08) 0%, rgba(90, 122, 154, 0.08) 100%);
            border-color: #3B556D;
            color: #3B556D;
        }
        .filter-dropdown-btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.25s ease;
            opacity: 0.6;
        }
        .filter-dropdown.open .filter-dropdown-btn {
            border-color: #3B556D;
            box-shadow: 0 4px 16px rgba(59, 85, 109, 0.15);
        }
        .filter-dropdown.open .filter-dropdown-btn svg {
            transform: rotate(180deg);
            opacity: 1;
        }
        .filter-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 220px;
            background: white;
            border: 1px solid rgba(59, 85, 109, 0.1);
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 50;
            display: none;
            padding: 8px;
            animation: dropdownFadeIn 0.2s ease;
        }
        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .filter-dropdown.open .filter-dropdown-menu {
            display: block;
        }
        .filter-menu-item {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 14px;
            color: #475569;
            transition: all 0.15s ease;
            border-radius: 8px;
            margin-bottom: 2px;
        }
        .filter-menu-item:last-child {
            margin-bottom: 0;
        }
        .filter-menu-item:hover {
            background: linear-gradient(135deg, rgba(59, 85, 109, 0.06) 0%, rgba(90, 122, 154, 0.06) 100%);
            color: #3B556D;
        }
        .filter-menu-item.selected {
            background: linear-gradient(135deg, rgba(59, 85, 109, 0.12) 0%, rgba(90, 122, 154, 0.12) 100%);
            color: #3B556D;
            font-weight: 600;
        }
        .filter-menu-item.selected::before {
            content: '✓';
            margin-right: 8px;
            font-weight: bold;
        }
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(59, 85, 109, 0.1);
        }
        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: linear-gradient(135deg, rgba(59, 85, 109, 0.1) 0%, rgba(90, 122, 154, 0.1) 100%);
            color: #3B556D;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid rgba(59, 85, 109, 0.15);
        }
        .filter-tag:hover {
            background: linear-gradient(135deg, rgba(59, 85, 109, 0.15) 0%, rgba(90, 122, 154, 0.15) 100%);
        }
        .filter-tag-remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(59, 85, 109, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-tag-remove:hover {
            background: #e74c3c;
        }
        .filter-tag-remove:hover svg {
            color: white;
        }
        .filter-tag-remove svg {
            width: 10px;
            height: 10px;
            color: #3B556D;
        }
        .filter-search-box {
            display: flex;
            align-items: center;
            background: white;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px 16px;
            gap: 12px;
            flex: 1;
            max-width: 320px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
            transition: all 0.25s ease;
        }
        .filter-search-box:focus-within {
            border-color: #3B556D;
            box-shadow: 0 4px 16px rgba(59, 85, 109, 0.15);
        }
        .filter-search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            background: transparent;
        }
        .filter-search-box input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .filter-search-box svg {
            width: 20px;
            height: 20px;
            color: #94a3b8;
            transition: color 0.2s ease;
        }
        .filter-search-box:focus-within svg {
            color: #3B556D;
        }
        .clear-filters-btn {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 8px 14px;
            border-radius: 8px;
            background: transparent;
        }
        .clear-filters-btn:hover {
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.08);
        }
    </style>

    <form id="filterForm" method="GET" action="donations.php">
    <div class="filter-container">
        <div class="filter-header">
            <div class="filter-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <h3>Filtrer les ressources</h3>
        </div>
        
        <div class="flex flex-wrap items-center gap-4 mb-5">
            <!-- Search Box -->
            <div class="filter-search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Rechercher un produit..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            <!-- Type de Ressource Dropdown -->
            <div class="filter-dropdown" data-dropdown="resource_type">
                <button type="button" class="filter-dropdown-btn <?php echo !empty($selectedResourceTypes) ? 'active' : ''; ?>">
                    <span>Type de Ressource</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="filter-dropdown-menu">
                    <?php foreach ($resourceTypes as $rt): ?>
                        <div class="filter-menu-item <?php echo in_array($rt['id'], $selectedResourceTypes) ? 'selected' : ''; ?>"
                             data-filter="resource_type" data-value="<?php echo $rt['id']; ?>">
                            <?php echo htmlspecialchars($rt['libelle']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Niveau Dropdown -->
            <div class="filter-dropdown" data-dropdown="niveau">
                <button type="button" class="filter-dropdown-btn <?php echo !empty($selectedCategories) ? 'active' : ''; ?>">
                    <span>Niveau</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="filter-dropdown-menu">
                    <?php foreach ($categories as $category): ?>
                        <div class="filter-menu-item <?php echo in_array($category['id'], $selectedCategories) ? 'selected' : ''; ?>"
                             data-filter="niveau" data-value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Langue Dropdown -->
            <div class="filter-dropdown" data-dropdown="langue">
                <button type="button" class="filter-dropdown-btn <?php echo !empty($selectedLanguages) ? 'active' : ''; ?>">
                    <span>Langue</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="filter-dropdown-menu">
                    <?php foreach ($languages as $lang): ?>
                        <div class="filter-menu-item <?php echo in_array($lang['id'], $selectedLanguages) ? 'selected' : ''; ?>"
                             data-filter="langue" data-value="<?php echo $lang['id']; ?>">
                            <?php echo htmlspecialchars($lang['langue']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Discipline Dropdown -->
            <div class="filter-dropdown" data-dropdown="discipline">
                <button type="button" class="filter-dropdown-btn <?php echo !empty($selectedDisciplines) ? 'active' : ''; ?>">
                    <span>Discipline</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="filter-dropdown-menu">
                    <?php foreach ($disciplines as $disc): ?>
                        <div class="filter-menu-item <?php echo in_array($disc['id'], $selectedDisciplines) ? 'selected' : ''; ?>"
                             data-filter="discipline" data-value="<?php echo $disc['id']; ?>">
                            <?php echo htmlspecialchars($disc['libelle']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Collection Dropdown -->
            <?php if (!empty($collections)): ?>
            <div class="filter-dropdown" data-dropdown="collection">
                <button type="button" class="filter-dropdown-btn <?php echo !empty($selectedCollections) ? 'active' : ''; ?>">
                    <span>Collection</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="filter-dropdown-menu">
                    <?php foreach ($collections as $coll): ?>
                        <div class="filter-menu-item <?php echo in_array($coll, $selectedCollections) ? 'selected' : ''; ?>"
                             data-filter="collection" data-value="<?php echo htmlspecialchars($coll); ?>">
                            <?php echo htmlspecialchars($coll); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Effacer tout les filtres -->
            <?php if ($hasActiveFilters): ?>
                <a href="donations.php" class="clear-filters-btn">
                    ✕ Effacer tout
                </a>
            <?php endif; ?>
        </div>

        <!-- Tags des filtres actifs -->
        <?php if ($hasActiveFilters): ?>
        <div class="filter-tags">
            <?php 
            // Tags des types de ressources
            foreach ($selectedResourceTypes as $rtId): 
                $rtName = '';
                foreach ($resourceTypes as $rt) {
                    if ($rt['id'] == $rtId) { $rtName = $rt['libelle']; break; }
                }
            ?>
                <div class="filter-tag">
                    <span><?php echo htmlspecialchars($rtName); ?></span>
                    <a href="<?php echo buildFilterUrl('resource_type', $rtId); ?>" class="filter-tag-remove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php 
            // Tags des langues
            foreach ($selectedLanguages as $langId): 
                $langName = '';
                foreach ($languages as $lang) {
                    if ($lang['id'] == $langId) { $langName = $lang['langue']; break; }
                }
            ?>
                <div class="filter-tag">
                    <span><?php echo htmlspecialchars($langName); ?></span>
                    <a href="<?php echo buildFilterUrl('langue', $langId); ?>" class="filter-tag-remove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php 
            // Tags des disciplines
            foreach ($selectedDisciplines as $discId): 
                $discName = '';
                foreach ($disciplines as $disc) {
                    if ($disc['id'] == $discId) { $discName = $disc['libelle']; break; }
                }
            ?>
                <div class="filter-tag">
                    <span><?php echo htmlspecialchars($discName); ?></span>
                    <a href="<?php echo buildFilterUrl('discipline', $discId); ?>" class="filter-tag-remove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php 
            // Tags des collections
            foreach ($selectedCollections as $coll): 
            ?>
                <div class="filter-tag">
                    <span><?php echo htmlspecialchars($coll); ?></span>
                    <a href="<?php echo buildFilterUrl('collection', $coll); ?>" class="filter-tag-remove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php 
            // Tags des niveaux
            foreach ($selectedCategories as $catId): 
                $catName = '';
                foreach ($categories as $cat) {
                    if ($cat['id'] == $catId) { $catName = $cat['name']; break; }
                }
            ?>
                <div class="filter-tag">
                    <span><?php echo htmlspecialchars($catName); ?></span>
                    <a href="<?php echo buildFilterUrl('niveau', $catId); ?>" class="filter-tag-remove">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    </form>

    <script>
        // Fonctionnalité de basculement du menu déroulant
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('.filter-dropdown-btn');
            
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                // Ferme tous les autres menus déroulants
                document.querySelectorAll('.filter-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                // Bascule le menu déroulant actuel
                dropdown.classList.toggle('open');
            });
        });

        // Ferme les menus déroulants en cliquant en dehors
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('open'));
            }
        });

        // Gestion des clics sur les éléments du menu - bascule de sélection du filtre
        document.querySelectorAll('.filter-menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const filterType = item.dataset.filter;
                const filterValue = item.dataset.value;
                const isSelected = item.classList.contains('selected');
                
                // Construit l'URL avec/sans ce filtre
                const urlParams = new URLSearchParams(window.location.search);
                
                if (isSelected) {
                    // Supprime cette valeur du filtre
                    const values = urlParams.getAll(filterType + '[]').filter(v => v !== filterValue);
                    urlParams.delete(filterType + '[]');
                    values.forEach(v => urlParams.append(filterType + '[]', v));
                } else {
                    // Ajoute cette valeur au filtre
                    urlParams.append(filterType + '[]', filterValue);
                }
                
                // Navigue vers l'URL nouvelle
                window.location.href = 'donations.php' + (urlParams.toString() ? '?' + urlParams.toString() : '');
            });
        });

        // Gestion de la recherche sur la touche Enter
        const searchInput = document.querySelector('.filter-search-box input');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('filterForm').submit();
                }
            });
        }
    </script>
    
    <!--Nombre de produits disponibles-->
    <div class="ml-12 flex justify-between items-center mb-6">
            <p class="text-gray-600 text-lg">
                <span class="font-bold text-canope-teal text-2xl"><?= $totalProducts ?></span> 
                <span class="text-canope-dark">produit<?= $totalProducts > 1 ? 's' : '' ?> disponible<?= $totalProducts > 1 ? 's' : '' ?></span>
            </p>
    </div>

    <?php if (count($products) > 0): ?>
        <div class="max-w-7xl mx-auto px-8 lg:px-16 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
           <?php foreach ($products as $product): ?>
    <?php if ($product['stock'] > 0): ?>
        <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-gray-100 flex flex-col h-full">
            <div class="h-36 bg-gradient-to-br from-canope-light to-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                <?php if (!empty($product['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['image_alt'] ?? $product['name']); ?>"
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-3xl">📦</span>
                <?php endif; ?>
            </div>
            
            <div class="p-4 text-center flex flex-col flex-grow">
                <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                <span class="inline-block text-xs bg-canope-light text-canope-dark px-2 py-0.5 rounded-full mb-2">
                    <?php echo htmlspecialchars($product['category_name'] ?? 'Non classé'); ?>
                </span>
                
                <?php if (!empty($product['description'])): ?>
                    <p class="text-gray-600 text-xs mb-3 line-clamp-2"><?php echo htmlspecialchars($product['description']); ?></p>
                <?php endif; ?>
                
                <!-- Spacer to push buttons to bottom -->
                <div class="flex-grow"></div>
                
                <div class="flex justify-between items-center gap-2 pt-3 border-t border-gray-100 mt-auto">
                    <a href="details.php?id=<?php echo $product['id']; ?>" class="flex-1">
                        <button class="w-full px-3 py-1.5 border border-blue-900 text-blue-900 text-xs font-medium rounded-lg hover:bg-blue-900 hover:text-white transition-all duration-300">
                            Détails →
                        </button>
                    </a>
                    <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>')"
                        class="flex-1 px-3 py-1.5 bg-blue-900 text-white text-xs font-medium rounded-lg hover:-translate-y-0.5 active:translate-y-0 transition-transform duration-200 shadow-sm hover:shadow-md">
                        Demander
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-8 pb-4">
            <?php
            // Build query string for pagination links (preserve filters)
            $queryParams = $_GET;
            unset($queryParams['page']);
            $queryString = http_build_query($queryParams);
            $baseUrl = 'donations.php' . ($queryString ? '?' . $queryString . '&' : '?');
            ?>
            
            <!-- Previous button -->
            <?php if ($currentPage > 1): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage - 1; ?>" 
                   class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors">
                    ←
                </a>
            <?php endif; ?>
            
            <!-- Page numbers -->
            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            
            if ($startPage > 1): ?>
                <a href="<?php echo $baseUrl; ?>page=1" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors">1</a>
                <?php if ($startPage > 2): ?><span class="px-2 text-gray-400">...</span><?php endif; ?>
            <?php endif;
            
            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>" 
                   class="px-3 py-2 rounded-lg border transition-colors <?php echo $i === $currentPage ? 'bg-blue-900 text-white border-blue-900' : 'border-gray-300 text-gray-600 hover:bg-gray-100'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor;
            
            if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?><span class="px-2 text-gray-400">...</span><?php endif; ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $totalPages; ?>" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors"><?php echo $totalPages; ?></a>
            <?php endif; ?>
            
            <!-- Next button -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage + 1; ?>" 
                   class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors">
                    →
                </a>
            <?php endif; ?>
        </div>
        
        <p class="text-center text-sm text-gray-500 mb-4">
            Page <?php echo $currentPage; ?> sur <?php echo $totalPages; ?> (<?php echo $totalProducts; ?> produits)
        </p>
        <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <span class="text-6xl mb-4 block">📭</span>
            <p class="text-gray-500 text-lg">
                <?php echo !empty($searchTerm) ? "Aucun produit trouvé pour \"" . htmlspecialchars($searchTerm) . "\"." : "Aucune dotation trouvée dans cette catégorie."; ?>
            </p>
            <a href="donations.php" class="text-canope-dark hover:underline mt-2 inline-block">← Voir toutes les dotations</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
