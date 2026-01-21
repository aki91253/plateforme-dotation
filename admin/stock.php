<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

// Vérifier que l'utilisateur est admin
requireAdmin();

// Message de confirmation après suppression
$successMessage = '';
$errorMessage = '';

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $successMessage = 'Dotation supprimée avec succès !';
}

if (isset($_GET['error'])) {
    $errorMessage = 'Erreur lors de la suppression : ' . htmlspecialchars($_GET['error']);
}

if (isset($_GET['created']) && $_GET['created'] == 1) {
    $successMessage = 'Dotation créée avec succès !';
}

// Récupérer les statistiques
$statsQuery = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as actives,
        SUM(CASE WHEN stock < 20 THEN 1 ELSE 0 END) as stock_faible,
        SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as en_rupture
    FROM product
");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

// Récupérer les filtres
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$showInactive = isset($_GET['show_inactive']) ? true : false;

// Construire la requête
$query = "SELECT p.*, c.name as category_name, pi.url as image_url
          FROM product p 
          LEFT JOIN category c ON p.category_id = c.id
          LEFT JOIN product_image pi ON p.id = pi.product_id
          WHERE 1=1";

$params = [];

if (!$showInactive) {
    $query .= " AND p.is_active = 1";
}

if (!empty($searchTerm)) {
    $query .= " AND (p.name LIKE :search OR p.reference LIKE :search)";
    $params['search'] = '%' . $searchTerm . '%';
}

if ($categoryFilter > 0) {
    $query .= " AND p.category_id = :category";
    $params['category'] = $categoryFilter;
}

$query .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le filtre
$categoriesQuery = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 p-6 rounded-lg">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button onclick="window.history.back()" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des dotations</h1>
                    <p class="text-gray-500 text-sm">Canopé Corse</p>
                </div>
            </div>
            <a href="dotation_create.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle dotation
            </a>
        </div>

        <!-- Messages de succès/erreur -->
        <?php if ($successMessage): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                <?= $successMessage ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['total'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Actives</p>
                        <p class="text-3xl font-bold text-green-600"><?= $stats['actives'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Stock faible</p>
                        <p class="text-3xl font-bold text-yellow-600"><?= $stats['stock_faible'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">En rupture</p>
                        <p class="text-3xl font-bold text-red-600"><?= $stats['en_rupture'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" 
                               placeholder="Rechercher une dotation..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <select name="category" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="0">Toutes catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="show_inactive" value="1" <?= $showInactive ? 'checked' : '' ?> 
                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Afficher inactives</span>
                </label>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Tableau des dotations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Nom</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Catégorie</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Lieu</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Stock</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Statut</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Actif</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Aucune dotation trouvée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            <?php if (!empty($product['image_url'])): ?>
                                                <img src="../<?= htmlspecialchars($product['image_url']) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                                     class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    📦
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></p>
                                            <p class="text-xs text-gray-500 line-clamp-1"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full whitespace-nowrap">
                                        <?= htmlspecialchars($product['category_name'] ?? 'Non classé') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($product['location'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900"><?= $product['stock'] ?>/<?= $product['quantite_totale'] ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($product['stock'] == 0): ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Rupture
                                        </span>
                                    <?php elseif ($product['stock'] > 0 && $product['stock'] < 20): ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                            Stock faible
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                            En stock
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" <?= $product['is_active'] ? 'checked' : '' ?> 
                                               onchange="toggleActive(<?= $product['id'] ?>, this.checked)"
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="dotation_edit.php?id=<?= $product['id'] ?>" 
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 transition-colors"
                                           title="Éditer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete(<?= $product['id'] ?>, '<?= addslashes(htmlspecialchars($product['name'])) ?>')"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 transition-colors"
                                                title="Supprimer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Confirmer la suppression</h3>
        <p class="text-gray-600 text-center mb-6">
            Êtes-vous vraiment sûr de vouloir supprimer la dotation<br>
            "<span id="deleteProductName" class="font-semibold"></span>" ?
        </p>
        <p class="text-sm text-red-600 text-center mb-6">Cette action est irréversible.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Annuler
            </button>
            <button onclick="deleteProduct()" 
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                Supprimer
            </button>
        </div>
    </div>
</div>

<script>
let productToDelete = null;

function confirmDelete(id, name) {
    productToDelete = id;
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    productToDelete = null;
}

function deleteProduct() {
    if (productToDelete) {
        window.location.href = `dotation_delete.php?id=${productToDelete}`;
    }
}

function toggleActive(id, isActive) {
    fetch('dotation_toggle_active.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id, is_active: isActive })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Erreur lors de la modification');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification');
        location.reload();
    });
}

// Fermer modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<?php include 'includes/admin_footer.php'; ?>