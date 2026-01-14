<?php
require_once 'includes/db.php';

$product_id = (int) $_GET['id'];

//requête qui récupère toutes les informations d'un produit en fonction de l'id 
$stmt = $pdo->prepare('
    SELECT p.*, c.name as category_name, s.quantity as stock_quantity, l.langue as langue, d.libelle as discipline, t.libelle as ressource
    FROM product p
    LEFT JOIN category c ON p.category_id = c.id
    LEFT JOIN stock s ON p.id = s.product_id
    LEFT JOIN langue_product l ON p.langue_id = l.id
    LEFT JOIN discipline d ON p.discipline_id = d.id 
    LEFT JOIN type_ressource t ON p.id_ressource = t.id
    WHERE p.id = ?
');
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {//dans le cas ou un produit n'est pas trouvé on renvoie une erreur 
    die('Produit non trouvé');
}

$img_stmt = $pdo->prepare('SELECT url, alt_text FROM product_image WHERE product_id = ? LIMIT 1'); // Permet de récupérer l'image du produit (en fonction de l'id)
$img_stmt->execute([$product_id]);
$image = $img_stmt->fetch();

$total_stock = 100; // Total de stock pour la barre de stock 
$available = $product['stock_quantity'] ?? 0;
$percentage = ($available / $total_stock) * 100;


include 'includes/header.php';
?>

<!-- section header -->

<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg stroke="#FFFFFF" class="w-16 h-16 mx-auto ml-1.5 -mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"">
							<path fill="none" d="M16.588,3.411h-4.466c0.042-0.116,0.074-0.236,0.074-0.366c0-0.606-0.492-1.098-1.099-1.098H8.901c-0.607,0-1.098,0.492-1.098,1.098c0,0.13,0.033,0.25,0.074,0.366H3.41c-0.606,0-1.098,0.492-1.098,1.098c0,0.607,0.492,1.098,1.098,1.098h0.366V16.59c0,0.808,0.655,1.464,1.464,1.464h9.517c0.809,0,1.466-0.656,1.466-1.464V5.607h0.364c0.607,0,1.1-0.491,1.1-1.098C17.688,3.903,17.195,3.411,16.588,3.411z M8.901,2.679h2.196c0.202,0,0.366,0.164,0.366,0.366S11.3,3.411,11.098,3.411H8.901c-0.203,0-0.366-0.164-0.366-0.366S8.699,2.679,8.901,2.679z M15.491,16.59c0,0.405-0.329,0.731-0.733,0.731H5.241c-0.404,0-0.732-0.326-0.732-0.731V5.607h10.983V16.59z M16.588,4.875H3.41c-0.203,0-0.366-0.164-0.366-0.366S3.208,4.143,3.41,4.143h13.178c0.202,0,0.367,0.164,0.367,0.366S16.79,4.875,16.588,4.875zM6.705,14.027h6.589c0.202,0,0.366-0.164,0.366-0.366s-0.164-0.367-0.366-0.367H6.705c-0.203,0-0.366,0.165-0.366,0.367S6.502,14.027,6.705,14.027z M6.705,11.83h6.589c0.202,0,0.366-0.164,0.366-0.365c0-0.203-0.164-0.367-0.366-0.367H6.705c-0.203,0-0.366,0.164-0.366,0.367C6.339,11.666,6.502,11.83,6.705,11.83z M6.705,9.634h6.589c0.202,0,0.366-0.164,0.366-0.366c0-0.202-0.164-0.366-0.366-0.366H6.705c-0.203,0-0.366,0.164-0.366,0.366C6.339,9.47,6.502,9.634,6.705,9.634z"></path>
						</svg>
            </div>
            <h1 class="text-3xl font-semibold text-white">Détails</h1>
        </div>
        <p class="text-white/80 text-sm ml-13">Voici les détails du produit</p>
    </div>
</div>
    <!-- Petit header avec en dessous de "Détails" pour le bouton retour  -->
     <div class="bg-gradient-to-r from-canope-gray to-canope-teal py-1 px-5">
     <a href="./donations.php">
        <button class="cursor-pointer duration-200 hover:scale-125 active:scale-100" title="Go Back">
            <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24" class="stroke-white">
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" d="M11 6L5 12M5 12L11 18M5 12H19"></path>
            </svg>
        </button>
        </a>
        </div>
    </div>
</div>

<!--Contenu principal -->
 <main class="max-w-7xl mx-auto px-2 py-8">
      <div class="grid grid-cols-2 gap-8">
        <!-- Left: Image -->
        <div>
          <div class="relative rounded-2xl overflow-hidden bg-gray-900 aspect-square">
            <?php if ($image): ?>
              <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="<?php echo htmlspecialchars($image['alt_text'] ?? $product['name']); ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center text-gray-400">
                <span>Image non disponible</span>
              </div>
            <?php endif; ?>
            <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm flex items-center gap-2">
              <span class="w-2 h-2 bg-white rounded-full"></span>
              <?php echo $available > 0 ? 'Disponible' : 'Rupture de stock'; ?>
            </div>
          </div>
        </div>

        <!-- Right: Details -->
        <div>
          <p class="text-gray-600 text-sm mb-2">Description</p>
          <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>

          <!-- Stock -->
          <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
              <p class="text-gray-700">Stock disponible</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $available; ?>/<?php echo $total_stock; ?></p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-canope-slate h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
            </div>
          </div>

          <!-- Info Items -->
          <div class="space-y-4 mb-8">
            <!-- Titre -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                📔
              </div>
              <div>
                <p class="text-gray-600 text-sm">Titre</p>
                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['name']); ?></p>
              </div>
            </div>

            <!-- Collection -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                📚
              </div>
              <div>
                <p class="text-gray-600 text-sm">Collection</p>
                <p class="text-gray-900 font-medium">
                  <?php
                    if(!empty($product['collection'])){
                      echo htmlspecialchars($product['collection']);
                    }else {
                        echo htmlspecialchars('Pas de collection pour cette dotation');
                    }
                  ?>
                </p>
              </div>
            </div>

            <!-- Niveau -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                🏷️
              </div>
              <div>
                <p class="text-gray-600 text-sm">Niveau</p>
                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['category_name']); ?></p>
              </div>
            </div>

            <!-- Discipline -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                🎓
              </div>
              <div>
                <p class="text-gray-600 text-sm">Discipline</p>
                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['discipline']); ?></p>
              </div>
            </div>



            <!-- Langue -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                🌍
              </div>
              <div>
                <p class="text-gray-600 text-sm">Langue</p>
                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['langue']); ?></p>
              </div>
            </div>

            <!-- Type de ressource -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                📦
              </div>
              <div>
                <p class="text-gray-600 text-sm">Type de la ressource</p>
                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['ressource']); ?></p>
              </div>
            </div>
          <!-- CTA Button -->
            <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>')" 
                class=" w-full bg-gradient-to-r from-canope-slate to-canope-teal hover:from-canope-dark hover:to-canope-gray text-white font-bold py-3 px-6 rounded-full shadow-lg transform transition-all duration-500 ease-in-out hover:scale-110  hover:animate-pulse active:animate-bounce">
                    🛒 Demander cette dotation
            </button>
        </div>
      </div>
    </main>
  </body>
</html>




<?php include 'includes/footer.php'; ?>