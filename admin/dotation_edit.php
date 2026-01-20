<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$success = '';

if ($id === 0) {
    header('Location: stock.php');
    exit;
}

// Récupérer les données du produit
$stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: stock.php');
    exit;
}

// Récupérer les catégories
$categoriesQuery = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les responsables
$responsiblesQuery = $pdo->query("SELECT id, last_name, first_name FROM responsible ORDER BY last_name");
$responsibles = $responsiblesQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les lieux de stockage
$locationsQuery = $pdo->query("SELECT DISTINCT location FROM product WHERE location IS NOT NULL AND location != '' ORDER BY location");
$locations = $locationsQuery->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $location = trim($_POST['location'] ?? '');
    $responsible_id = intval($_POST['responsible_id'] ?? 0);
    $stock_initial = intval($_POST['stock_initial'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Gestion de l'upload d'image
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        
        if (in_array($file['type'], $allowedTypes)) {
            // Créer le dossier s'il n'existe pas
            $uploadDir = '../assets/img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Générer un nom unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('product_') . '.' . $extension;
            $destination = $uploadDir . $newFileName;
            
            // Déplacer le fichier
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image_url = 'assets/img/' . $newFileName;
            } else {
                $error = 'Erreur lors de l\'upload de l\'image.';
            }
        } else {
            $error = 'Format d\'image non autorisé. Utilisez JPG, PNG ou WEBP.';
        }
    }

    if (empty($name)) {
        $error = 'Le nom est obligatoire.';
    } elseif ($category_id === 0) {
        $error = 'La catégorie est obligatoire.';
    } elseif ($stock_initial < 0 || $stock < 0) {
        $error = 'Les quantités ne peuvent pas être négatives.';
    } else {
        try {
            // Mettre à jour le produit
            $stmt = $pdo->prepare("
                UPDATE product 
                SET name = ?, description = ?, category_id = ?, location = ?, 
                    responsible_id = ?, quantite_totale = ?, stock = ?, 
                    is_active = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $name,
                $description,
                $category_id,
                $location,
                $responsible_id,
                $stock_initial,
                $stock,
                $is_active,
                $id
            ]);

            // Mettre à jour l'image si uploadée
            if (!empty($image_url)) {
                // Vérifier si une image existe déjà
                $imgStmt = $pdo->prepare("SELECT id FROM product_image WHERE product_id = ?");
                $imgStmt->execute([$id]);
                $existingImage = $imgStmt->fetch();

                if ($existingImage) {
                    // Mettre à jour l'image existante
                    $updateImg = $pdo->prepare("UPDATE product_image SET url = ? WHERE product_id = ?");
                    $updateImg->execute([$image_url, $id]);
                } else {
                    // Insérer une nouvelle image
                    $insertImg = $pdo->prepare("INSERT INTO product_image (product_id, url) VALUES (?, ?)");
                    $insertImg->execute([$id, $image_url]);
                }
            }

            // Ajouter l'historique de modification
            $histoStmt = $pdo->prepare("INSERT INTO historique_modif (product_id, date_modif) VALUES (?, CURDATE())");
            $histoStmt->execute([$id]);

            $success = 'Dotation modifiée avec succès !';
            
            // Recharger les données du produit
            $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $error = 'Erreur lors de la modification : ' . $e->getMessage();
        }
    }
}

// Récupérer l'URL de l'image actuelle
$imageStmt = $pdo->prepare("SELECT url FROM product_image WHERE product_id = ?");
$imageStmt->execute([$id]);
$currentImage = $imageStmt->fetch(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center gap-4 mb-6">
            <a href="stock.php" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Modifier la dotation</h1>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" required
                       value="<?= htmlspecialchars($product['name']) ?>"
                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <!-- Catégorie et Lieu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Catégorie -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Lieu de stockage -->
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                        Lieu de stockage <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" list="locationsList" required
                           value="<?= htmlspecialchars($product['location'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <datalist id="locationsList">
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= htmlspecialchars($loc) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>

            <!-- Responsable -->
            <div>
                <label for="responsible_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Responsable <span class="text-red-500">*</span>
                </label>
                <select id="responsible_id" name="responsible_id" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Sélectionnez un responsable</option>
                    <?php foreach ($responsibles as $resp): ?>
                        <option value="<?= $resp['id'] ?>" <?= $product['responsible_id'] == $resp['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($resp['first_name'] . ' ' . $resp['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Upload Photo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Photo du produit
                </label>
                
                <!-- Aperçu de l'image actuelle -->
                <?php if (!empty($currentImage['url'])): ?>
                    <div class="mb-3 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                        <img src="../<?= htmlspecialchars($currentImage['url']) ?>" alt="Image actuelle" class="max-h-32 rounded-lg">
                    </div>
                <?php endif; ?>
                
                <!-- Zone de glisser-déposer -->
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer bg-gray-50">
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
                    
                    <div id="uploadPrompt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-gray-600 font-medium mb-1">Glissez une nouvelle image ici ou cliquez pour parcourir</p>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 5MB</p>
                    </div>
                    
                    <div id="imagePreview" class="hidden">
                        <img id="previewImg" src="" alt="Aperçu" class="max-h-48 mx-auto rounded-lg mb-3">
                        <p id="fileName" class="text-sm text-gray-600 mb-2"></p>
                        <button type="button" onclick="removeImage()" class="text-red-600 text-sm hover:text-red-700 font-medium">
                            ✕ Supprimer cette image
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quantités -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quantité totale -->
                <div>
                    <label for="stock_initial" class="block text-sm font-semibold text-gray-700 mb-2">
                        Quantité totale <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock_initial" name="stock_initial" min="0" required
                           value="<?= $product['quantite_totale'] ?>"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <!-- Quantité disponible -->
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                        Quantité disponible <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" min="0" required
                           value="<?= $product['stock'] ?>"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>

            <!-- Toggle Dotation active -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_active" name="is_active" <?= $product['is_active'] ? 'checked' : '' ?>
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
                <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">
                    Dotation active
                </label>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="stock.php" 
                   class="flex-1 px-6 py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-colors text-center">
                    Annuler
                </a>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const imageInput = document.getElementById('imageInput');
const uploadPrompt = document.getElementById('uploadPrompt');
const imagePreview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');
const fileName = document.getElementById('fileName');

// Clic sur la zone = ouvrir le sélecteur de fichier
dropZone.addEventListener('click', () => {
    imageInput.click();
});

// Changement de fichier
imageInput.addEventListener('change', (e) => {
    handleFile(e.target.files[0]);
});

// Glisser-déposer
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        imageInput.files = e.dataTransfer.files;
        handleFile(file);
    }
});

// Traiter le fichier
function handleFile(file) {
    if (!file) return;
    
    // Vérifier la taille (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('L\'image est trop grande. Maximum 5MB.');
        return;
    }
    
    // Afficher l'aperçu
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        fileName.textContent = file.name;
        uploadPrompt.classList.add('hidden');
        imagePreview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

// Supprimer l'image
function removeImage() {
    imageInput.value = '';
    uploadPrompt.classList.remove('hidden');
    imagePreview.classList.add('hidden');
    previewImg.src = '';
    fileName.textContent = '';
}
</script>

<?php include 'includes/admin_footer.php'; ?>