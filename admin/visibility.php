<?php
/**
 * Admin - gére par qui la visiblité des dotations peut être modifiée
 * toggle qui montre ou cache les dotations sur le site public 
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

// gérer la visibilité (toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    
    if ($productId > 0) {
        try {
            if ($action === 'hide') {
                $stmt = $pdo->prepare('UPDATE product SET is_published = 0 WHERE id = ?');
                $stmt->execute([$productId]);
                $message = 'Dotation masquée du site public.';
            } elseif ($action === 'show') {
                $stmt = $pdo->prepare('UPDATE product SET is_published = 1 WHERE id = ?');
                $stmt->execute([$productId]);
                $message = 'Dotation visible sur le site public.';
            }
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Erreur lors de la mise à jour.';
            $messageType = 'error';
        }
    }
}

// GET le produit avec son image
$products = $pdo->query('
    SELECT p.*, c.name as category_name, pi.url as image_url
    FROM product p 
    LEFT JOIN category c ON c.id = p.category_id 
    LEFT JOIN product_image pi ON pi.product_id = p.id
    WHERE p.is_active = 1
    ORDER BY p.is_published DESC, p.name ASC
')->fetchAll(PDO::FETCH_ASSOC);

// COUNT le nombre de produits visibles et masqués
$visibleCount = count(array_filter($products, fn($p) => $p['is_published']));
$hiddenCount = count($products) - $visibleCount;

include 'includes/admin_header.php';
?>

<!-- Header de la page -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Visibilité des dotations</h2>
        <p class="text-gray-500 mt-1">Gérez ce qui est visible sur le site public</p>
    </div>
    <div class="flex gap-3">
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <?= $visibleCount ?> visible<?= $visibleCount > 1 ? 's' : '' ?>
        </span>
        <!-- nombre de produits masqués -->
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
            <?= $hiddenCount ?> masquée<?= $hiddenCount > 1 ? 's' : '' ?>
        </span>
    </div>
</div>
<!-- Message de confirmation -->
<?php if ($message): ?>
<div class="mb-6 px-4 py-3 rounded-xl flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- Grille des produits -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($products as $product): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden <?= !$product['is_published'] ? 'opacity-60' : '' ?> hover:shadow-lg transition-all">
        <!-- Image du produit -->
        <div class="relative h-40 bg-gradient-to-br from-canope-light to-gray-100 flex items-center justify-center overflow-hidden">
            <?php if (!empty($product['image_url'])): ?>
            <img src="../<?= htmlspecialchars($product['image_url']) ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>"
                 class="w-full h-full object-cover">
            <?php else: ?>
            <div class="text-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs">Pas d'image</span>
            </div>
            <?php endif; ?>
            
            <!-- Badge de visibilité -->
            <div class="absolute top-3 right-3">
                <?php if ($product['is_published']): ?>
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-500 text-white rounded-full text-xs font-medium shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Visible
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-500 text-white rounded-full text-xs font-medium shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    Masquée
                </span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- info produits -->
        <div class="p-4">
            <h3 class="font-semibold text-gray-800 truncate"><?= htmlspecialchars($product['name']) ?></h3>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($product['category_name'] ?? 'Non classé') ?></p>
            
            <!-- Bouton toggle -->
            <form method="POST" class="mt-4">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <?php if ($product['is_published']): ?>
                <input type="hidden" name="action" value="hide">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 hover:text-red-600 hover:border-red-200 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    Masquer du site
                </button>
                <?php else: ?>
                <input type="hidden" name="action" value="show">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-canope-green text-white rounded-xl hover:bg-canope-olive transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Afficher sur le site
                </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>
