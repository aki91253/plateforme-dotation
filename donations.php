<?php
require_once 'includes/db.php';

// Get selected filter values from URL
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

// Fetch filter options from database
$categoriesQuery = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

$resourceTypesQuery = $pdo->query("SELECT * FROM type_ressource ORDER BY libelle");
$resourceTypes = $resourceTypesQuery->fetchAll(PDO::FETCH_ASSOC);

$languagesQuery = $pdo->query("SELECT * FROM langue_product ORDER BY langue");
$languages = $languagesQuery->fetchAll(PDO::FETCH_ASSOC);

$disciplinesQuery = $pdo->query("SELECT * FROM discipline ORDER BY libelle");
$disciplines = $disciplinesQuery->fetchAll(PDO::FETCH_ASSOC);

// Get distinct collections from products (filtering out empty ones)
$collectionsQuery = $pdo->query("SELECT DISTINCT collection FROM product WHERE collection IS NOT NULL AND collection != '' ORDER BY collection");
$collections = $collectionsQuery->fetchAll(PDO::FETCH_COLUMN);

// Build product query with filters
$baseQuery = "SELECT p.*, c.name as category_name, rt.libelle as resource_type_name, 
              l.langue as language_name, d.libelle as discipline_name,
              pi.url as image_url, pi.alt_text as image_alt
              FROM product p 
              LEFT JOIN category c ON p.category_id = c.id 
              LEFT JOIN type_ressource rt ON p.id_ressource = rt.id
              LEFT JOIN langue_product l ON p.langue_id = l.id
              LEFT JOIN discipline d ON p.discipline_id = d.id
              LEFT JOIN product_image pi ON p.id = pi.product_id
              WHERE p.is_active = 1 AND p.is_published = 1";

$params = [];

// Apply category/niveau filter
if (!empty($selectedCategories)) {
    $placeholders = [];
    foreach ($selectedCategories as $i => $catId) {
        $placeholders[] = ":cat$i";
        $params["cat$i"] = $catId;
    }
    $baseQuery .= " AND p.category_id IN (" . implode(',', $placeholders) . ")";
}

// Apply resource type filter
if (!empty($selectedResourceTypes)) {
    $placeholders = [];
    foreach ($selectedResourceTypes as $i => $rtId) {
        $placeholders[] = ":rt$i";
        $params["rt$i"] = $rtId;
    }
    $baseQuery .= " AND p.id_ressource IN (" . implode(',', $placeholders) . ")";
}

// Apply language filter
if (!empty($selectedLanguages)) {
    $placeholders = [];
    foreach ($selectedLanguages as $i => $langId) {
        $placeholders[] = ":lang$i";
        $params["lang$i"] = $langId;
    }
    $baseQuery .= " AND p.langue_id IN (" . implode(',', $placeholders) . ")";
}

// Apply discipline filter
if (!empty($selectedDisciplines)) {
    $placeholders = [];
    foreach ($selectedDisciplines as $i => $discId) {
        $placeholders[] = ":disc$i";
        $params["disc$i"] = $discId;
    }
    $baseQuery .= " AND p.discipline_id IN (" . implode(',', $placeholders) . ")";
}

// Apply collection filter
if (!empty($selectedCollections)) {
    $placeholders = [];
    foreach ($selectedCollections as $i => $coll) {
        $placeholders[] = ":coll$i";
        $params["coll$i"] = $coll;
    }
    $baseQuery .= " AND p.collection IN (" . implode(',', $placeholders) . ")";
}

// Apply search filter
if (!empty($searchTerm)) {
    $baseQuery .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.reference LIKE :search)";
    $params['search'] = '%' . $searchTerm . '%';
}

$baseQuery .= " ORDER BY p.name";

$productsQuery = $pdo->prepare($baseQuery);
$productsQuery->execute($params);
$products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);

// Check if any filters are active
$hasActiveFilters = !empty($selectedCategories) || !empty($selectedResourceTypes) || 
                    !empty($selectedLanguages) || !empty($selectedDisciplines) || !empty($selectedCollections) || !empty($searchTerm);

