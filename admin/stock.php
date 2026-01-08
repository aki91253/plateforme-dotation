<?php
/**
 * Admin - gérer les stocks
 * mise à jour rapide des stocks avec des indicateurs visuels
 */
require_once 'includes/admin_auth.php';
requireAdmin();

// connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=canope-reseau;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données');
}

$message = '';
$messageType = '';

// gére les mise a jours des stocks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $action = $_POST['action'] ?? 'set';
    
    if ($productId > 0) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare('UPDATE stock SET quantity = quantity + ? WHERE product_id = ?');
                $stmt->execute([$quantity, $productId]);
                $message = "Stock augmenté de $quantity unités.";
            } elseif ($action === 'subtract') {
                $stmt = $pdo->prepare('UPDATE stock SET quantity = GREATEST(0, quantity - ?) WHERE product_id = ?');
                $stmt->execute([$quantity, $productId]);
                $message = "Stock diminué de $quantity unités.";
            } else {
                $stmt = $pdo->prepare('UPDATE stock SET quantity = ? WHERE product_id = ?');
                $stmt->execute([max(0, $quantity), $productId]);
                $message = "Stock mis à jour.";
            }
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Erreur lors de la mise à jour du stock.';
            $messageType = 'error';
        }
    }
}

// Get stock qvec info produits
$stockItems = $pdo->query('
    SELECT p.id, p.name, p.reference, c.name as category_name, COALESCE(s.quantity, 0) as quantity
    FROM product p 
    LEFT JOIN category c ON c.id = p.category_id 
    LEFT JOIN stock s ON s.product_id = p.id
    WHERE p.is_active = 1
    ORDER BY s.quantity ASC, p.name ASC
')->fetchAll(PDO::FETCH_ASSOC);

// calcul des stats sur les produits
$totalStock = array_sum(array_column($stockItems, 'quantity'));
$lowStockCount = count(array_filter($stockItems, fn($item) => $item['quantity'] > 0 && $item['quantity'] < 20));
$outOfStockCount = count(array_filter($stockItems, fn($item) => $item['quantity'] == 0));

include 'includes/admin_header.php';
?>

<!-- Header de la page -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gestion du stock</h2>
        <p class="text-gray-500 mt-1">Mettez à jour les quantités disponibles</p>
    </div>
</div>
<!-- message de confirmation -->
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

<!-- cartes des stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-canope-green/10 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($totalStock) ?></p>
            <p class="text-sm text-gray-500">Articles en stock</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $lowStockCount ?></p>
            <p class="text-sm text-gray-500">Stock faible (&lt;20)</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $outOfStockCount ?></p>
            <p class="text-sm text-gray-500">Rupture de stock</p>
        </div>
    </div>
</div>

<!-- Tableau des stocks -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Référence</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock actuel</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ajustement rapide</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Définir</th>
                </tr>
            </thead>
            <!-- corps du tableau -->
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($stockItems as $item): ?>
                <?php
                $qty = (int)$item['quantity'];
                $stockClass = $qty == 0 ? 'bg-red-100 text-red-700' : ($qty < 20 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                $rowClass = $qty == 0 ? 'bg-red-50/50' : ($qty < 20 ? 'bg-amber-50/30' : '');
                ?>
                <tr class="hover:bg-gray-50 transition-colors <?= $rowClass ?>">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if ($qty == 0): ?>
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <?php elseif ($qty < 20): ?>
                            <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                            <?php else: ?>
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <?php endif; ?>
                            <span class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">
                            <?= htmlspecialchars($item['reference'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600"><?= htmlspecialchars($item['category_name'] ?? 'Non classé') ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold <?= $stockClass ?>">
                            <?= $qty ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <form method="POST" class="flex items-center gap-1">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="action" value="subtract">
                                <input type="hidden" name="quantity" value="10">
                                <button type="submit" class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors flex items-center justify-center" title="-10">
                                    <span class="text-sm font-bold">-10</span>
                                </button>
                            </form>
                            <form method="POST" class="flex items-center gap-1">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="action" value="subtract">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors flex items-center justify-center" title="-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                    </svg>
                                </button>
                            </form>
                            <form method="POST" class="flex items-center gap-1">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors flex items-center justify-center" title="+1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </form>
                            <form method="POST" class="flex items-center gap-1">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="quantity" value="10">
                                <button type="submit" class="w-8 h-8 bg-emerald-100 hover:bg-emerald-200 text-emerald-600 rounded-lg transition-colors flex items-center justify-center" title="+10">
                                    <span class="text-sm font-bold">+10</span>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" class="flex items-center justify-end gap-2">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="action" value="set">
                            <input type="number" name="quantity" value="<?= $qty ?>" min="0" 
                                   class="w-20 border border-gray-300 rounded-lg px-3 py-1.5 text-center text-sm focus:ring-2 focus:ring-canope-green focus:border-transparent">
                            <button type="submit" class="p-2 bg-canope-green hover:bg-canope-olive text-white rounded-lg transition-colors" title="Définir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
