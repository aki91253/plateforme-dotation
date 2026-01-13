<?php
require_once 'includes/db.php';

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

$categoriesQuery = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

$baseQuery = "SELECT p.*, c.name as category_name, pi.url as image_url, pi.alt_text as image_alt
              FROM product p 
              LEFT JOIN category c ON p.category_id = c.id 
              LEFT JOIN product_image pi ON p.id = pi.product_id
              WHERE p.is_active = 1 AND p.is_published = 1";

$params = [];

if ($selectedCategory > 0) {
    $baseQuery .= " AND p.category_id = :category";
    $params['category'] = $selectedCategory;
}

if (!empty($searchTerm)) {
    $baseQuery .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.reference LIKE :search)";
    $params['search'] = '%' . $searchTerm . '%';
}

$baseQuery .= " ORDER BY p.name";

$productsQuery = $pdo->prepare($baseQuery);
$productsQuery->execute($params);
$products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="max-w-6xl mx-auto px-5 py-8">
    <div class="flex items-center justify-center w-fit h-fit [--book-color:#f1775b] [--book-cover-color:#506c86]">
        <div class="relative flex justify-end items-start w-[100px] h-[8px] bg-[var(--book-color)] border-b-2 border-[var(--book-cover-color)]">
            <div class="w-1/2 h-[1px] bg-[var(--book-color)] origin-left animate-page1"></div>
            <div class="absolute w-1/2 h-[1px] bg-[var(--book-color)] origin-left animate-page2"></div>
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

    <h1 class="text-4xl font-normal mb-8 text-gray-900">Liste des dotations</h1>
    
    <?php if (!empty($searchTerm)): ?>
        <div class="mb-4 text-sm text-gray-600">
            Résultats pour: <strong><?php echo htmlspecialchars($searchTerm); ?></strong>
            <a href="donations.php<?php echo $selectedCategory > 0 ? '?category=' . $selectedCategory : ''; ?>" class="ml-2 text-canope-green hover:underline">Effacer la recherche</a>
        </div>
    <?php endif; ?>
    
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="donations.php<?php echo !empty($searchTerm) ? '?search=' . urlencode($searchTerm) : ''; ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-all <?php echo $selectedCategory == 0 ? 'bg-canope-green text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            Tout
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="donations.php?category=<?php echo $category['id']; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-all <?php echo $selectedCategory == $category['id'] ? 'bg-canope-green text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
        <?php endforeach; ?>
        
        <form method="GET" action="donations.php" class="form relative">
            <button type="submit" class="absolute left-2 -translate-y-1/2 top-1/2 p-1">
                <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search" class="w-5 h-5 text-gray-700">
                    <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <input 
                name="search" 
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                class="input rounded-full px-8 py-1 border-2 border-transparent focus:outline-none focus:border-blue-500 placeholder-gray-400 transition-all duration-300 shadow-md w-48 placeholder:text-[11px]" 
                placeholder="Rechercher un produit..." 
                type="text" 
            />
            <?php if (!empty($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?php echo (int)$_GET['category']; ?>" />
            <?php endif; ?>
            <?php if (!empty($_GET['search'])): ?>
                <a href="donations.php<?php echo !empty($_GET['category']) ? '?category=' . (int)$_GET['category'] : ''; ?>" class="absolute right-3 -translate-y-1/2 top-1/2 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            <?php endif; ?>
        </form>
    </div>

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
                            <span class="text-xs bg-canope-light text-canope-green px-2 py-1 rounded-full">
                                <?php echo htmlspecialchars($product['category_name'] ?? 'Non classé'); ?>
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3">Réf: <?php echo htmlspecialchars($product['reference']); ?></p>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <span class="text-canope-green font-bold">
                                <!-- From Uiverse.io by M4rio1 --> 
                                 <a href="details.php">
                                <button
            
                                    class="relative bg-[#476B59] text-white font-medium text-[12px] px-8 py-[0.35em] pl-5 h-[2.8em] rounded-[0.9em] flex items-center overflow-hidden cursor-pointer shadow-[inset_0_0_1.6em_-0.6em_#0b3800] group"
                                >
                                    <span class="mr-10">Voir détails</span>
                                        <div
                                            class="absolute right-[0.3em] bg-white h-[2.2em] w-[2.2em] rounded-[0.7em] flex items-center justify-center transition-all duration-300 group-hover:w-[calc(100%-0.6em)] shadow-[0.1em_0.1em_0.6em_0.2em_#135f01] active:scale-95"
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
                            <button
                                onclick="this.classList.toggle('active');
                                        addToFav(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>');"
                                type="button"
                                class="group transition-transform duration-200 active:scale-125"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="w-8 h-8 fill-gray-300 transition-all duration-300 group-[.active]:fill-red-500 group-[.active]:scale-125"
                                >
                                    <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5 -1.935 0-3.597 1.126-4.312 2.733 -.715-1.607-2.377-2.733-4.313-2.733 C5.1 3.75 3 5.765 3 8.25 c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
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
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Voir toutes les dotations</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
