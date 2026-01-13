<?php
require_once 'includes/db.php';

$product_id = (int) $_GET['id'];


$stmt = $pdo->prepare('
    SELECT p.*, c.name as category_name, s.quantity as stock_quantity
    FROM product p
    LEFT JOIN category c ON p.category_id = c.id
    LEFT JOIN stock s ON p.id = s.product_id
    WHERE p.id = ?
');
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Produit non trouvé');
}

$img_stmt = $pdo->prepare('SELECT url, alt_text FROM product_image WHERE product_id = ? LIMIT 1');
$img_stmt->execute([$product_id]);
$image = $img_stmt->fetch();

$total_stock = 100;
$available = $product['stock_quantity'] ?? 0;
$percentage = ($available / $total_stock) * 100;

$responsible_stmt = $pdo->prepare('SELECT first_name, last_name, job_title FROM responsible ORDER BY id LIMIT 1');
$responsible_stmt->execute();
$responsible = $responsible_stmt->fetch();

include 'includes/header.php';
?>

<!-- section header -->
<div onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes(htmlspecialchars($product['name'])); ?>')" 
class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <!-- From Uiverse.io by Rahulcheryala --> 
     <a href="./donations.php">
        <button
            type="button"
            class="bg-white text-center w-40 rounded-2xl h-10 relative text-black text-xl font-semibold border-4 border-white group"
            >
            <div
                class="bg-canope-gray rounded-xl h-8 w-1/4 grid place-items-center absolute left-0 top-0 group-hover:w-full z-10 duration-500"
            >
                <svg
                    width="25px"
                    height="25px"
                    viewBox="0 0 1024 1024"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill="#ffffffff"
                        d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"
                    ></path>
                    <path
                        fill="#ffffffff"
                        d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"
                    ></path>
                </svg>
            </div>
        <p class="translate-x-4"></p>
        </button>
        </a>
    </div>
</div>

<!--Contenu principal -->
 <main class="max-w-7xl mx-auto px-4 py-8">
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
          <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($product['category_name'] ?? 'Équipement'); ?></p>
          <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>

          <p class="text-gray-600 mb-6 leading-relaxed">
            <?php echo htmlspecialchars($product['description'] ?? 'Aucune description disponible.'); ?>
          </p>

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
            <!-- Location -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                📍
              </div>
              <div>
                <p class="text-gray-600 text-sm">Lieu de stockage</p>
                <p class="text-gray-900 font-medium">Atelier Canopé Ajaccio</p>
              </div>
            </div>

            <!-- Responsible -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
                👤
              </div>
              <div>
                <p class="text-gray-600 text-sm">Responsable</p>
                <p class="text-gray-900 font-medium">
                  <?php
                    if ($responsible) {
                      echo htmlspecialchars($responsible['first_name'] . ' ' . $responsible['last_name']);
                    } else {
                      echo 'Non assigné';
                    }
                  ?>
                </p>
              </div>
            </div>

            <!-- Conditions -->
            <div class="flex gap-4">
              <div class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1">
              </div>
              <div>
              </div>
            </div>
          </div>

          <!-- CTA Button -->
          <button class="w-full bg-gradient-to-r from-canope-slate to-canope-teal text-white font-medium py-3 px-6 rounded-lg hover:from-canope-teal hover:to-canope-slate transition-all flex items-center justify-center gap-2">
            🛒 Demander cette dotation
          </button>
        </div>
      </div>
    </main>
  </body>
</html>




<?php include 'includes/footer.php'; ?>