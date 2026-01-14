<?php
require_once 'includes/db.php';
//categories
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
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Voir toutes les dotations</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
