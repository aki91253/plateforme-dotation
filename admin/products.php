<?php
/**
 * Admin - Dotations/Products Management
 * CRUD operations for products
 */
require_once 'includes/admin_auth.php';
requireAdmin();

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=canope-reseau;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données');
}

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $reference = trim($_POST['reference'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        
        if (!empty($name)) {
            try {
                $stmt = $pdo->prepare('INSERT INTO product (name, reference, description, category_id, list_price, is_published, is_active) VALUES (?, ?, ?, ?, 0.00, 1, 1)');
                $stmt->execute([$name, $reference, $description, $categoryId ?: null]);
                $productId = $pdo->lastInsertId();
                
                // Add initial stock entry
                $stmtStock = $pdo->prepare('INSERT INTO stock (product_id, quantity) VALUES (?, 0)');
                $stmtStock->execute([$productId]);
                
                // Handle image upload
                if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/img/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($ext, $allowedExts)) {
                        $filename = 'product-' . $productId . '-' . time() . '.' . $ext;
                        $filepath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                            $imageUrl = 'assets/img/' . $filename;
                            $stmtImg = $pdo->prepare('INSERT INTO product_image (product_id, url, alt_text) VALUES (?, ?, ?)');
                            $stmtImg->execute([$productId, $imageUrl, $name]);
                        }
                    }
                }
                
                $message = 'Dotation ajoutée avec succès.';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Erreur lors de l\'ajout: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $reference = trim($_POST['reference'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id > 0 && !empty($name)) {
            try {
                $stmt = $pdo->prepare('UPDATE product SET name = ?, reference = ?, description = ?, category_id = ?, is_active = ? WHERE id = ?');
                $stmt->execute([$name, $reference, $description, $categoryId ?: null, $isActive, $id]);
                $message = 'Dotation mise à jour avec succès.';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Erreur lors de la mise à jour.';
                $messageType = 'error';
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare('UPDATE product SET is_active = 0 WHERE id = ?');
                $stmt->execute([$id]);
                $message = 'Dotation désactivée avec succès.';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Erreur lors de la suppression.';
                $messageType = 'error';
            }
        }
    }
}

// Get categories
$categories = $pdo->query('SELECT * FROM category ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

// Get products with category and stock info
$products = $pdo->query('
    SELECT p.*, c.name as category_name, COALESCE(s.quantity, 0) as stock_quantity
    FROM product p 
    LEFT JOIN category c ON c.id = p.category_id 
    LEFT JOIN stock s ON s.product_id = p.id
    ORDER BY p.is_active DESC, p.name ASC
')->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gestion des dotations</h2>
        <p class="text-gray-500 mt-1">Gérez les produits disponibles pour la dotation</p>
    </div>
    <button onclick="openAddModal()" 
            class="inline-flex items-center gap-2 bg-canope-green hover:bg-canope-olive text-white font-medium px-5 py-2.5 rounded-xl transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Ajouter une dotation
    </button>
</div>

<?php if ($message): ?>
<div class="mb-6 px-4 py-3 rounded-xl flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?php if ($messageType === 'success'): ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <?php else: ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <?php endif; ?>
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- Products Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Référence</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-gray-50 transition-colors <?= !$product['is_active'] ? 'opacity-50' : '' ?>">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-canope-green/10 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($product['name']) ?></p>
                                <p class="text-xs text-gray-500 max-w-xs truncate"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">
                            <?= htmlspecialchars($product['reference'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600"><?= htmlspecialchars($product['category_name'] ?? 'Non classé') ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $stockQty = (int)$product['stock_quantity'];
                        $stockClass = $stockQty == 0 ? 'bg-red-100 text-red-700' : ($stockQty < 20 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $stockClass ?>">
                            <?= $stockQty ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($product['is_active']): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            Actif
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Inactif
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($product)) ?>)" 
                                    class="p-2 text-gray-500 hover:text-canope-green hover:bg-canope-green/10 rounded-lg transition-colors"
                                    title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <?php if ($product['is_active']): ?>
                            <button onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>')" 
                                    class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Désactiver">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Nouvelle dotation</h3>
            <button onclick="closeAddModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <input type="hidden" name="action" value="add">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                <input type="text" name="reference" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image du produit</label>
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-canope-green/10 file:text-canope-green hover:file:bg-canope-green/20">
                <p class="text-xs text-gray-500 mt-1">Formats: JPG, PNG, GIF, WebP</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-canope-green text-white rounded-lg hover:bg-canope-olive transition-colors">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Modifier la dotation</h3>
            <button onclick="closeEditModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                <input type="text" name="reference" id="edit_reference" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" id="edit_category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent"></textarea>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="edit_is_active" class="w-4 h-4 text-canope-green focus:ring-canope-green border-gray-300 rounded">
                <label for="edit_is_active" class="text-sm font-medium text-gray-700">Produit actif</label>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-canope-green text-white rounded-lg hover:bg-canope-olive transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Désactiver cette dotation ?</h3>
            <p class="text-gray-500 text-sm mb-6" id="deleteProductName"></p>
            <form method="POST" class="flex gap-3">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete_id">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Désactiver
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addModal').classList.remove('flex');
    }
    
    function openEditModal(product) {
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_reference').value = product.reference || '';
        document.getElementById('edit_description').value = product.description || '';
        document.getElementById('edit_category_id').value = product.category_id || '';
        document.getElementById('edit_is_active').checked = product.is_active == 1;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
    
    function confirmDelete(id, name) {
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
    
    // Close modals on outside click
    ['addModal', 'editModal', 'deleteModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });
    });
</script>

<?php include 'includes/admin_footer.php'; ?>
