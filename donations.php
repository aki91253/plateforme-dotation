<?php
require_once 'includes/db.php'; // inclut le fichier db.php qui contient la connexion à la base de données via PDO.

// Get selected category from URL parameter
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0; //On récupère l’ID de la catégorie sélectionnée depuis l’URL (?category=3).

// Fetch all categories
$categoriesQuery = $pdo->query("SELECT * FROM category ORDER BY name"); // exécute une requête SQL simple pour récupérer toutes les catégories. 
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);// Transforme le résultat en tableau associatif PHP : chaque catégorie devient un tableau avec id et name.

// Fetch products (filtered by category if selected)
if ($selectedCategory > 0) { // Condition qui vérifie si il y a une catégorie précise ( supérieur à 0 = id d'une catégorie précise / 0 = toutes les categories )
    $productsQuery = $pdo->prepare("SELECT p.*, c.name as category_name, pi.url as image_url, pi.alt_text as image_alt
                                     FROM product p 
                                     LEFT JOIN category c ON p.category_id = c.id 
                                     LEFT JOIN product_image pi ON p.id = pi.product_id
                                     WHERE p.is_active = 1 AND p.category_id = :category
                                     ORDER BY p.name");
    $productsQuery->execute(['category' => $selectedCategory]); // Execute la requête / C'est un tableau associatif / Le prepare + Execute evite les injections SQL. 
} else {
    $productsQuery = $pdo->query("SELECT p.*, c.name as category_name, pi.url as image_url, pi.alt_text as image_alt
                                   FROM product p 
                                   LEFT JOIN category c ON p.category_id = c.id 
                                   LEFT JOIN product_image pi ON p.id = pi.product_id
                                   WHERE p.is_active = 1
                                   ORDER BY p.name");
}
$products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="max-w-6xl mx-auto px-5 py-8">
    <!-- Loader compact en Tailwind -->
<div class="flex items-center justify-center w-fit h-fit [--book-color:#f1775b] [--book-cover-color:#506c86]">
  <div class="relative flex justify-end items-start w-[100px] h-[8px] bg-[var(--book-color)] border-b-2 border-[var(--book-cover-color)]">
    <!-- Page 1 -->
    <div class="w-1/2 h-[1px] bg-[var(--book-color)] origin-left animate-page1"></div>
    <!-- Page 2 -->
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
    
    
    <!-- Category Filter -->
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="donations.php" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-all <?php echo $selectedCategory == 0 ? 'bg-canope-green text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            Tout
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="donations.php?category=<?php echo $category['id']; ?>" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-all <?php echo $selectedCategory == $category['id'] ? 'bg-canope-green text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Products Grid -->
    <?php if (count($products) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <!-- Product Image -->
                    <div class="h-48 bg-gradient-to-br from-canope-light to-gray-100 flex items-center justify-center overflow-hidden">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['image_alt'] ?? $product['name']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-4xl">📦</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Info -->
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
                                <?php echo $product['list_price'] > 0 ? number_format($product['list_price'], 2, ',', ' ') . ' €' : 'Gratuit'; ?>
                            </span>
                            <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>')"
                                    class="add-to-cart-btn group cursor-pointer outline-none hover:rotate-90 duration-300"
                                    title="Ajouter à ma sélection">
                                <svg class="stroke-canope-green fill-none group-hover:fill-canope-light group-hover:stroke-[#2d5443] group-active:stroke-white group-active:fill-canope-green group-active:duration-0 duration-300"
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
            <p class="text-gray-500 text-lg">Aucune dotation trouvée dans cette catégorie.</p>
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Voir toutes les dotations</a>
        </div>
    <?php endif; ?>
</div>

<!-- Bouton Scroll To Top - Only on this page -->
<button 
  id="scrollToTopBtn"
  class="fixed right-4 bottom-4 z-50 w-14 h-14 rounded-full bg-canope-green text-white flex items-center justify-center shadow-lg border-2 border-canope-green hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] hover:border-[#4a8a70] active:scale-90 transition-all duration-300"
  title="Remonter en haut">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
  </svg>
</button>

<script>
  document.getElementById('scrollToTopBtn').addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>

<?php include 'includes/footer.php'; ?>