// Helper function to build URL without a specific filter value
function buildFilterUrl($filterType, $valueToRemove) {
    global $selectedResourceTypes, $selectedLanguages, $selectedDisciplines, $selectedCollections, $selectedCategories, $searchTerm;
    
    $params = [];
    
    // Add resource_type params
    foreach ($selectedResourceTypes as $val) {
        if ($filterType !== 'resource_type' || $val != $valueToRemove) {
            $params[] = 'resource_type[]=' . urlencode($val);
        }
    }
    
    // Add langue params
    foreach ($selectedLanguages as $val) {
        if ($filterType !== 'langue' || $val != $valueToRemove) {
            $params[] = 'langue[]=' . urlencode($val);
        }
    }
    
    // Add discipline params
    foreach ($selectedDisciplines as $val) {
        if ($filterType !== 'discipline' || $val != $valueToRemove) {
            $params[] = 'discipline[]=' . urlencode($val);
        }
    }
    
    // Add collection params
    foreach ($selectedCollections as $val) {
        if ($filterType !== 'collection' || $val != $valueToRemove) {
            $params[] = 'collection[]=' . urlencode($val);
        }
    }
    
    // Add niveau params
    foreach ($selectedCategories as $val) {
        if ($filterType !== 'niveau' || $val != $valueToRemove) {
            $params[] = 'niveau[]=' . urlencode($val);
        }
    }
    
    // Add search param
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
        .filter-dropdown {
            position: relative;
            display: inline-block;
        }
        .filter-dropdown-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 16px;
            min-width: 160px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #6b7280;
            transition: all 0.2s ease;
        }
        .filter-dropdown-btn:hover {
            border-color: #9ca3af;
        }
        .filter-dropdown-btn.active {
            border-color: #3B556D;
            color: #3B556D;
        }
        .filter-dropdown-btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }
        .filter-dropdown.open .filter-dropdown-btn svg {
            transform: rotate(180deg);
        }
        .filter-dropdown-menu {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            min-width: 200px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
            display: none;
            padding: 8px 0;
        }
        .filter-dropdown.open .filter-dropdown-menu {
            display: block;
        }
        .filter-menu-item {
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            transition: background 0.15s ease;
        }
        .filter-menu-item:hover {
            background: #f3f4f6;
        }
        .filter-menu-item.selected {
            background: #e0f2f1;
            color: #3B556D;
            font-weight: 500;
        }
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #e0f2f1;
            color: #3B556D;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .filter-tag-remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(59, 85, 109, 0.2);
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .filter-tag-remove:hover {
            background: rgba(59, 85, 109, 0.4);
        }
        .filter-tag-remove svg {
            width: 10px;
            height: 10px;
        }
        .filter-search-box {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            gap: 8px;
            flex: 1;
            max-width: 300px;
        }
        .filter-search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
            color: #374151;
        }
        .filter-search-box input::placeholder {
            color: #9ca3af;
        }
        .filter-search-box svg {
            width: 18px;
            height: 18px;
            color: #9ca3af;
        }
    </style>

    <form id="filterForm" method="GET" action="donations.php">
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <!-- Search Box -->
            <div class="filter-search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Recherche" value="<?php echo htmlspecialchars($searchTerm); ?>">
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

            <!-- Clear All Filters -->
            <?php if ($hasActiveFilters): ?>
                <a href="donations.php" class="text-sm text-gray-500 hover:text-red-500 transition-colors ml-2">
                    Effacer tout
                </a>
            <?php endif; ?>
        </div>

        <!-- Selected Filter Tags -->
        <?php if ($hasActiveFilters): ?>
        <div class="filter-tags">
            <?php 
            // Resource Type tags
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
            // Language tags
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
            // Discipline tags
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
            // Collection tags
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
            // Niveau tags
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
    </form>

    <script>
        // Dropdown toggle functionality
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('.filter-dropdown-btn');
            
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                // Close all other dropdowns
                document.querySelectorAll('.filter-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                // Toggle current dropdown
                dropdown.classList.toggle('open');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('open'));
            }
        });

        // Handle menu item clicks - toggle filter selection
        document.querySelectorAll('.filter-menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const filterType = item.dataset.filter;
                const filterValue = item.dataset.value;
                const isSelected = item.classList.contains('selected');
                
                // Build URL with/without this filter
                const urlParams = new URLSearchParams(window.location.search);
                
                if (isSelected) {
                    // Remove this value from the filter
                    const values = urlParams.getAll(filterType + '[]').filter(v => v !== filterValue);
                    urlParams.delete(filterType + '[]');
                    values.forEach(v => urlParams.append(filterType + '[]', v));
                } else {
                    // Add this value to the filter
                    urlParams.append(filterType + '[]', filterValue);
                }
                
                // Navigate to the new URL
                window.location.href = 'donations.php' + (urlParams.toString() ? '?' + urlParams.toString() : '');
            });
        });

        // Handle search on Enter key
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


    <?php if (count($products) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="h-48 bg-gradient-to-br from-canope-light to-gray-100 flex items-center justify-center overflow-hidden">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['image_alt'] ?? $product['name']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-4xl">📦</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-900 text-lg"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <span class="text-xs bg-canope-light text-canope-dark px-2 py-1 rounded-full">
                                <?php echo htmlspecialchars($product['category_name'] ?? 'Non classé'); ?>
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3">Réf: <?php echo htmlspecialchars($product['reference']); ?></p>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <span class="text-canope-dark font-bold">
                                <!-- From Uiverse.io by M4rio1 --> 
                                 <a href="details.php?id=<?php echo $product['id']; ?>">
                                <button
            
                                    class="relative bg-[#3B556D] text-white font-medium text-[12px] px-8 py-[0.35em] pl-5 h-[2.8em] rounded-[0.9em] flex items-center overflow-hidden cursor-pointer shadow-[inset_0_0_1.6em_-0.6em_#0B162C] group"
                                >
                                    <span class="mr-10">Voir détails</span>
                                        <div
                                            class="absolute right-[0.3em] bg-white h-[2.2em] w-[2.2em] rounded-[0.7em] flex items-center justify-center transition-all duration-300 group-hover:w-[calc(100%-0.6em)] shadow-[0.1em_0.1em_0.6em_0.2em_#0B162C] active:scale-95"
                                        >
                                            <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            width="24"
                                            height="24"
                                            class="w-[1.1em] transition-transform duration-300 text-[#7b52b9] group-hover:translate-x-[0.1em]"
                                        >
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                <path
                                    fill="currentColor"
                                    d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                                    ></path>
                                </svg>
                            </div>
                      
                        </button>
                        </a>

                            </span>
                            <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>')"
                                    class="add-to-cart-btn group cursor-pointer outline-none hover:rotate-90 duration-300"
                                    title="Ajouter à ma sélection">
                                <svg class="stroke-canope-gray fill-none group-hover:fill-canope-light group-hover:stroke-canope-teal group-active:stroke-white group-active:fill-canope-green group-active:duration-0 duration-300"
                                     viewBox="0 0 24 24" height="40px" width="40px" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-width="1.5" d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"></path>
                                    <path stroke-width="1.5" d="M8 12H16"></path>
                                    <path stroke-width="1.5" d="M12 16V8"></path>
                                </svg>
                            </button>      
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
