<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>
<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg stroke="#FFFFFF" class="w-16 h-16 mx-auto ml-1.5 -mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"">
							<path d="M9.719,17.073l-6.562-6.51c-0.27-0.268-0.504-0.567-0.696-0.888C1.385,7.89,1.67,5.613,3.155,4.14c0.864-0.856,2.012-1.329,3.233-1.329c1.924,0,3.115,1.12,3.612,1.752c0.499-0.634,1.689-1.752,3.612-1.752c1.221,0,2.369,0.472,3.233,1.329c1.484,1.473,1.771,3.75,0.693,5.537c-0.19,0.32-0.425,0.618-0.695,0.887l-6.562,6.51C10.125,17.229,9.875,17.229,9.719,17.073 M6.388,3.61C5.379,3.61,4.431,4,3.717,4.707C2.495,5.92,2.259,7.794,3.145,9.265c0.158,0.265,0.351,0.51,0.574,0.731L10,16.228l6.281-6.232c0.224-0.221,0.416-0.466,0.573-0.729c0.887-1.472,0.651-3.346-0.571-4.56C15.57,4,14.621,3.61,13.612,3.61c-1.43,0-2.639,0.786-3.268,1.863c-0.154,0.264-0.536,0.264-0.69,0C9.029,4.397,7.82,3.61,6.388,3.61"></path>
						</svg>
            </div>
            <h1 class="text-3xl font-semibold text-white">Mes favoris</h1>
        </div>
        <p class="text-white/80 text-sm ml-13">Ici consultez vos favoris afin de les ajouter à votre sélection</p>
    </div>
</div>



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

