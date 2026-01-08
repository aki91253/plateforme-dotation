<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-5 py-8">
    <h1 class="text-4xl font-normal mb-8 text-gray-900">Mes favoris</h1>
    
    <!-- Favoris Container -->
    <div id="favorites-container">
        <div id="favorites-empty" class="text-center py-16 hidden">
            <span class="text-6xl mb-4 block">💛</span>
            <p class="text-gray-500 text-lg">Vous n'avez pas encore ajouté de favoris.</p>
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Parcourir les dotations</a>
        </div>
        
        <div id="favorites-items" class="space-y-4">
            <!-- Favoris items seront ajoutés dynamiquement ici -->
        </div>
    </div>
</div>

<script>
// Récupérer les favoris depuis localStorage
function getFavorites() {
    return JSON.parse(localStorage.getItem('canope_fav') || '[]');
}

// Retirer un favori
function removeFavorite(id) {
    let favorites = getFavorites();
    favorites = favorites.filter(item => item.id !== id);
    localStorage.setItem('canope_fav', JSON.stringify(favorites));
    displayFavorites();
}

// Afficher les favoris
function displayFavorites() {
    const favorites = getFavorites();
    const container = document.getElementById('favorites-items');
    const empty = document.getElementById('favorites-empty');

    if (favorites.length === 0) {
        empty.classList.remove('hidden');
        container.innerHTML = '';
        return;
    }

    empty.classList.add('hidden');

    let html = '';
    favorites.forEach(item => {
        html += `
            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">${item.name}</h3>
                </div>
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
                <button onclick="removeFavorite(${item.id})"
                        class="text-red-500 hover:text-red-700 transition-colors p-2" title="Retirer">
                    ✕
                </button>
            </div>
        `;
    });

    container.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', displayFavorites);
</script>

<?php include 'includes/footer.php'; ?>

